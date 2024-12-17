<?php
session_start();
$rootPath = dirname(dirname(__FILE__));
require $rootPath . '/config/db_connect.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Add these debug lines temporarily
error_log("Root path: " . $rootPath);
error_log("Full database path: " . $rootPath . '/config/db_connect.php');

$response = ['success' => false, 'errors' => []];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Sanitize inputs
    $firstName = trim(filter_var($data['firstName'] ?? '', FILTER_SANITIZE_STRING));
    $lastName = trim(filter_var($data['lastName'] ?? '', FILTER_SANITIZE_STRING));
    $email = trim(filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $password = $data['password'] ?? '';
    
    // Validation
    if (empty($firstName)) $response['errors'][] = "First name is required";
    if (empty($lastName)) $response['errors'][] = "Last name is required";
    
    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors'][] = "Invalid email format";
    }
    
    // Password validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $response['errors'][] = "Password must be at least 8 characters and contain at least one uppercase letter, one number, and one special character";
    }
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $response['errors'][] = "Email already registered";
    }
    
    if (empty($response['errors'])) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);
            
            $response['success'] = true;
            $response['message'] = "Registration successful!";
        } catch (PDOException $e) {
            $response['errors'][] = "Registration failed. Please try again.";
        }
    }
}

echo json_encode($response);
