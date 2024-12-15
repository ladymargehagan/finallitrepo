<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['exercise_results'])) {
    header('Location: dashboard.php');
    exit();
}

$results = $_SESSION['exercise_results'];
$totalQuestions = count($results);
$correctAnswers = array_sum(array_column($results, 'correct'));
$score = ($correctAnswers / $totalQuestions) * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exercise Results</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="results-container">
        <h1>Exercise Results</h1>
        <div class="score-summary">
            <h2>Score: <?php echo round($score); ?>%</h2>
            <p><?php echo $correctAnswers; ?> out of <?php echo $totalQuestions; ?> correct</p>
        </div>

        <div class="results-list">
            <?php foreach ($results as $result): ?>
                <div class="result-item <?php echo $result['correct'] ? 'correct' : 'incorrect'; ?>">
                    <div class="word"><?php echo htmlspecialchars($result['word']); ?></div>
                    <div class="answer">
                        Your answer: <?php echo htmlspecialchars($result['user_answer']); ?>
                    </div>
                    <?php if (!$result['correct']): ?>
                        <div class="correct-answer">
                            Correct answer: <?php echo htmlspecialchars($result['correct_answer']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="action-buttons">
            <a href="learn.php?course=<?php echo $_SESSION['last_course']; ?>&category=<?php echo $_SESSION['last_category']; ?>" class="btn btn-primary">Try Again</a>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html> 