<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    exit('Unauthorized access');
}

$languageId = isset($_GET['languageId']) ? $_GET['languageId'] : '';

try {
    $query = "
        SELECT 
            e.exerciseId,
            e.question,
            e.difficulty,
            e.answer,
            c.categoryName,
            l.languageName
        FROM exercise_templates e
        JOIN word_categories c ON e.categoryId = c.categoryId
        JOIN languages l ON e.languageId = l.languageId
        WHERE 1=1
    ";

    if ($languageId !== '') {
        $query .= " AND e.languageId = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$languageId]);
    } else {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($exercises);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching exercises']);
} 