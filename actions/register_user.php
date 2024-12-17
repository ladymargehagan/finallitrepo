<?php
session_start();
$rootPath = dirname(dirname(__FILE__));

// First, set the headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting but don't display errors directly
error_reporting(E_ALL);
ini_set('display_errors', 0);

$response = ['success' => false, 'errors' => []];

try {
    require $rootPath . '/config/db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get and decode JSON data
        $jsonData = file_get_contents('php://input');
        if (!$jsonData) {
            throw new Exception('No data received');
        }

        $data = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data received');
        }

        // Sanitize inputs
        $firstName = trim(htmlspecialchars($data['firstName'] ?? '', ENT_QUOTES, 'UTF-8'));
        $lastName = trim(htmlspecialchars($data['lastName'] ?? '', ENT_QUOTES, 'UTF-8'));
        $email = trim(filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $password = $data['password'] ?? '';
        
        // Validation checks...
        if (empty($firstName)) $response['errors'][] = "First name is required";
        if (empty($lastName)) $response['errors'][] = "Last name is required";
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['errors'][] = "Invalid email format";
        }
        
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
                $result = $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);
                
                if ($result) {
                    $response['success'] = true;
                    $response['message'] = "Registration successful!";
                } else {
                    $response['errors'][] = "Database insertion failed";
                }
            } catch (PDOException $e) {
                $response['errors'][] = "Registration failed. Please try again.";
            }
        }
    } else {
        throw new Exception('Invalid request method');
    }

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $response['errors'][] = "Database connection error. Please try again later.";
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $response['errors'][] = $e->getMessage();
}

// Ensure clean output
ob_clean(); // Clear any output buffers
echo json_encode($response);
exit;

