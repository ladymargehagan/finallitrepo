<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    exit('Unauthorized access');
}

try {
    $languageName = $_POST['languageName'];
    $languageCode = strtolower($_POST['languageCode']);

    // Check if language already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM languages WHERE languageCode = ?");
    $stmt->execute([$languageCode]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Language code already exists']);
        exit;
    }

    // Add new language
    $stmt = $pdo->prepare("INSERT INTO languages (languageName, languageCode, active) VALUES (?, ?, 1)");
    $stmt->execute([$languageName, $languageCode]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding language']);
} 