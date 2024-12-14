<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $languageName = $_POST['languageName'];
    $languageCode = strtolower($_POST['languageCode']);
    
    // Validate language code format
    if (!preg_match('/^[a-z]{2}$/', $languageCode)) {
        throw new Exception('Invalid language code format');
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO languages (
            languageName, 
            languageCode,
            active
        ) VALUES (?, ?, 1)
    ");
    
    $stmt->execute([$languageName, $languageCode]);
    
    echo json_encode(['success' => true, 'languageId' => $pdo->lastInsertId()]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 