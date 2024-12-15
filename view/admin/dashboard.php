<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch enhanced stats
$stats = [
    'total_users' => $pdo->query("
        SELECT COUNT(DISTINCT u.id) 
        FROM users u 
        JOIN user_enrollments ue ON u.id = ue.userId 
        WHERE ue.status = 'active'
    ")->fetchColumn(),
    
    'total_exercises' => $pdo->query("
        SELECT COUNT(*) 
        FROM exercise_sets
    ")->fetchColumn(),
    
    'total_languages' => $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM languages WHERE active = 1) as active,
            (SELECT COUNT(*) FROM languages) as total
    ")->fetch(PDO::FETCH_ASSOC)
];

// Enhanced recent activity query
$recentActivity = $pdo->query("
    SELECT 
        u.firstName,
        u.lastName,
        l.languageName,
        wc.categoryName,
        ue.enrollmentDate as startTime
    FROM user_enrollments ue
    JOIN users u ON ue.userId = u.id
    JOIN languages l ON ue.languageId = l.languageId
    JOIN word_categories wc ON wc.categoryId = wc.categoryId
    WHERE ue.status = 'active'
    ORDER BY ue.enrollmentDate DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/admin/dashboard.css">
    <?php include 'includes/sidebar.php'; ?>
</head>
<body>
    <div class="admin-container">
        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Dashboard Overview</h1>
                <!-- <div class="header-actions">
                    <button class="btn-primary" onclick="window.location.href='exercises.php'">
                        <i class="fas fa-plus"></i> New Exercise
                    </button>
                    <button class="btn btn-secondary" onclick="window.location.href='languages.php'">
                        <i class="fas fa-language"></i> New Language
                    </button>
                </div> -->
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active Users</h3>
                        <p class="stat-value"><?php echo $stats['total_users']; ?></p>
                        <p class="stat-label">Last 30 days</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon exercises">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Exercises</h3>
                        <p class="stat-value"><?php echo $stats['total_exercises']; ?></p>
                        <p class="stat-label">Across all languages</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon languages">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Languages</h3>
                        <p class="stat-value"><?php echo $stats['total_languages']['active']; ?> / <?php echo $stats['total_languages']['total']; ?></p>
                        <p class="stat-label">Active / Total</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <section class="recent-activity">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    <?php if (empty($recentActivity)): ?>
                        <div class="no-activity">
                            <i class="fas fa-info-circle"></i>
                            <p>No recent activity to display</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentActivity as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-status">
                                    <i class="fas fa-book-reader"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-text">
                                        <span class="user-name">
                                            <?php echo htmlspecialchars($activity['firstName'] . ' ' . $activity['lastName']); ?>
                                        </span>
                                        studied 
                                        <span class="language-name">
                                            <?php echo htmlspecialchars($activity['languageName']); ?>
                                        </span>
                                        in category
                                        <span class="category-name">
                                            <?php echo htmlspecialchars($activity['categoryName']); ?>
                                        </span>
                                    </p>
                                    <span class="activity-time">
                                        <?php 
                                        $attemptDate = new DateTime($activity['startTime']);
                                        $now = new DateTime();
                                        $interval = $now->diff($attemptDate);
                                        
                                        if ($interval->days == 0) {
                                            if ($interval->h == 0) {
                                                echo $interval->i . ' minutes ago';
                                            } else {
                                                echo $interval->h . ' hours ago';
                                            }
                                        } else if ($interval->days == 1) {
                                            echo 'Yesterday';
                                        } else {
                                            echo $attemptDate->format('M j, Y g:i A');
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <!-- Add Language Modal
    <div id="newLanguageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Language</h3>
                <button class="close-modal" onclick="hideModal('newLanguageModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="newLanguageForm" class="modal-form">
                <div class="form-group">
                    <label for="languageName">Language Name</label>
                    <input type="text" id="languageName" name="languageName" required>
                </div>
                <div class="form-group">
                    <label for="languageCode">Language Code</label>
                    <input type="text" id="languageCode" name="languageCode" required 
                           pattern="[a-z]{2}" title="Two letter language code (e.g., en, es, fr)">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="hideModal('newLanguageModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Add Language</button>
                </div>
            </form>
        </div> -->
    </div>

</body>
</html>