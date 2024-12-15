<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $languageName = $_POST['languageName'];
        $level = $_POST['level'];
        $description = $_POST['description'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO languages (languageName, level, description, active) VALUES (?, ?, ?, 1)");
            $stmt->execute([$languageName, $level, $description]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Failed to add language']);
        }
        break;

    case 'edit':
        $languageId = $_POST['languageId'];
        $languageName = $_POST['languageName'];
        $level = $_POST['level'];
        $description = $_POST['description'];
        
        try {
            $stmt = $pdo->prepare("UPDATE languages SET languageName = ?, level = ?, description = ? WHERE languageId = ?");
            $stmt->execute([$languageName, $level, $description, $languageId]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Failed to update language']);
        }
        break;

    case 'toggle':
        $languageId = $_POST['languageId'];
        $active = $_POST['active'];
        
        try {
            $stmt = $pdo->prepare("UPDATE languages SET active = ? WHERE languageId = ?");
            $stmt->execute([$active, $languageId]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Failed to toggle language status']);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
} 