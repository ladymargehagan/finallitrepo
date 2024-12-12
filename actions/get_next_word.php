<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config/db_connect.php';
    
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new Exception('Database connection not properly established');
    }
    
    $courseId = filter_input(INPUT_GET, 'courseId', FILTER_VALIDATE_INT);
    $category = htmlspecialchars(trim($_GET['category'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    if (!$courseId) {
        throw new Exception('Invalid course ID');
    }

    if (empty($category)) {
        throw new Exception('Category is required');
    }

    // Get a random word we haven't learned yet or need to review
    $query = "
        SELECT 
            w.wordId,
            w.original_text,
            w.pronunciation,
            w.difficulty,
            w.context_type,
            t.translationId,
            t.translated_text
        FROM words w
        INNER JOIN translations t ON w.wordId = t.wordId
        LEFT JOIN learned_words lw ON w.wordId = lw.wordId 
            AND lw.userId = ?
        WHERE w.languageId = ? 
        AND w.categoryId = (SELECT categoryId FROM word_categories WHERE categorySlug = ?)
        AND (lw.id IS NULL OR lw.proficiency != 'mastered')
        AND t.is_primary = TRUE
        ORDER BY RAND()
        LIMIT 1
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['user_id'], $courseId, $category]);
    $word = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$word) {
        throw new Exception('No words available in this category');
    }

    // Get word segments for the translation
    $segmentsQuery = "
        SELECT 
            ws.segmentId,
            ws.segment_text,
            ws.position,
            ws.part_of_speech
        FROM word_segments ws
        WHERE ws.translationId = ?
        ORDER BY ws.position
    ";
    
    $stmt = $pdo->prepare($segmentsQuery);
    $stmt->execute([$word['translationId']]);
    $correctSegments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get distractors from word bank
    $distractorsQuery = "
        SELECT DISTINCT wb.segment_text
        FROM word_bank wb
        INNER JOIN word_relationships wr 
            ON (wb.bankWordId = wr.word1_id OR wb.bankWordId = wr.word2_id)
        WHERE wb.languageId = ?
        AND wb.difficulty = ?
        AND wb.part_of_speech IN (
            SELECT DISTINCT part_of_speech 
            FROM word_segments 
            WHERE translationId = ?
        )
        AND wb.segment_text NOT IN (
            SELECT segment_text 
            FROM word_segments 
            WHERE translationId = ?
        )
        ORDER BY wb.usage_frequency DESC, RAND()
        LIMIT 3
    ";

    $stmt = $pdo->prepare($distractorsQuery);
    $stmt->execute([$courseId, $word['difficulty'], $word['translationId'], $word['translationId']]);
    $distractors = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Combine correct segments and distractors into word tiles
    $wordTiles = array_merge(
        array_column($correctSegments, 'segment_text'),
        $distractors
    );
    
    // Shuffle the word tiles
    shuffle($wordTiles);

    // Create exercise record
    $exerciseQuery = "
        INSERT INTO exercise_sets 
        (wordId, translationId, difficulty, type) 
        VALUES (?, ?, ?, 'translation')
    ";
    
    $stmt = $pdo->prepare($exerciseQuery);
    $stmt->execute([$word['wordId'], $word['translationId'], $word['difficulty']]);
    $exerciseId = $pdo->lastInsertId();

    // Record word tiles for this exercise
    $tileQuery = "
        INSERT INTO exercise_word_bank 
        (exerciseId, bankWordId, is_answer, position) 
        VALUES (?, (SELECT bankWordId FROM word_bank WHERE segment_text = ?), ?, ?)
    ";
    
    $stmt = $pdo->prepare($tileQuery);
    foreach ($wordTiles as $position => $tile) {
        $isAnswer = in_array($tile, array_column($correctSegments, 'segment_text'));
        $stmt->execute([$exerciseId, $tile, $isAnswer, $position]);
    }

    echo json_encode([
        'success' => true,
        'word' => [
            'id' => $exerciseId,
            'original' => $word['original_text'],
            'pronunciation' => $word['pronunciation'],
            'wordTiles' => $wordTiles,
            'difficulty' => $word['difficulty']
        ]
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 