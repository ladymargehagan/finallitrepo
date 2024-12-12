<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user's first name from session
$userName = $_SESSION['user_name'] ?? 'User';

// Fetch user's enrolled courses
try {
    $stmt = $pdo->prepare("
        SELECT c.*, ue.status, up.wordsLearned, up.totalScore
        FROM courses c
        LEFT JOIN user_enrollments ue ON c.courseId = ue.courseId AND ue.userId = ?
        LEFT JOIN user_progress up ON c.courseId = up.courseId AND up.userId = ?
        WHERE ue.status = 'active' OR ue.status IS NULL
    ");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching courses: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Language Learning Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="nav-logo">
            <img src="../assets/images/logo.png" alt="Logo" class="logo-image">
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary">
                <i class="fas fa-user"></i>
                Profile
            </button>
            <a href="../actions/logout.php" class="btn btn-primary">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </header>

    <main class="dashboard-content">
        <!-- Welcome Card -->
        <section class="welcome-card">
            <h1>Welcome back, <?php echo htmlspecialchars($userName); ?>!</h1>
            <p>Ready to continue your language learning journey?</p>
        </section>

        <!-- Current Course -->
        <section class="current-course">
            <h2>Current Course</h2>
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <?php if ($course['status'] === 'active'): ?>
                        <div class="course-card active">
                            <img src="../assets/images/flags/<?php echo strtolower($course['language']); ?>.png" 
                                 alt="<?php echo htmlspecialchars($course['language']); ?> Flag" 
                                 class="course-flag">
                            <div class="course-info">
                                <h3><?php echo htmlspecialchars($course['language']); ?></h3>
                                <div class="progress-bar">
                                    <?php 
                                        $progress = isset($course['wordsLearned']) ? min(($course['wordsLearned'] / 100) * 100, 100) : 0;
                                    ?>
                                    <div class="progress" style="width: <?php echo $progress; ?>%"></div>
                                </div>
                                <p class="progress-text"><?php echo round($progress); ?>% Complete</p>
                                <a href="learn.php?course=<?php echo $course['courseId']; ?>" class="btn btn-primary">
                                    Continue Learning
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- Available Courses -->
        <section class="available-courses">
            <h2>More Courses</h2>
            <div class="course-grid">
                <div class="course-card">
                    <img src="../assets/images/flags/french.png" alt="French Flag" class="course-flag">
                    <div class="course-info">
                        <h3>French</h3>
                        <a href="course.php?id=1" class="btn btn-secondary">Start Learning</a>
                    </div>
                </div>
                <?php foreach ($courses as $course): ?>
                    <?php if ($course['status'] !== 'active'): ?>
                        <div class="course-card">
                            <img src="../assets/images/flags/<?php echo strtolower($course['language']); ?>.png" 
                                 alt="<?php echo htmlspecialchars($course['language']); ?> Flag" 
                                 class="course-flag">
                            <div class="course-info">
                                <h3><?php echo htmlspecialchars($course['language']); ?></h3>
                                <a href="enroll.php?course=<?php echo $course['courseId']; ?>" class="btn btn-secondary">
                                    Start Learning
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html> 