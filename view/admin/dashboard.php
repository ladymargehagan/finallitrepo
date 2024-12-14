<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch basic stats
$stats = [
    'total_users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'total_exercises' => $pdo->query("SELECT COUNT(*) FROM exercise_templates")->fetchColumn(),
    'active_languages' => $pdo->query("SELECT COUNT(*) FROM languages WHERE active = 1")->fetchColumn()
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin/styles.css">
</head>
<body class="admin-dashboard">
    <nav class="admin-nav">
        <div class="nav-brand">
            <i class="fas fa-language"></i>
            <span>Admin Panel</span>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="exercises.php"><i class="fas fa-tasks"></i> Exercises</a></li>
            <li><a href="languages.php"><i class="fas fa-globe"></i> Languages</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="../../actions/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="admin-main">
        <h1>Dashboard Overview</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>Total Users</h3>
                <p><?php echo $stats['total_users']; ?></p>
            </div>
            <div class="stat-card">
                <i class="fas fa-tasks"></i>
                <h3>Total Exercises</h3>
                <p><?php echo $stats['total_exercises']; ?></p>
            </div>
            <div class="stat-card">
                <i class="fas fa-globe"></i>
                <h3>Active Languages</h3>
                <p><?php echo $stats['active_languages']; ?></p>
            </div>
        </div>

        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="exercises.php?action=new" class="btn-primary">
                    <i class="fas fa-plus"></i> Create New Exercise
                </a>
                <button class="btn-secondary" onclick="showModal('newLanguageModal')">
                    <i class="fas fa-language"></i> New Language
                </button>
            </div>
        </div>

        <!-- Add Language Modal -->
        <div id="newLanguageModal" class="modal">
            <div class="modal-content">
                <h3>Add New Language</h3>
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
            </div>
        </div>

        <!-- Add the JavaScript file -->
        <script src="../../assets/js/admin/languages.js"></script>
    </main>
</body>
</html>
