<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['answer']) || !isset($_POST['wordId'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM words WHERE wordId = ?");
    $stmt->execute([$_POST['wordId']]);
    $word = $stmt->fetch();

    if (!$word) {
        throw new Exception('Word not found');
    }

    $userAnswer = strtolower(trim($_POST['answer']));
    $correctAnswer = strtolower(trim($word['translation']));

    $isCorrect = ($userAnswer === $correctAnswer);

    if ($isCorrect) {
        // Record the learned word
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO learned_words (user_id, word_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $word['wordId']]);

        // Update user progress
        $stmt = $pdo->prepare("
            UPDATE user_progress 
            SET wordsLearned = wordsLearned + 1
            WHERE userId = ? AND courseId = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $word['courseId']]);
    }

    echo json_encode([
        'success' => true,
        'correct' => $isCorrect,
        'correctAnswer' => $word['translation']
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 