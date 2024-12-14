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

    // Get correct sequence from exercise
    $stmt = $pdo->prepare("
        SELECT 
            e.correct_sequence,
            e.wordId,
            e.translationId
        FROM exercise_sets e
        WHERE e.exerciseId = ?
    ");
    $stmt->execute([$input['exerciseId']]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exercise) {
        throw new Exception('Exercise not found');
    }

    $correctSequence = json_decode($exercise['correct_sequence'], true);
    $userAnswer = explode(' ', trim($input['answer']));

    // Check if arrays have same length
    if (count($userAnswer) !== count($correctSequence)) {
        echo json_encode([
            'success' => true,
            'correct' => false,
            'hint' => 'Wrong number of words'
        ]);
        exit();
    }

    // Check each position
    $isCorrect = true;
    for ($i = 0; $i < count($correctSequence); $i++) {
        if ($userAnswer[$i] !== $correctSequence[$i]['text']) {
            $isCorrect = false;
            break;
        }
    }

    if ($isCorrect) {
        // Update user progress
        $stmt = $pdo->prepare("
            INSERT INTO learned_words 
                (userId, wordId, proficiency, learnedDate) 
            VALUES (?, ?, 'learning', CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE 
                proficiency = CASE 
                    WHEN proficiency = 'learning' THEN 'familiar'
                    WHEN proficiency = 'familiar' THEN 'mastered'
                    ELSE proficiency
                END
        ");
        $stmt->execute([$_SESSION['user_id'], $exercise['wordId']]);
    }

    echo json_encode([
        'success' => true,
        'correct' => $isCorrect,
        'correctSequence' => $correctSequence,
        'hint' => $isCorrect ? null : 'Check word order'
    ]);

} catch (Exception $e) {
    error_log("Error in check_answer.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'wordId' => $exercise['wordId'] ?? null,
            'answerId' => $input['answer'] ?? null
        ]
    ]);
} 