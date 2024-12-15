<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Check for all required fields based on exercise_sets table structure
    if (!isset($data['wordId']) || 
        !isset($data['translationId']) || 
        !isset($data['type']) || 
        !isset($data['difficulty']) ||
        !isset($data['wordBankOptions']) || 
        !is_array($data['wordBankOptions'])) {
        throw new Exception('Missing required fields');
    }

    // Begin transaction
    $pdo->beginTransaction();

    // Insert into exercise_sets
    $stmt = $pdo->prepare("
        INSERT INTO exercise_sets (wordId, translationId, type, difficulty) 
        VALUES (:wordId, :translationId, :type, :difficulty)
    ");

    $stmt->execute([
        ':wordId' => $data['wordId'],
        ':translationId' => $data['translationId'],
        ':type' => $data['type'],
        ':difficulty' => $data['difficulty']
    ]);

    $exerciseId = $pdo->lastInsertId();

    // Insert word bank options
    $stmt = $pdo->prepare("
        INSERT INTO exercise_word_bank (exerciseId, bankWordId, is_answer, position) 
        VALUES (:exerciseId, :bankWordId, :is_answer, :position)
    ");

    foreach ($data['wordBankOptions'] as $index => $option) {
        if (!isset($option['bankWordId'])) {
            throw new Exception('Invalid word bank option format');
        }
        
        $stmt->execute([
            ':exerciseId' => $exerciseId,
            ':bankWordId' => $option['bankWordId'],
            ':is_answer' => isset($option['isAnswer']) && $option['isAnswer'] ? 1 : 0,
            ':position' => $index
        ]);
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'exerciseId' => $exerciseId
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 