<?php
session_start();
require_once '../config/db_connect.php';
require_once '../includes/quiz-functions.php';

// Initialize error message variable
$errorMessage = null;

// Initialize exercise start time if not set
if (!isset($_SESSION['exercise_start_time'])) {
    $_SESSION['exercise_start_time'] = time();
}

// Initialize completed exercises array in session if not exists
if (!isset($_SESSION['completed_exercises'])) {
    $_SESSION['completed_exercises'] = [];
}

/**
 * Checks if a quiz session is complete and handles completion logic
 * 
 * @param array|false $exercise Current exercise data
 * @param PDO $pdo Database connection
 * @param int $totalExercises Total number of exercises in quiz
 * @param int $userId User ID
 * @param int $startTime Quiz start time
 * @return bool True if quiz is complete, false otherwise
 */
function handleQuizCompletion($exercise, $pdo, $totalExercises, $userId) {
    if (!$exercise) {
        $endTime = time();
        
        // Update exercise results for display
        $_SESSION['exercise_results'] = [
            'total_words' => $totalExercises,
            'correct_words' => count($_SESSION['completed_exercises']),
            'start_time' => $_SESSION['exercise_start_time'],
            'end_time' => $endTime,
            'language' => $exercise['languageName'] ?? '',
            'category' => $exercise['categoryName'] ?? '',
            'answers' => array_values($_SESSION['exercise_answers'] ?? []) // Convert to indexed array
        ];
        
        // Store last course and category
        $_SESSION['last_course'] = $_GET['course'] ?? null;
        $_SESSION['last_category'] = $_GET['category'] ?? null;
        
        // Clear quiz-specific session data
        $_SESSION['completed_exercises'] = [];
        $_SESSION['exercise_answers'] = [];
        $_SESSION['exercise_start_time'] = null;
        $_SESSION['current_category'] = null;
        
        // Redirect to results page
        header('Location: exercise_results.php');
        exit();
    }
    
    return false;
}
