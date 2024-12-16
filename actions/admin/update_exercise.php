<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    exit('Unauthorized');
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Update words table first
    $stmt = $pdo->prepare("
        UPDATE words 
        SET languageId = ?, categoryId = ?, original_text = ?, 
            pronunciation = ?, difficulty = ?
        WHERE wordId = (
            SELECT wordId FROM exercise_sets WHERE exerciseId = ?
        )
    ");
    $stmt->execute([
        $data['languageId'],
        $data['categoryId'],
        $data['questionText'],
        $data['pronunciationText'],
        $data['difficulty'],
        $data['exerciseId']
    ]);
    
    // Handle word bank updates
    // First, delete existing word bank entries
    $stmt = $pdo->prepare("DELETE FROM exercise_word_bank WHERE exerciseId = ?");
    $stmt->execute([$data['exerciseId']]);
    
    // Insert new word bank entries
    foreach ($data['wordBank'] as $index => $word) {
        // First, insert or get word from word_bank table
        $stmt = $pdo->prepare("
            INSERT INTO word_bank (segment_text, languageId) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE bankWordId = LAST_INSERT_ID(bankWordId)
        ");
        $stmt->execute([$word['text'], $data['languageId']]);
        $bankWordId = $pdo->lastInsertId();
        
        // Then link it to the exercise
        $stmt = $pdo->prepare("
            INSERT INTO exercise_word_bank (exerciseId, bankWordId, is_answer, position) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['exerciseId'],
            $bankWordId,
            $word['isAnswer'] ? 1 : 0,
            $index
        ]);
    }
    
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
