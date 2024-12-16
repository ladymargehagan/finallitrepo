<?php
session_start();
require_once '../config/db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Get the correct answer from database
    $stmt = $pdo->prepare("
        SELECT wb.segment_text 
        FROM exercise_word_bank ewb
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        WHERE ewb.exerciseId = ? AND ewb.is_answer = 1
    ");
    $stmt->execute([$data['exerciseId']]);
    $correctAnswer = $stmt->fetchColumn();

    // Check if answer matches
    $isCorrect = strtolower($data['answer']) === strtolower($correctAnswer);

    if ($isCorrect) {
        // Add to completed exercises
        $_SESSION['completed_exercises'][] = $data['exerciseId'];
    }

    echo json_encode([
        'isCorrect' => $isCorrect,
        'message' => $isCorrect ? 'Correct!' : 'Try again'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'isCorrect' => false,
        'error' => 'An error occurred'
    ]);
} 