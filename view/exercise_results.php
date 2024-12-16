<?php
session_start();
require_once '../config/db_connect.php';

// Redirect if no results exist
if (!isset($_SESSION['exercise_results'])) {
    header('Location: dashboard.php');
    exit();
}

$results = $_SESSION['exercise_results'];

// Prevent division by zero
$totalWords = max(1, $results['total_words']); // Ensure minimum of 1
$correctWords = isset($results['correct_words']) ? $results['correct_words'] : 0;
$score = ($correctWords / $totalWords) * 100;

// Calculate time taken
$timeTaken = isset($results['end_time']) ? 
    ($results['end_time'] - $results['start_time']) : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Results - <?php echo htmlspecialchars($results['language']); ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/results.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="results-container">
        <div class="results-header">
            <h1>Exercise Complete!</h1>
            <p class="category-info">
                <?php echo htmlspecialchars($results['language']); ?> - 
                <?php echo htmlspecialchars($results['category']); ?>
            </p>
        </div>

        <div class="score-card">
            <div class="score-circle <?php echo $score >= 70 ? 'good' : ($score >= 50 ? 'average' : 'needs-work'); ?>">
                <span class="score-number"><?php echo round($score); ?>%</span>
                <span class="score-label">Score</span>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fas fa-check-circle"></i>
                    <span class="stat-value"><?php echo $correctWords; ?>/<?php echo $totalWords; ?></span>
                    <span class="stat-label">Correct Answers</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span class="stat-value"><?php echo gmdate("i:s", $timeTaken); ?></span>
                    <span class="stat-label">Time Taken</span>
                </div>
            </div>
        </div>

        <div class="answers-review">
            <h2>Review Your Answers</h2>
            <?php foreach ($results['answers'] as $answer): ?>
                <div class="answer-item <?php echo $answer['correct'] ? 'correct' : 'incorrect'; ?>">
                    <div class="question">
                        <span class="original-word"><?php echo htmlspecialchars($answer['word']); ?></span>
                    </div>
                    <div class="user-answer">
                        <i class="fas <?php echo $answer['correct'] ? 'fa-check' : 'fa-times'; ?>"></i>
                        Your answer: <?php echo htmlspecialchars($answer['user_answer']); ?>
                    </div>
                    <?php if (!$answer['correct']): ?>
                        <div class="correct-answer">
                            Correct answer: <?php echo htmlspecialchars($answer['correct_answer']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="action-buttons">
            <a href="learn.php?course=<?php echo $_SESSION['last_course']; ?>&category=<?php echo $_SESSION['last_category']; ?>" 
               class="btn btn-primary">
                <i class="fas fa-redo"></i> Try Again
            </a>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <?php
    // Clear the results from session after displaying
    unset($_SESSION['exercise_results']);
    ?>
</body>
</html> 