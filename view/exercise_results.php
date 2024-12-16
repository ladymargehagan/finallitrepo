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
    
    // Start transaction
    $pdo->beginTransaction();
    
    // 1. Store quiz results
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
    
    // 2. Update user statistics
    $updateStmt = $pdo->prepare("
        UPDATE user_statistics 
        SET 
            total_quizzes = total_quizzes + 1,
            total_questions = total_questions + ?,
            correct_answers = correct_answers + ?,
            total_time = total_time + ?,
            average_score = (
                (average_score * total_quizzes + ?) / (total_quizzes + 1)
            )
        WHERE user_id = ?
    ");
    
    $updateStmt->execute([
        $totalQuestions,
        $correctAnswers,
        $quizDuration,
        $score,
        $_SESSION['user_id']
    ]);
    
    // If no row was updated, create new statistics record
    if ($updateStmt->rowCount() === 0) {
        $insertStmt = $pdo->prepare("
            INSERT INTO user_statistics 
            (user_id, total_quizzes, total_questions, correct_answers, 
             total_time, average_score)
            VALUES (?, 1, ?, ?, ?, ?)
        ");
        
        $insertStmt->execute([
            $_SESSION['user_id'],
            $totalQuestions,
            $correctAnswers,
            $quizDuration,
            $score
        ]);
    }
    
    // 3. Update language-specific progress
    $langProgressStmt = $pdo->prepare("
        UPDATE user_language_progress 
        SET 
            completed_exercises = completed_exercises + ?,
            correct_answers = correct_answers + ?,
            last_activity = NOW()
        WHERE user_id = ? AND language_name = ?
    ");
    
    $langProgressStmt->execute([
        $totalQuestions,
        $correctAnswers,
        $_SESSION['user_id'],
        $language
    ]);
    
    // If no row was updated, create new language progress record
    if ($langProgressStmt->rowCount() === 0) {
        $insertLangStmt = $pdo->prepare("
            INSERT INTO user_language_progress 
            (user_id, language_name, completed_exercises, correct_answers, last_activity)
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $insertLangStmt->execute([
            $_SESSION['user_id'],
            $language,
            $totalQuestions,
            $correctAnswers
        ]);
    }
    
    // Commit transaction
    $pdo->commit();
    
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    error_log("Error updating user statistics: " . $e->getMessage());
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="results-container">
        <div class="results-header">
            <h1>Quiz Complete!</h1>
            <p class="category-info">
                <i class="fas fa-language"></i> <?php echo htmlspecialchars($language); ?> - 
                <i class="fas fa-folder"></i> <?php echo htmlspecialchars($category); ?>
            </p>
        </div>

        <div class="score-card">
            <div class="score-circle <?php echo $score >= 80 ? 'excellent' : ($score >= 60 ? 'good' : 'needs-work'); ?>">
                <span class="score-number"><?php echo round($score); ?>%</span>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fas fa-check-circle"></i>
                    <span class="stat-value"><?php echo $correctAnswers; ?> / <?php echo $totalQuestions; ?></span>
                    <span class="stat-label">Correct Answers</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span class="stat-value"><?php echo $minutes; ?>m <?php echo $seconds; ?>s</span>
                    <span class="stat-label">Time Taken</span>
                </div>
            </div>
        </div>

        <div class="answers-review">
            <h2><i class="fas fa-list-check"></i> Answer Summary</h2>
            <?php foreach ($_SESSION['exercise_answers'] as $exerciseId => $answer): ?>
                <div class="answer-item <?php echo $answer['correct'] ? 'correct-answer' : 'wrong-answer'; ?>">
                    <div class="question-word">
                        <?php echo htmlspecialchars($answer['word']); ?>
                    </div>
                    <div class="answer-details">
                        <?php if ($answer['correct']): ?>
                            <div class="user-correct-answer">
                                <i class="fas fa-check"></i> Your answer: <?php echo htmlspecialchars($answer['user_answer']); ?>
                            </div>
                        <?php else: ?>
                            <div class="user-wrong-answer">
                                <i class="fas fa-times"></i> Your answer: <?php echo htmlspecialchars($answer['user_answer']); ?>
                            </div>
                            <div class="correct-solution">
                                <i class="fas fa-check"></i> Correct answer: <?php echo htmlspecialchars($answer['correct_answer']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="action-buttons">
            <a href="dashboard.php" class="btn">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
            <a href="learn.php?course=<?php echo $_SESSION['last_course_id']; ?>&category=<?php echo $_SESSION['last_category_slug']; ?>" 
               class="btn btn-primary">
                <i class="fas fa-redo"></i> Try Again
            </a>
        </div>
    </div>
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