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
    SELECT l.*, ue.status, ue.enrollmentDate,
    (SELECT COUNT(*) FROM learned_words lw 
     JOIN words w ON lw.wordId = w.wordId 
     WHERE lw.userId = ue.userId AND w.languageId = l.languageId) as wordsLearned
    FROM user_enrollments ue 
    JOIN languages l ON ue.languageId = l.languageId 
    WHERE ue.userId = ? AND ue.status = 'active'
");
$stmt->execute([$userId]);
$currentCourses = $stmt->fetchAll();
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
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo count($currentCourses); ?></div>
                <div class="stat-label">Active Courses</div>
            </div>
            <div class="stat-card">
                <?php
                $totalWords = 0;
                foreach ($currentCourses as $course) {
                    $totalWords += $course['wordsLearned'];
                }
                ?>
                <div class="stat-value"><?php echo $totalWords; ?></div>
                <div class="stat-label">Words Learned</div>
            </div>
        </div>

        <div class="course-grid">
            <?php foreach ($currentCourses as $course): ?>
                <div class="course-card">
                    <h3><?php echo htmlspecialchars($course['languageName']); ?></h3>
                    <div class="progress-bar">
                        <?php 
                        $progressPercent = min(100, ($course['wordsLearned'] / 20) * 100);
                        ?>
                        <div class="progress" style="width: <?php echo $progressPercent; ?>%"></div>
                    </div>
                    <p><?php echo round($progressPercent); ?>% Complete</p>
                    <a href="course_page.php?id=<?php echo $course['languageId']; ?>&language=<?php echo urlencode($course['languageName']); ?>" 
                       class="btn-update">Continue Learning</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="password-section">
            <h2>Change Password</h2>
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