<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;

try {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM exercise_sets es 
        JOIN words w ON es.wordId = w.wordId 
        WHERE w.categoryId = ?
    ");
    $stmt->execute([$categoryId]);
    $result = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'exerciseCount' => $result['count']
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 