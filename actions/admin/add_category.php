<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['categoryName']) || empty(trim($data['categoryName']))) {
        throw new Exception('Category name is required');
    }

    $categoryName = trim($data['categoryName']);
    $categoryDescription = isset($data['categoryDescription']) ? trim($data['categoryDescription']) : null;
    
    // Create a URL-friendly slug
    $categorySlug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $categoryName));

    // Check if category already exists
    $stmt = $pdo->prepare("SELECT categoryId FROM word_categories WHERE categoryName = ?");
    $stmt->execute([$categoryName]);
    if ($stmt->fetch()) {
        throw new Exception('Category already exists');
    }

    // Insert new category
    $stmt = $pdo->prepare("
        INSERT INTO word_categories (categoryName, categorySlug, description) 
        VALUES (?, ?, ?)
    ");
    
    $stmt->execute([$categoryName, $categorySlug, $categoryDescription]);
    $categoryId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'categoryId' => $categoryId,
        'categoryName' => $categoryName,
        'message' => 'Category added successfully!'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 