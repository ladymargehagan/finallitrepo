<?php
session_start();
require_once '../config/db_connect.php';
header('Content-Type: application/json');

$response = ['success' => false, 'errors' => []];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $email = trim(filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $password = $data['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $response['errors'][] = "Both email and password are required";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['firstName'];
                $_SESSION['user_role'] = $user['role'];
                
                $response['success'] = true;
                $response['redirect'] = '../view/dashboard.php';
            } else {
                $response['errors'][] = "Invalid email or password";
            }
        } catch (PDOException $e) {
            $response['errors'][] = "Login failed. Please try again.";
        }
    }
}

echo json_encode($response);