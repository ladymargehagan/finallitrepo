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
            es.exerciseId as id,
            wb.segment_text as questionText,
            l.languageName,
            wc.categoryName,
            es.difficulty
        FROM exercise_sets es
        JOIN languages l ON es.languageId = l.languageId
        JOIN word_categories wc ON es.categoryId = wc.categoryId
        JOIN exercise_word_bank ewb ON es.exerciseId = ewb.exerciseId
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        WHERE ewb.is_question = 1
        ORDER BY es.created_at DESC
    ");
    
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($exercises);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 