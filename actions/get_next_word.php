<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['courseId']) || !isset($_GET['category'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    // Get next unlearned word for this user in the specified category
    $stmt = $pdo->prepare("
        SELECT w.* 
        FROM words w
        LEFT JOIN learned_words lw ON w.wordId = lw.word_id AND lw.user_id = ?
        WHERE w.courseId = ? 
        AND w.category = ?
        AND lw.id IS NULL
        ORDER BY w.difficulty, RAND()
        LIMIT 1
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $_GET['courseId'],
        $_GET['category']
    ]);
    
    $word = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$word) {
        // If all words are learned, get a random word for review
        $stmt = $pdo->prepare("
            SELECT * FROM words 
            WHERE courseId = ? AND category = ?
            ORDER BY RAND() 
            LIMIT 1
        ");
        $stmt->execute([$_GET['courseId'], $_GET['category']]);
        $word = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($word) {
        // Create word tiles from the translation
        $translation = $word['translation'];
        $words = explode(' ', $translation);
        shuffle($words); // Randomize the order of words
        
        echo json_encode([
            'success' => true,
            'word' => [
                'id' => $word['wordId'],
                'original' => $word['word'],
                'pronunciation' => $word['pronunciation'],
                'wordTiles' => $words
            ]
        ]);
    } else {
        throw new Exception('No words available');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 