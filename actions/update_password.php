<?php
session_start();
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$response = ['success' => false, 'error' => null];

if (!isset($_SESSION['user_id'])) {
    $response['error'] = 'Not authenticated';
    echo json_encode($response);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (empty($data['current_password']) || empty($data['new_password']) || empty($data['confirm_password'])) {
        throw new Exception('All fields are required');
    }

    if ($data['new_password'] !== $data['confirm_password']) {
        throw new Exception('New passwords do not match');
    }

    // Password regex validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $data['new_password'])) {
        throw new Exception('Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character');
    }

    // Get current user's password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($data['current_password'], $user['password'])) {
        throw new Exception('Current password is incorrect');
    }

    // Update password
    $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $_SESSION['user_id']]);

    $response['success'] = true;
    $response['message'] = 'Password updated successfully';

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response); 