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

    // Get correct sequence and wordId from exercise_word_bank
    $stmt = $pdo->prepare("
        SELECT 
            wb.segment_text,
            ewb.position,
            es.wordId
        FROM exercise_word_bank ewb
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        JOIN exercise_sets es ON ewb.exerciseId = es.exerciseId
        WHERE ewb.exerciseId = ?
        AND ewb.is_answer = 1
        ORDER BY ewb.position
    ");

    $stmt->execute([$input['exerciseId']]);
    $correctSequence = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($correctSequence)) {
        throw new Exception('No correct answer found for this exercise');
    }

    // Get wordId from the first result
    $wordId = $correctSequence[0]['wordId'];

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
    $stmt->execute([$_SESSION['user_id'], $wordId, $isCorrect]);

    // Update or insert into learned_words table
    $stmt = $pdo->prepare("
        INSERT INTO learned_words (userId, wordId, correct_attempts, total_attempts, last_attempt_date)
        VALUES (?, ?, ?, 1, NOW())
        ON DUPLICATE KEY UPDATE
            correct_attempts = correct_attempts + ?,
            total_attempts = total_attempts + 1,
            last_attempt_date = NOW()
    ");
    $stmt->execute([$_SESSION['user_id'], $wordId, $isCorrect ? 1 : 0, $isCorrect ? 1 : 0]);

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
    $stmt->execute([$_SESSION['user_id'], $wordId]);

    $pdo->commit();

    if ($isCorrect) {
        // First get the language and category IDs for the current exercise
        $exerciseInfoStmt = $pdo->prepare("
            SELECT w.languageId, w.categoryId 
            FROM words w
            WHERE w.wordId = ?
        ");
        $exerciseInfoStmt->execute([$wordId]);
        $exerciseInfo = $exerciseInfoStmt->fetch(PDO::FETCH_ASSOC);
        
        // Now use these IDs in the progress calculation
        $progressStmt = $pdo->prepare("
            SELECT 
                COUNT(DISTINCT es.exerciseId) as completed,
                (SELECT COUNT(*) FROM exercise_sets WHERE wordId IN 
                    (SELECT wordId FROM words WHERE languageId = ? AND categoryId = ?)
                ) as total
            FROM exercise_sets es
            JOIN learned_words lw ON es.wordId = lw.wordId
            WHERE lw.userId = ? AND lw.proficiency IN ('familiar', 'mastered')
        ");
        
        $progressStmt->execute([
            $exerciseInfo['languageId'], 
            $exerciseInfo['categoryId'], 
            $_SESSION['user_id']
        ]);
        $progress = $progressStmt->fetch(PDO::FETCH_ASSOC);
        
        // Add safety check for division by zero
        $progressPercentage = $progress['total'] > 0 
            ? ($progress['completed'] / $progress['total']) * 100 
            : 0;
        
        echo json_encode([
            'success' => true,
            'correct' => $isCorrect,
            'correctAnswer' => $correctAnswer,
            'progress' => [
                'progress' => $progressPercentage,
                'completed' => $progress['completed'],
                'total' => max(1, $progress['total']) // Ensure total is at least 1
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'correct' => false,
            'correctAnswer' => $correctAnswer
        ]);
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error in check_answer.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 