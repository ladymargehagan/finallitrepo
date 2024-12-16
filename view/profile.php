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

// Get overall statistics
$statsQuery = $pdo->prepare("
    SELECT 
        COUNT(*) as total_words,
        SUM(correct_attempts) as total_correct,
        SUM(total_attempts) as total_attempts,
        SUM(CASE WHEN proficiency = 'mastered' THEN 1 ELSE 0 END) as mastered_count,
        SUM(CASE WHEN proficiency = 'familiar' THEN 1 ELSE 0 END) as familiar_count,
        SUM(CASE WHEN proficiency = 'learning' THEN 1 ELSE 0 END) as learning_count
    FROM learned_words 
    WHERE userId = ?
");
$statsQuery->execute([$userId]);
$stats = $statsQuery->fetch();

// Calculate percentages for progress bars
$totalWords = $stats['total_words'] ?: 1; // Prevent division by zero
$masteredPercent = round(($stats['mastered_count'] / $totalWords) * 100);
$familiarPercent = round(($stats['familiar_count'] / $totalWords) * 100);
$learningPercent = round(($stats['learning_count'] / $totalWords) * 100);
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
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['total_words']; ?></div>
                    <div class="stat-label">Words Learned</div>
                    <div class="proficiency-breakdown">
                        <div class="progress-bar">
                            <div class="progress-segment mastered" style="width: <?php echo $masteredPercent; ?>%"></div>
                            <div class="progress-segment familiar" style="width: <?php echo $familiarPercent; ?>%"></div>
                            <div class="progress-segment learning" style="width: <?php echo $learningPercent; ?>%"></div>
                        </div>
                        <div class="proficiency-legend">
                            <span class="mastered"><?php echo $stats['mastered_count']; ?> Mastered</span>
                            <span class="familiar"><?php echo $stats['familiar_count']; ?> Familiar</span>
                            <span class="learning"><?php echo $stats['learning_count']; ?> Learning</span>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
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
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['total_attempts']; ?></div>
                    <div class="stat-label">Total Attempts</div>
                </div>
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