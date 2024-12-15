<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['current_exercise_session'])) {
    header('Location: dashboard.php');
    exit();
}

$sessionId = $_SESSION['current_exercise_session'];

// Get session statistics
$stmt = $pdo->prepare("
    SELECT 
        es.*,
        TIMESTAMPDIFF(MINUTE, es.startTime, COALESCE(es.endTime, NOW())) as duration,
        (SELECT COUNT(*) FROM session_word_progress WHERE sessionId = es.sessionId AND mastered = TRUE) as mastered_count,
        (SELECT COUNT(*) FROM session_word_progress WHERE sessionId = es.sessionId AND correctAttempts >= 1 AND mastered = FALSE) as familiar_count,
        (SELECT COUNT(*) FROM session_word_progress WHERE sessionId = es.sessionId AND correctAttempts = 0) as learning_count
    FROM exercise_sessions es
    WHERE es.sessionId = ?
");
$stmt->execute([$sessionId]);
$stats = $stmt->fetch();

// Calculate total words and percentages
$totalWords = $stats['mastered_count'] + $stats['familiar_count'] + $stats['learning_count'];
$masteredPercent = round(($stats['mastered_count'] / $totalWords) * 100);
$familiarPercent = round(($stats['familiar_count'] / $totalWords) * 100);
$learningPercent = round(($stats['learning_count'] / $totalWords) * 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Exercise Complete!</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/stats.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="stats-container">
        <div class="completion-header">
            <div class="completion-badge">
                <i class="fas fa-trophy"></i>
            </div>
            <h1>Exercise Complete!</h1>
            <div class="time-spent">
                <i class="fas fa-clock"></i>
                <span><?php echo $stats['duration']; ?> minutes</span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stats-card progress-chart">
                <canvas id="proficiencyChart"></canvas>
            </div>

            <div class="stats-card metrics">
                <div class="metric mastered">
                    <div class="metric-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="metric-details">
                        <span class="metric-value"><?php echo $stats['mastered_count']; ?></span>
                        <span class="metric-label">Mastered</span>
                    </div>
                </div>
                <div class="metric familiar">
                    <div class="metric-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="metric-details">
                        <span class="metric-value"><?php echo $stats['familiar_count']; ?></span>
                        <span class="metric-label">Familiar</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
            <a href="learn.php" class="btn btn-primary">
                <i class="fas fa-redo"></i> New Exercise Set
            </a>
        </div>
    </div>

    <script>
        // Initialize and animate the chart
        const ctx = document.getElementById('proficiencyChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Mastered', 'Familiar', 'Learning'],
                datasets: [{
                    data: [
                        <?php echo $masteredPercent; ?>,
                        <?php echo $familiarPercent; ?>,
                        <?php echo $learningPercent; ?>
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>
</body>
</html> 