<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Fetch user's current courses with word count
$stmt = $pdo->prepare("
    SELECT l.*, ue.status, ue.enrollmentDate
    FROM user_enrollments ue 
    JOIN languages l ON ue.languageId = l.languageId 
    WHERE ue.userId = ? AND ue.status = 'active'
");
$stmt->execute([$userId]);
$currentCourses = $stmt->fetchAll();

// Get statistics per language
$statsQuery = $pdo->prepare("
    SELECT 
        ulp.language_name,
        ulp.completed_exercises as total_words,
        ulp.correct_answers as total_correct,
        ulp.completed_exercises as total_attempts,
        l.languageId,
        CASE 
            WHEN (ulp.correct_answers / ulp.completed_exercises) >= 0.9 THEN ulp.completed_exercises
            ELSE 0 
        END as mastered_count,
        CASE 
            WHEN (ulp.correct_answers / ulp.completed_exercises) >= 0.7 
            AND (ulp.correct_answers / ulp.completed_exercises) < 0.9 
            THEN ulp.completed_exercises
            ELSE 0
        END as familiar_count,
        CASE 
            WHEN (ulp.correct_answers / ulp.completed_exercises) < 0.7 
            THEN ulp.completed_exercises
            ELSE 0
        END as learning_count
    FROM user_language_progress ulp
    JOIN languages l ON l.languageName = ulp.language_name
    WHERE ulp.user_id = ?
    AND ulp.completed_exercises > 0
");
$statsQuery->execute([$userId]);
$languageStats = $statsQuery->fetchAll();

// Add overall statistics from user_statistics table
$overallStatsQuery = $pdo->prepare("
    SELECT 
        total_quizzes,
        total_questions,
        correct_answers,
        total_time,
        average_score
    FROM user_statistics 
    WHERE user_id = ?
");
$overallStatsQuery->execute([$userId]);
$userStats = $overallStatsQuery->fetch();

// Calculate overall totals
$overallStats = [
    'total_words' => $userStats['total_questions'] ?? 0,
    'total_correct' => $userStats['correct_answers'] ?? 0,
    'total_attempts' => $userStats['total_questions'] ?? 0,
    'total_quizzes' => $userStats['total_quizzes'] ?? 0,
    'average_score' => $userStats['average_score'] ?? 0,
    'total_time' => $userStats['total_time'] ?? 0,
    'mastered_count' => 0,
    'familiar_count' => 0,
    'learning_count' => 0
];

foreach ($languageStats as $stats) {
    $overallStats['total_words'] += $stats['total_words'];
    $overallStats['total_correct'] += $stats['total_correct'];
    $overallStats['total_attempts'] += $stats['total_attempts'];
    $overallStats['mastered_count'] += $stats['mastered_count'];
    $overallStats['familiar_count'] += $stats['familiar_count'];
    $overallStats['learning_count'] += $stats['learning_count'];
}

// Calculate percentages for progress bars
$totalWords = $overallStats['total_words'] ?: 1; // Prevent division by zero
$masteredPercent = round(($overallStats['mastered_count'] / $totalWords) * 100);
$familiarPercent = round(($overallStats['familiar_count'] / $totalWords) * 100);
$learningPercent = round(($overallStats['learning_count'] / $totalWords) * 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
    <!-- Header -->
    <header class="main-navigation">
        <div class="nav-logo">
            <img src="../assets/images/logo.svg" alt="Language Learning Platform Logo" class="logo-image">
        </div>
        <div class="auth-buttons">
            <a href="dashboard.php" class="btn-secondary">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="../actions/logout.php" class="btn-primary">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>

    <div class="profile-container">
        <h1>My Profile</h1>
        
        <div class="user-info">
            <div class="info-card">
                <h3>Personal Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['joinDate'])); ?></p>
                <p><strong>Account Type:</strong> <?php echo ucfirst($user['role']); ?></p>
            </div>

            <div class="stats-grid">
                <div class="overall-stats">
                    <h3>Overall Progress</h3>
                    <div class="stat-card">
                        <div class="stat-row">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $overallStats['total_quizzes']; ?></div>
                                <div class="stat-label">Quizzes Completed</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $overallStats['total_words']; ?></div>
                                <div class="stat-label">Total Questions Answered</div>
                            </div>
                        </div>
                        
                        <div class="accuracy-rate">
                            <div class="accuracy-bar">
                                <div class="accuracy-fill" style="width: <?php echo $overallStats['average_score']; ?>%"></div>
                            </div>
                            <div class="accuracy-text"><?php echo round($overallStats['average_score'], 1); ?>% Overall Accuracy</div>
                        </div>
                        
                        <div class="time-stat">
                            <?php 
                            $totalHours = floor($overallStats['total_time'] / 3600);
                            $totalMinutes = floor(($overallStats['total_time'] % 3600) / 60);
                            ?>
                            <div class="stat-value"><?php echo $totalHours; ?>h <?php echo $totalMinutes; ?>m</div>
                            <div class="stat-label">Total Time Learning</div>
                        </div>
                    </div>
                </div>

                <?php foreach ($languageStats as $stats): ?>
                    <div class="language-stats">
                        <h3><?php echo htmlspecialchars($stats['language_name']); ?></h3>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $stats['total_words']; ?></div>
                            <div class="stat-label">Questions Answered</div>
                            
                            <div class="accuracy-stats">
                                <div class="stat-value"><?php echo $stats['total_correct']; ?></div>
                                <div class="stat-label">Correct Answers</div>
                                <div class="accuracy-rate">
                                    <?php 
                                    $accuracy = $stats['total_attempts'] > 0 
                                        ? round(($stats['total_correct'] / $stats['total_attempts']) * 100) 
                                        : 0;
                                    ?>
                                    <div class="accuracy-bar">
                                        <div class="accuracy-fill" style="width: <?php echo $accuracy; ?>%"></div>
                                    </div>
                                    <div class="accuracy-text"><?php echo $accuracy; ?>% Accuracy</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Password Change Form -->
        <div class="password-section">
            <h3>Change Password</h3>
            <form class="password-form" action="../actions/update_password.php" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-update">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        // Client-side password validation
        document.querySelector('.password-form').addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('New passwords do not match!');
            }
        });
    </script>
</body>
</html> 