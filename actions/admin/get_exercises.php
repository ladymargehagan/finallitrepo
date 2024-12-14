<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $stmt = $pdo->query("
        SELECT 
            es.exerciseId,
            es.difficulty,
            l.languageName,
            wc.categoryName,
            wb.segment_text as questionText
        FROM exercise_sets es
        JOIN words w ON es.wordId = w.wordId
        JOIN languages l ON w.languageId = l.languageId
        JOIN word_categories wc ON w.categoryId = wc.categoryId
        JOIN exercise_word_bank ewb ON es.exerciseId = ewb.exerciseId
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        GROUP BY es.exerciseId
        ORDER BY es.created_at DESC
    ");
    
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'exercises' => $exercises]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
} 