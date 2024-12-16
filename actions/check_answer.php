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
    
    if (!isset($input['exerciseId']) || !isset($input['answer'])) {
        throw new Exception('Missing required fields');
    }

    // Get correct sequence from exercise_word_bank
    $stmt = $pdo->prepare("
        SELECT 
            wb.segment_text,
            ewb.position
        FROM exercise_word_bank ewb
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        WHERE ewb.exerciseId = ?
        AND ewb.is_answer = 1
        ORDER BY ewb.position
    ");

    $stmt->execute([$input['exerciseId']]);
    $correctSequence = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build correct answer string
    $correctAnswer = implode(' ', array_map(function($word) {
        return $word['segment_text'];
    }, $correctSequence));

    // Compare with user answer
    $isCorrect = (trim($input['answer']) === trim($correctAnswer));

    $pdo->beginTransaction();
    
    // Record the attempt in word_attempts table
    $stmt = $pdo->prepare("
        INSERT INTO word_attempts (userId, wordId, isCorrect, attemptDate)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$userId, $wordId, $isCorrect]);

    // Update or insert into learned_words table
    $stmt = $pdo->prepare("
        INSERT INTO learned_words (userId, wordId, correct_attempts, total_attempts, last_attempt_date)
        VALUES (?, ?, ?, 1, NOW())
        ON DUPLICATE KEY UPDATE
            correct_attempts = correct_attempts + ?,
            total_attempts = total_attempts + 1,
            last_attempt_date = NOW()
    ");
    $stmt->execute([$userId, $wordId, $isCorrect ? 1 : 0, $isCorrect ? 1 : 0]);

    // Update proficiency based on success rate
    $stmt = $pdo->prepare("
        UPDATE learned_words 
        SET proficiency = CASE
            WHEN (correct_attempts/total_attempts) >= 0.8 THEN 'mastered'
            WHEN (correct_attempts/total_attempts) >= 0.6 THEN 'familiar'
            ELSE 'learning'
        END
        WHERE userId = ? AND wordId = ?
    ");
    $stmt->execute([$userId, $wordId]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'correct' => $isCorrect
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error in check_answer.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 