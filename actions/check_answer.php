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
    require_once 'ProficiencyTracker.php';

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

    // Get wordId for the exercise
    $stmt = $pdo->prepare("SELECT wordId FROM exercise_sets WHERE exerciseId = ?");
    $stmt->execute([$input['exerciseId']]);
    $wordId = $stmt->fetchColumn();

    // Initialize proficiency tracker and record attempt
    $proficiencyTracker = new ProficiencyTracker($pdo, $_SESSION['user_id']);
    $proficiencyTracker->recordAttempt($wordId, $isCorrect);
    
    // Get updated progress for response
    $progress = $proficiencyTracker->getProgress();

    echo json_encode([
        'success' => true,
        'correct' => $isCorrect,
        'hint' => $isCorrect ? null : 'Check word order',
        'progress' => $progress,
        'proficiency' => [
            'counts' => $progress['counts'],
            'total' => $progress['total_words']
        ]
    ]);

} catch (Exception $e) {
    error_log("Error in check_answer.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 