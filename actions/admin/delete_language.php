<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $languageId = $data['languageId'];
    
    $pdo->beginTransaction();
    
    // Delete in the correct order to respect foreign key constraints
    
    // 1. Delete exercise word bank entries
    $stmt = $pdo->prepare("
        DELETE ewb FROM exercise_word_bank ewb
        JOIN exercise_sets es ON ewb.exerciseId = es.exerciseId
        JOIN words w ON es.wordId = w.wordId
        WHERE w.languageId = ?
    ");
    $stmt->execute([$languageId]);
    
    // 2. Delete word bank entries
    $stmt = $pdo->prepare("DELETE FROM word_bank WHERE languageId = ?");
    $stmt->execute([$languageId]);
    
    // 3. Delete exercise sets and related translations
    $stmt = $pdo->prepare("
        DELETE es, t FROM exercise_sets es
        JOIN words w ON es.wordId = w.wordId
        LEFT JOIN translations t ON w.wordId = t.wordId
        WHERE w.languageId = ?
    ");
    $stmt->execute([$languageId]);
    
    // 4. Delete user enrollments
    $stmt = $pdo->prepare("DELETE FROM user_enrollments WHERE languageId = ?");
    $stmt->execute([$languageId]);
    
    // 5. Delete words
    $stmt = $pdo->prepare("DELETE FROM words WHERE languageId = ?");
    $stmt->execute([$languageId]);
    
    // 6. Finally delete the language
    $stmt = $pdo->prepare("DELETE FROM languages WHERE languageId = ?");
    $stmt->execute([$languageId]);
    
    $pdo->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 