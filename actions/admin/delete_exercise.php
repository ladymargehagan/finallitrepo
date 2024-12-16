<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['exerciseId'])) {
        throw new Exception('Exercise ID is required');
    }

    $exerciseId = (int)$data['exerciseId'];
    
    $pdo->beginTransaction();

    // 1. Get the wordId associated with this exercise
    $stmt = $pdo->prepare("SELECT wordId FROM exercise_sets WHERE exerciseId = ?");
    $stmt->execute([$exerciseId]);
    $wordId = $stmt->fetchColumn();

    if ($wordId) {
        // 2. Delete from exercise_word_bank first (foreign key constraint)
        $stmt = $pdo->prepare("DELETE FROM exercise_word_bank WHERE exerciseId = ?");
        $stmt->execute([$exerciseId]);

        // 3. Delete the exercise set
        $stmt = $pdo->prepare("DELETE FROM exercise_sets WHERE exerciseId = ?");
        $stmt->execute([$exerciseId]);

        // 4. Check if the word is used in any other exercise_sets
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM exercise_sets 
            WHERE wordId = ?
        ");
        $stmt->execute([$wordId]);
        $usageCount = $stmt->fetchColumn();

        if ($usageCount === 0) {
            // Word is not used anywhere else, safe to delete
            // First delete translations (foreign key constraint)
            $stmt = $pdo->prepare("DELETE FROM translations WHERE wordId = ?");
            $stmt->execute([$wordId]);

            // Then delete the word
            $stmt = $pdo->prepare("DELETE FROM words WHERE wordId = ?");
            $stmt->execute([$wordId]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 