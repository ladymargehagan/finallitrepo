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
    
    $pdo->beginTransaction();
    
    // Insert into exercise_sets
    $stmt = $pdo->prepare("
        INSERT INTO exercise_sets (
            languageId, 
            categoryId, 
            difficulty, 
            type
        ) VALUES (?, ?, ?, 'translation')
    ");
    
    $stmt->execute([
        $data['languageId'],
        $data['categoryId'],
        $data['difficulty']
    ]);
    
    $exerciseId = $pdo->lastInsertId();
    
    // Insert question text into word_bank
    $stmt = $pdo->prepare("
        INSERT INTO word_bank (
            segment_text,
            languageId
        ) VALUES (?, ?)
    ");
    $stmt->execute([$data['questionText'], $data['languageId']]);
    $questionBankId = $pdo->lastInsertId();
    
    // Insert words into word_bank and exercise_word_bank
    foreach ($data['wordBank'] as $position => $word) {
        // Insert word into word_bank
        $stmt = $pdo->prepare("
            INSERT INTO word_bank (
                segment_text,
                languageId
            ) VALUES (?, ?)
        ");
        $stmt->execute([$word, $data['languageId']]);
        $bankWordId = $pdo->lastInsertId();
        
        // Link word to exercise
        $stmt = $pdo->prepare("
            INSERT INTO exercise_word_bank (
                exerciseId,
                bankWordId,
                position,
                is_answer
            ) VALUES (?, ?, ?, ?)
        ");
        
        // Check if this word is part of the answer
        $isAnswer = in_array($word, explode(' ', $data['answer'])) ? 1 : 0;
        
        $stmt->execute([
            $exerciseId,
            $bankWordId,
            $position,
            $isAnswer
        ]);
    }
    
    $pdo->commit();
    echo json_encode(['success' => true, 'exerciseId' => $exerciseId]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 