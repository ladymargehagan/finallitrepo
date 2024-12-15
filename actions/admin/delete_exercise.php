<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['exerciseId'])) {
        throw new Exception('Exercise ID is required');
    }

    $exerciseId = $data['exerciseId'];
    
    $pdo->beginTransaction();

    // Delete from exercise_word_bank first (foreign key constraint)
    $stmt = $pdo->prepare("DELETE FROM exercise_word_bank WHERE exerciseId = ?");
    $stmt->execute([$exerciseId]);

    // Delete from exercise_sets
    $stmt = $pdo->prepare("DELETE FROM exercise_sets WHERE exerciseId = ?");
    $stmt->execute([$exerciseId]);

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 