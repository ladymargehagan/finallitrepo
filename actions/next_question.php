<?php
session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Not authenticated');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    
    // Get parameters
    $languageId = filter_input(INPUT_POST, 'languageId', FILTER_VALIDATE_INT);
    $categorySlug = htmlspecialchars(trim($input['category'] ?? ''));

    // Increment question counter
    if (!isset($_SESSION['current_question'])) {
        $_SESSION['current_question'] = 1;
    } else {
        $_SESSION['current_question'] = min($_SESSION['current_question'] + 1, 5);
    }

    // Get next exercise
    $stmt = $pdo->prepare("
        SELECT 
            es.exerciseId,
            es.wordId,
            w.word,
            w.pronunciation,
            w.context_type,
            w.difficulty,
            es.type
        FROM exercise_sets es
        JOIN words w ON es.wordId = w.wordId
        JOIN word_categories wc ON w.categoryId = wc.categoryId
        WHERE w.languageId = ? 
        AND wc.categorySlug = ?
        AND es.exerciseId != ?
        ORDER BY RAND()
        LIMIT 1
    ");

    $stmt->execute([$languageId, $categorySlug, $input['currentExerciseId']]);
    $nextExercise = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$nextExercise) {
        throw new Exception('No more exercises available');
    }

    // Get word bank for next exercise
    $wordBankStmt = $pdo->prepare("
        SELECT 
            wb.bankWordId,
            wb.segment_text,
            wb.part_of_speech,
            ewb.is_answer,
            ewb.position
        FROM exercise_word_bank ewb
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        WHERE ewb.exerciseId = ?
        ORDER BY RAND()
    ");
    
    $wordBankStmt->execute([$nextExercise['exerciseId']]);
    $wordBank = $wordBankStmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if we've reached 5 questions
    $isComplete = $_SESSION['current_question'] >= 5;

    echo json_encode([
        'success' => true,
        'exercise' => $nextExercise,
        'wordBank' => $wordBank,
        'questionCount' => [
            'current' => $_SESSION['current_question'],
            'total' => 5
        ],
        'isComplete' => $isComplete
    ]);

} catch (Exception $e) {
    error_log("Error in next_question.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 