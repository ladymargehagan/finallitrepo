<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

try {
    $languageId = isset($_GET['languageId']) ? $_GET['languageId'] : '';
    
    $query = "
        SELECT 
            es.exerciseId,
            w.word as question,
            w.difficulty,
            wc.categoryName,
            l.languageName
        FROM exercise_sets es
        JOIN words w ON es.wordId = w.wordId
        JOIN word_categories wc ON w.categoryId = wc.categoryId
        JOIN languages l ON w.languageId = l.languageId
    ";
    
    if ($languageId) {
        $query .= " WHERE w.languageId = ?";
    }
    
    $stmt = $pdo->prepare($query);
    
    if ($languageId) {
        $stmt->execute([$languageId]);
    } else {
        $stmt->execute();
    }
    
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($exercises);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} 