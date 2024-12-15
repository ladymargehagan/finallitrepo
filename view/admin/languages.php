<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

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
    <link rel="stylesheet" href="../../assets/css/admin/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/admin/languages.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Language Management</h1>
                <button class="btn btn-primary" onclick="showAddLanguageModal()">
                    <i class="fas fa-plus"></i> Add New Language
                </button>
            </div>

            <div class="languages-grid">
                <?php foreach ($languages as $language): ?>
                    <div class="language-card <?= $language['active'] ? 'active' : 'inactive' ?>">
                        <div class="language-header">
                            <h3><?= htmlspecialchars($language['languageName']) ?></h3>
                            <span class="level-badge"><?= htmlspecialchars($language['level']) ?></span>
                        </div>
                        <p class="description"><?= htmlspecialchars($language['description']) ?></p>
                        <div class="language-actions">
                            <button class="btn btn-secondary" onclick="editLanguage(<?= htmlspecialchars(json_encode($language)) ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn <?= $language['active'] ? 'btn-danger' : 'btn-success' ?>"
                                    onclick="toggleLanguage(<?= $language['languageId'] ?>, <?= $language['active'] ?>)">
                                <i class="fas fa-<?= $language['active'] ? 'times' : 'check' ?>"></i>
                                <?= $language['active'] ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Add/Edit Language Modal -->
    <div id="languageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Add New Language</h2>
            <form id="languageForm">
                <input type="hidden" id="languageId" name="languageId">
                <div class="form-group">
                    <label for="languageName">Language Name</label>
                    <input type="text" id="languageName" name="languageName" required>
                </div>
                <div class="form-group">
                    <label for="level">Level</label>
                    <select id="level" name="level" required>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="hideModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/admin/languages.js"></script>
</body>
</html> 