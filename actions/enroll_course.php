<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

try {
    require_once '../config/db_connect.php';

    // Get the language ID from POST request
    $languageId = filter_input(INPUT_POST, 'languageId', FILTER_VALIDATE_INT);
    
    // Validate language ID
    if (!$languageId) {
        throw new Exception('Invalid language ID');
    }

    // Check if this is the French course (assuming French has languageId = 1)
    $stmt = $pdo->prepare("SELECT languageName FROM languages WHERE languageId = ?");
    $stmt->execute([$languageId]);
    $language = $stmt->fetch();

    if (!$language || $language['languageName'] !== 'French') {
        echo json_encode([
            'success' => false,
            'message' => 'Only French course is currently available for enrollment'
        ]);
        exit();
    }

    // Check if already enrolled
    $stmt = $pdo->prepare("
        SELECT status 
        FROM user_enrollments 
        WHERE userId = ? AND languageId = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $languageId]);
    $enrollment = $stmt->fetch();

    if ($enrollment) {
        if ($enrollment['status'] === 'active') {
            echo json_encode([
                'success' => false,
                'message' => 'You are already enrolled in this course'
            ]);
            exit();
        } else {
            // Reactivate dropped enrollment
            $stmt = $pdo->prepare("
                UPDATE user_enrollments 
                SET status = 'active' 
                WHERE userId = ? AND languageId = ?
            ");
        }
    } else {
        // Create new enrollment
        $stmt = $pdo->prepare("
            INSERT INTO user_enrollments (userId, languageId, status) 
            VALUES (?, ?, 'active')
        ");
    }

    $stmt->execute([$_SESSION['user_id'], $languageId]);

    echo json_encode([
        'success' => true,
        'message' => 'Successfully enrolled in French course',
        'redirect' => '../view/french_course.php?course=' . $languageId
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 