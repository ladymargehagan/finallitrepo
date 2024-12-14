<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $categoryName = $_POST['categoryName'];
    $description = $_POST['categoryDescription'];
    $categorySlug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $categoryName));
    
    $stmt = $pdo->prepare("
        INSERT INTO word_categories (
            categoryName, 
            categorySlug, 
            description
        ) VALUES (?, ?, ?)
    ");
    
    $stmt->execute([$categoryName, $categorySlug, $description]);
    
    echo json_encode(['success' => true, 'categoryId' => $pdo->lastInsertId()]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 