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

// Fetch user's current courses
$stmt = $pdo->prepare("
    SELECT c.*, uc.progress 
    FROM user_courses uc 
    JOIN courses c ON uc.course_id = c.id 
    WHERE uc.user_id = ?
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
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="profile-container">
        <h1>My Profile</h1>
        
        <div class="profile-info">
            <div class="avatar">
                <img src="<?php echo htmlspecialchars($user['avatar_url'] ?? '../assets/images/default-avatar.png'); ?>" alt="Profile Picture">
            </div>
            
            <div class="user-details">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Member since: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
            </div>
            
            <div class="stats">
                <h3>Learning Statistics</h3>
                <div class="stat-grid">
                    <div class="stat-item">
                        <span class="stat-value"><?php echo count($currentCourses); ?></span>
                        <span class="stat-label">Active Courses</span>
                    </div>
                    <!-- Add more stats as needed -->
                </div>
            </div>
        </div>

        <div class="current-courses">
            <h3>My Courses</h3>
            <div class="course-grid">
                <?php foreach ($currentCourses as $course): ?>
                    <div class="course-card">
                        <h4><?php echo htmlspecialchars($course['name']); ?></h4>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo $course['progress']; ?>%"></div>
                        </div>
                        <p><?php echo $course['progress']; ?>% Complete</p>
                        <a href="learn.php?course=<?php echo $course['id']; ?>" class="btn btn-primary">Continue Learning</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html> 