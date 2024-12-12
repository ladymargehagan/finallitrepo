<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config/db_connect.php';
    
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new Exception('Database connection not properly established');
    }
    
    $courseId = filter_input(INPUT_GET, 'courseId', FILTER_VALIDATE_INT);
    $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
    
    if (!$courseId) {
        throw new Exception('Invalid course ID');
    }

    $query = "SELECT * FROM words 
              WHERE course_id = ? AND category = ? 
              ORDER BY RAND() 
              LIMIT 1";
              
    $stmt = $pdo->prepare($query);
    $stmt->execute([$courseId, $category]);
    $word = $stmt->fetch();

    if (!$word) {
        http_response_code(404);
        throw new Exception('No words found for this category');
    }

    echo json_encode([
        'success' => true,
        'word' => $word
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'details' => [
            'courseId' => $courseId ?? null,
            'category' => $category ?? null
        ]
    ]);
}
?> 