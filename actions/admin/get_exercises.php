<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

try {
    $languageId = isset($_GET['languageId']) ? $_GET['languageId'] : '';
    
    $stmt = $pdo->prepare("
        SELECT 
            es.exerciseId,
            w.original_text as questionText,
            w.pronunciation,
            w.difficulty,
            wc.categoryName,
            l.languageName,
            w.languageId,
            w.categoryId
        FROM exercise_sets es
        JOIN words w ON es.wordId = w.wordId
        JOIN word_categories wc ON w.categoryId = wc.categoryId
        JOIN languages l ON w.languageId = l.languageId
        WHERE (:languageId = '' OR w.languageId = :languageId)
        ORDER BY es.exerciseId DESC
    ");
    
    $stmt->execute(['languageId' => $languageId]);
    
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($exercises);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} 