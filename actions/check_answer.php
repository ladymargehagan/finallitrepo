<?php
session_start();
require_once '../config/db_connect.php';

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$exerciseId = $data['exerciseId'] ?? null;
$userAnswer = $data['answer'] ?? '';

// Initialize response array
$response = [
    'isCorrect' => false,
    'message' => ''
];

try {
    // Get the correct answer from database
    $stmt = $pdo->prepare("
        SELECT 
            es.exerciseId,
            w.word,
            w.translation
        FROM exercise_sets es
        JOIN words w ON es.wordId = w.wordId
        WHERE es.exerciseId = ?
    ");
    
    $stmt->execute([$exerciseId]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($exercise) {
        $correctAnswer = $exercise['translation'];
        $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer));
        
        // Initialize exercise_answers array if not exists
        if (!isset($_SESSION['exercise_answers'])) {
            $_SESSION['exercise_answers'] = [];
        }
        
        // Store the answer data only if it hasn't been stored already
        if (!isset($_SESSION['exercise_answers'][$exerciseId])) {
            $_SESSION['exercise_answers'][$exerciseId] = [
                'word' => $exercise['word'],
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'correct' => $isCorrect
            ];
        }
        
        if ($isCorrect) {
            $_SESSION['last_correct'] = true;
            if (!isset($_SESSION['exercise_results'])) {
                $_SESSION['exercise_results'] = [
                    'correct_words' => 0,
                    'total_words' => 0
                ];
            }
            $_SESSION['exercise_results']['correct_words']++;
            
            $response['isCorrect'] = true;
            $response['message'] = 'Correct!';
        } else {
            $response['message'] = 'Try again';
        }
    }
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    $response['message'] = 'An error occurred';
    error_log("Error checking answer: " . $e->getMessage());
    echo json_encode($response);
} 