<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

try {
    require_once '../config/db_connect.php';

    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['wordId']) || !isset($input['segmentOrder'])) {
        throw new Exception('Missing required fields');
    }

    $wordId = (int)$input['wordId'];
    $userSegmentOrder = $input['segmentOrder']; // Array of segment IDs

    // Get correct segment order
    $stmt = $pdo->prepare("
        SELECT s.segment_id, s.position 
        FROM word_segments s 
        WHERE s.word_id = ? AND s.is_distractor = FALSE 
        ORDER BY s.position
    ");
    $stmt->execute([$wordId]);
    $correctOrder = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert to simple arrays for comparison
    $correctSegmentIds = array_column($correctOrder, 'segment_id');
    
    // Remove any distractor segments from user answer
    $userSegmentOrder = array_values(array_intersect($userSegmentOrder, $correctSegmentIds));
    
    $isCorrect = ($userSegmentOrder === $correctSegmentIds);

    if ($isCorrect) {
        // Record the learned word
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO learned_words 
            (userId, wordId, proficiency, learnedDate) 
            VALUES (?, ?, 'learning', CURRENT_TIMESTAMP)
        ");
        $stmt->execute([$_SESSION['user_id'], $wordId]);
    }

    echo json_encode([
        'success' => true,
        'correct' => $isCorrect,
        'correctOrder' => $correctSegmentIds
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 