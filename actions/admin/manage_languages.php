<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            $languageName = trim($_POST['languageName']);
            $level = $_POST['level'];
            $description = trim($_POST['description']);
            
            // Validate inputs
            if (empty($languageName) || empty($level)) {
                throw new Exception('Language name and level are required');
            }

            $stmt = $pdo->prepare("INSERT INTO languages (languageName, level, description, active) VALUES (?, ?, ?, 1)");
            $success = $stmt->execute([$languageName, $level, $description]);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'id' => $pdo->lastInsertId(),
                    'message' => 'Language added successfully'
                ]);
            } else {
                throw new Exception('Failed to add language');
            }
            break;

        case 'edit':
            $languageId = $_POST['languageId'];
            $languageName = trim($_POST['languageName']);
            $level = $_POST['level'];
            $description = trim($_POST['description']);
            
            // Validate inputs
            if (empty($languageId) || empty($languageName) || empty($level)) {
                throw new Exception('Invalid input data');
            }

            $stmt = $pdo->prepare("UPDATE languages SET languageName = ?, level = ?, description = ? WHERE languageId = ?");
            $success = $stmt->execute([$languageName, $level, $description, $languageId]);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Language updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update language');
            }
            break;

        case 'toggle':
            $languageId = $_POST['languageId'];
            $active = (int)$_POST['active'];
            
            if (empty($languageId)) {
                throw new Exception('Language ID is required');
            }

            $stmt = $pdo->prepare("UPDATE languages SET active = ? WHERE languageId = ?");
            $success = $stmt->execute([$active, $languageId]);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Language status updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update language status');
            }
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
} 