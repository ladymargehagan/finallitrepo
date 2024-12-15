<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$categoryName = $data['categoryName'] ?? '';

if (empty($categoryName)) {
    echo json_encode(['success' => false, 'error' => 'Category name is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO word_categories (categoryName) VALUES (?)");
    $stmt->execute([$categoryName]);
    
    echo json_encode([
        'success' => true,
        'categoryId' => $pdo->lastInsertId(),
        'message' => 'Category added successfully'
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
} 