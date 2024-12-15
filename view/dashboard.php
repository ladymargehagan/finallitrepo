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

// Fetch current courses (enrolled)
$stmt = $pdo->prepare("
    SELECT l.*, ue.status, ue.enrollmentDate 
    FROM languages l
    LEFT JOIN user_enrollments ue ON l.languageId = ue.languageId AND ue.userId = ?
    WHERE ue.status = 'active'
");
$stmt->execute([$_SESSION['user_id']]);
$currentCourses = $stmt->fetchAll();

// Fetch available courses (not enrolled)
$stmt = $pdo->prepare("
    SELECT * 
    FROM languages 
    WHERE languageId NOT IN (
        SELECT languageId 
        FROM user_enrollments 
        WHERE userId = ? AND status = 'active'
    )
    AND active = 1
");
$stmt->execute([$_SESSION['user_id']]);
$availableCourses = $stmt->fetchAll();
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
            <img src="../assets/images/logo.svg" alt="Language Learning Platform Logo" class="logo-image">
        </div>
        <div class="header-actions">
            <div class="auth-buttons">
                <a href="profile.php" class="btn-secondary">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="../actions/logout.php" class="btn-primary">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="dashboard-content">
        <!-- Welcome Card -->
        <section class="welcome-card">
            <h1>Welcome back, <?php echo htmlspecialchars($userName); ?>!</h1>
            <p>Ready to continue your language learning journey?</p>
        </section>

        <!-- Current Courses -->
        <section class="current-courses">
            <h2>My Courses</h2>
            <div class="courses-grid">
                <?php if (empty($currentCourses)): ?>
                    <p>You haven't enrolled in any courses yet.</p>
                <?php else: ?>
                    <?php foreach ($currentCourses as $course): ?>
                        <div class="course-card">
                            <h3><?php echo htmlspecialchars($course['languageName']); ?></h3>
                            <p>Level: <?php echo htmlspecialchars($course['level']); ?></p>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <a href="course_page.php?languageId=<?php echo $course['languageId']; ?>" class="btn btn-primary">
                                Continue Learning
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Available Courses -->
        <section class="available-courses">
            <h2>Available Courses</h2>
            <div class="courses-grid">
                <?php if (empty($availableCourses)): ?>
                    <p>No more courses available at the moment.</p>
                <?php else: ?>
                    <?php foreach ($availableCourses as $course): ?>
                        <div class="course-card">
                            <h3><?php echo htmlspecialchars($course['languageName']); ?></h3>
                            <p>Level: <?php echo htmlspecialchars($course['level']); ?></p>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <form action="../actions/enroll_course.php" method="POST" class="enrollment-form">
                                <input type="hidden" name="languageId" value="<?php echo $course['languageId']; ?>">
                                <button type="submit" class="btn btn-primary">
                                    Start Learning
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Add JavaScript for enrollment -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const enrollmentForms = document.querySelectorAll('.enrollment-form');
        
        enrollmentForms.forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form)
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Refresh the page to show updated course lists
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to enroll in course');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to process enrollment');
                }
            });
        });
    });
    </script>
</body>
</html> 