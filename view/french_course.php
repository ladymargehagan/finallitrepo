<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get course ID from URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch course details
try {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE courseId = ? AND language = 'French'");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch();

    if (!$course) {
        header('Location: dashboard.php');
        exit();
    }

    // Fetch user's progress for this course
    $stmt = $pdo->prepare("
        SELECT * FROM user_progress 
        WHERE userId = ? AND courseId = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $courseId]);
    $progress = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Something went wrong');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn French - Language Learning Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/course.css">
</head>
<body>
    <!-- Header -->
    <header class="course-header">
        <div class="nav-logo">
            <img src="../assets/images/logo.png" alt="Logo" class="logo-image">
        </div>
        <div class="course-progress">
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo ($progress['wordsLearned'] ?? 0) ?>%"></div>
            </div>
            <span class="progress-text"><?php echo $progress['wordsLearned'] ?? 0 ?> words learned</span>
        </div>
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </header>

    <main class="course-content">
        <section class="course-intro">
            <h1>Learn French</h1>
            <p>Select a category to start learning vocabulary</p>
        </section>

        <div class="category-grid">
            <!-- Basic Phrases -->
            <div class="category-card" onclick="window.location.href='learn.php?course=<?php echo $courseId; ?>&category=basic-phrases'">
                <div class="category-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="category-info">
                    <h3>Basic Phrases</h3>
                    <p>Essential French expressions</p>
                </div>
            </div>

            <!-- People -->
            <div class="category-card" onclick="window.location.href='learn.php?course=<?php echo $courseId; ?>&category=people'">
                <div class="category-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="category-info">
                    <h3>People & Family</h3>
                    <p>Learn about people and relationships</p>
                </div>
            </div>

            <!-- Animals -->
            <div class="category-card" onclick="window.location.href='learn.php?course=<?php echo $courseId; ?>&category=animals'">
                <div class="category-icon">
                    <i class="fas fa-paw"></i>
                </div>
                <div class="category-info">
                    <h3>Animals</h3>
                    <p>Common animal names</p>
                </div>
            </div>

            <!-- Food & Drinks -->
            <div class="category-card" onclick="window.location.href='learn.php?course=<?php echo $courseId; ?>&category=food'">
                <div class="category-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="category-info">
                    <h3>Food & Drinks</h3>
                    <p>Culinary vocabulary</p>
                </div>
            </div>

            <!-- Places -->
            <div class="category-card" onclick="window.location.href='learn.php?course=<?php echo $courseId; ?>&category=places'">
                <div class="category-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="category-info">
                    <h3>Places</h3>
                    <p>Locations and directions</p>
                </div>
            </div>

            <!-- Numbers -->
            <div class="category-card" onclick="window.location.href='learn.php?course=<?php echo $courseId; ?>&category=numbers'">
                <div class="category-icon">
                    <i class="fas fa-sort-numeric-up"></i>
                </div>
                <div class="category-info">
                    <h3>Numbers</h3>
                    <p>Counting in French</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html> 