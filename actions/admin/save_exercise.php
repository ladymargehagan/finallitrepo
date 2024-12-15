<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $pdo->beginTransaction();

    // 1. Insert into exercise_sets
    $stmt = $pdo->prepare("
        INSERT INTO exercise_sets (wordId, translationId, type, difficulty) 
        VALUES (?, ?, 'translation', ?)
    ");

    $stmt->execute([
        $data['wordId'] ?? null,
        $data['translationId'] ?? null,
        $data['difficulty']
    ]);

    $exerciseId = $pdo->lastInsertId();

    // 2. Insert word bank options
    foreach ($data['wordBank'] as $index => $word) {
        // Insert into word_bank
        $stmt = $pdo->prepare("
            INSERT INTO word_bank (segment_text, languageId) 
            VALUES (?, ?)
        ");
        
        $stmt->execute([
            $word['text'],
            $data['languageId']
        ]);
        
        $bankWordId = $pdo->lastInsertId();

        // Link to exercise_word_bank
        $stmt = $pdo->prepare("
            INSERT INTO exercise_word_bank (exerciseId, bankWordId, is_answer, position) 
            VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $exerciseId,
            $bankWordId,
            $word['isAnswer'] ? 1 : 0,
            $index
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'exerciseId' => $exerciseId]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 