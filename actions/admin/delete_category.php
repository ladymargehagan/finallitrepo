<?php
session_start();
require_once '../../config/db_connect.php';

// Check if user is admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get category ID from POST request
$categoryId = isset($_POST['categoryId']) ? (int)$_POST['categoryId'] : 0;

if (!$categoryId) {
    echo json_encode(['success' => false, 'message' => 'Invalid category ID']);
    exit();
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // First, delete all exercises in this category
    $stmt = $pdo->prepare("
        DELETE FROM exercise_sets 
        WHERE wordId IN (SELECT wordId FROM words WHERE categoryId = ?)
    ");
    $stmt->execute([$categoryId]);

    // Delete all words in this category
    $stmt = $pdo->prepare("DELETE FROM words WHERE categoryId = ?");
    $stmt->execute([$categoryId]);

    // Finally, delete the category
    $stmt = $pdo->prepare("DELETE FROM word_categories WHERE categoryId = ?");
    $stmt->execute([$categoryId]);

    // Commit transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
} catch (Exception $e) {
    // Rollback on error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error deleting category: ' . $e->getMessage()]);
} 