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

    // Get word with translation
    $query = "
        SELECT 
            w.wordId,
            w.original_text,
            w.pronunciation,
            t.translationId
        FROM words w
        INNER JOIN translations t ON w.wordId = t.wordId
        WHERE w.languageId = ? 
        AND w.categoryId = (SELECT categoryId FROM word_categories WHERE categorySlug = ?)
        AND t.is_primary = TRUE
        ORDER BY RAND()
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$courseId, $category]);
    $word = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get segments in correct order
    $segmentsQuery = "
        SELECT 
            segmentId,
            segment_text,
            position,
            part_of_speech
        FROM word_segments 
        WHERE translationId = ?
        ORDER BY position
    ";
    
    $stmt = $pdo->prepare($segmentsQuery);
    $stmt->execute([$word['translationId']]);
    $segments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create exercise record with segment information
    $exerciseQuery = "
        INSERT INTO exercise_sets 
            (wordId, translationId, type, correct_sequence) 
        VALUES (?, ?, 'translation', ?)
    ";
    
    // Store correct sequence as JSON
    $correctSequence = json_encode(array_map(function($segment) {
        return [
            'id' => $segment['segmentId'],
            'text' => $segment['segment_text'],
            'position' => $segment['position']
        ];
    }, $segments));

    $stmt = $pdo->prepare($exerciseQuery);
    $stmt->execute([
        $word['wordId'], 
        $word['translationId'],
        $correctSequence
    ]);
    
    $exerciseId = $pdo->lastInsertId();

    // Return word data with segments
    echo json_encode([
        'success' => true,
        'word' => [
            'id' => $exerciseId,
            'original' => $word['original_text'],
            'pronunciation' => $word['pronunciation'],
            'segments' => array_map(function($segment) {
                return $segment['segment_text'];
            }, $segments)
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