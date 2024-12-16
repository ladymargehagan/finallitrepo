<?php
session_start();

// Ensure we have quiz data
if (!isset($_SESSION['attempted_exercises']) || empty($_SESSION['attempted_exercises'])) {
    header('Location: dashboard.php');
    exit();
}

// Calculate final statistics
$totalQuestions = count($_SESSION['attempted_exercises']);
$correctAnswers = array_sum($_SESSION['attempted_exercises']);
$score = ($correctAnswers / $totalQuestions) * 100;

// Get quiz duration
$quizDuration = time() - $_SESSION['exercise_start_time'];
$minutes = floor($quizDuration / 60);
$seconds = $quizDuration % 60;

// Get quiz info
$language = $_SESSION['current_quiz_info']['language'] ?? 'Unknown Language';
$category = $_SESSION['current_quiz_info']['category'] ?? 'Unknown Category';

// Store the results in the database
try {
    require_once '../config/db_connect.php';
    
    $stmt = $pdo->prepare("
        INSERT INTO quiz_results 
        (user_id, language_name, category_name, total_questions, correct_answers, 
         completion_time, score, completed_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $language,
        $category,
        $totalQuestions,
        $correctAnswers,
        $quizDuration,
        $score
    ]);
    
} catch (PDOException $e) {
    error_log("Error storing quiz results: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/results.css">
</head>
<body>
    <div class="results-container">
        <h1>Quiz Complete!</h1>
        
        <div class="quiz-info">
            <p>Language: <?php echo htmlspecialchars($language); ?></p>
            <p>Category: <?php echo htmlspecialchars($category); ?></p>
        </div>

        <div class="score-display">
            <div class="score-circle">
                <span class="score-number"><?php echo round($score); ?>%</span>
            </div>
        </div>

        <div class="statistics">
            <div class="stat-item">
                <span class="stat-label">Correct Answers:</span>
                <span class="stat-value"><?php echo $correctAnswers; ?> / <?php echo $totalQuestions; ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Time Taken:</span>
                <span class="stat-value"><?php echo $minutes; ?>m <?php echo $seconds; ?>s</span>
            </div>
        </div>

        <div class="action-buttons">
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
            <a href="learn.php?course=<?php echo $_SESSION['last_course_id']; ?>&category=<?php echo $_SESSION['last_category_slug']; ?>" 
               class="btn btn-primary">Try Again</a>
        </div>
    </div>

    <script>
        // Add any animations or interactivity here
    </script>
</body>
</html>

<?php
// Clean up session variables after displaying results
unset($_SESSION['attempted_exercises']);
unset($_SESSION['exercise_answers']);
unset($_SESSION['exercise_start_time']);
unset($_SESSION['current_quiz_info']);
unset($_SESSION['correct_answers']);
unset($_SESSION['total_attempts']);
// Keep last_course_id and last_category_slug for the "Try Again" button
?> 