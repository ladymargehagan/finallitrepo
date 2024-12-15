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
    $categoryDescription = isset($data['categoryDescription']) ? trim($data['categoryDescription']) : '';
    
    // Log the incoming data for debugging
    error_log("Category Name: " . $categoryName);
    error_log("Category Description: " . $categoryDescription);

    // Create a URL-friendly slug
    $categorySlug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $categoryName));

    // Check if category already exists
    $stmt = $pdo->prepare("SELECT categoryId FROM word_categories WHERE categoryName = ?");
    $stmt->execute([$categoryName]);
    if ($stmt->fetch()) {
        throw new Exception('Category already exists');
    }

    // Insert new category with description
    $stmt = $pdo->prepare("
        INSERT INTO word_categories (categoryName, categorySlug, description) 
        VALUES (?, ?, ?)
    ");
    
    $stmt->execute([$categoryName, $categorySlug, $categoryDescription]);
    $categoryId = $pdo->lastInsertId();

    // Verify the insertion
    $stmt = $pdo->prepare("SELECT * FROM word_categories WHERE categoryId = ?");
    $stmt->execute([$categoryId]);
    $newCategory = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'categoryId' => $categoryId,
        'categoryName' => $categoryName,
        'description' => $categoryDescription,
        'message' => 'Category added successfully!'
    ]);

} catch (Exception $e) {
    error_log("Error adding category: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 