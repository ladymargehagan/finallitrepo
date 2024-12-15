<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Exercise ID is required');
    }
    
    $exerciseId = $_GET['id'];
    
    // Get exercise data with its basic info
    $stmt = $pdo->prepare("
        SELECT 
            es.exerciseId,
            w.word as question_text,
            w.languageId,
            w.categoryId,
            w.difficulty,
            wc.categoryName,
            l.languageName
        FROM exercise_sets es
        JOIN words w ON es.wordId = w.wordId
        JOIN word_categories wc ON w.categoryId = wc.categoryId
        JOIN languages l ON w.languageId = l.languageId
        WHERE es.exerciseId = ?
    ");
    
    $stmt->execute([$exerciseId]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$exercise) {
        throw new Exception('Exercise not found');
    }

    // Get word bank options for this exercise
    $stmt = $pdo->prepare("
        SELECT 
            wb.bankWordId,
            wb.segment_text,
            ewb.is_answer
        FROM exercise_word_bank ewb
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        WHERE ewb.exerciseId = ?
    ");
    
    $stmt->execute([$exerciseId]);
    $wordBank = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format response
    $response = [
        'templateId' => $exercise['exerciseId'],
        'question_text' => $exercise['question_text'],
        'languageId' => $exercise['languageId'],
        'categoryId' => $exercise['categoryId'],
        'difficulty' => $exercise['difficulty'],
        'wordBank' => array_map(function($row) {
            return [
                'bankWordId' => $row['bankWordId'],
                'segment_text' => $row['segment_text'],
                'is_answer' => (bool)$row['is_answer']
            ];
        }, $wordBank)
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 