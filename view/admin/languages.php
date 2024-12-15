<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch existing languages
$languages = $pdo->query("SELECT * FROM languages ORDER BY languageName")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Management - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/admin/languages.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-language"></i>
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="exercises.php">
                    <i class="fas fa-dumbbell"></i>
                    <span>Exercises</span>
                </a>
                <a href="languages.php" class="active">
                    <i class="fas fa-globe"></i>
                    <span>Languages</span>
                </a>
                <a href="users.php">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1>Language Management</h1>
                <button class="btn btn-primary" onclick="showModal('newLanguageModal')">
                    <i class="fas fa-plus"></i> Add New Language
                </button>
            </div>

            <div class="languages-grid">
                <?php foreach ($languages as $language): ?>
                    <div class="language-card">
                        <div class="language-header">
                            <h3><?= htmlspecialchars($language['languageName']) ?></h3>
                            <span class="language-code"><?= htmlspecialchars($language['languageCode']) ?></span>
                        </div>
                        <div class="language-status">
                            <span class="status-badge <?= $language['active'] ? 'active' : 'inactive' ?>">
                                <?= $language['active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                        <div class="language-actions">
                            <button class="btn btn-secondary" onclick="editLanguage(<?= $language['languageId'] ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger" onclick="toggleLanguageStatus(<?= $language['languageId'] ?>)">
                                <i class="fas fa-power-off"></i> 
                                <?= $language['active'] ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Add Language Modal -->
    <div id="newLanguageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Language</h3>
                <button class="close-modal" onclick="hideModal('newLanguageModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addLanguageForm" action="../../actions/admin/add_language.php" method="POST">
                <div class="form-group">
                    <label for="languageName">Language Name</label>
                    <input type="text" id="languageName" name="languageName" required>
                </div>
                <div class="form-group">
                    <label for="languageCode">Language Code</label>
                    <input type="text" id="languageCode" name="languageCode" 
                           pattern="[a-z]{2}" title="Two letter language code (e.g., en, es, fr)" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="hideModal('newLanguageModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Language</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/admin/languages.js"></script>
</body>
</html> 