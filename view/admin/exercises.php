<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch languages for dropdown
$languages = $pdo->query("SELECT languageId, languageName FROM languages WHERE active = 1")->fetchAll();
// Fetch categories for dropdown
$categories = $pdo->query("SELECT categoryId, categoryName FROM word_categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Management - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin/styles.css">
    <link rel="stylesheet" href="../../assets/css/admin/exercises.css">
</head>
<body class="admin-exercises">
    <?php include 'includes/admin-nav.php'; ?>

    <main class="admin-main">
        <!-- Quick Actions Panel -->
        <div class="quick-actions-panel">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <button class="btn-secondary" onclick="showModal('newCategoryModal')">
                    <i class="fas fa-folder-plus"></i> New Category
                </button>
                <button class="btn-secondary" onclick="showModal('newLanguageModal')">
                    <i class="fas fa-language"></i> New Language
                </button>
            </div>
        </div>

        <div class="exercise-creator">
            <div class="toolbar">
                <button id="prevExercise" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
                <button id="newExercise" class="btn-primary">
                    <i class="fas fa-plus"></i> New Exercise
                </button>
                <button id="nextExercise" class="btn-secondary">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            <div class="exercise-form">
                <div class="form-header">
                    <select id="languageSelect" required>
                        <option value="">Select Language</option>
                        <?php foreach ($languages as $language): ?>
                            <option value="<?= $language['languageId'] ?>">
                                <?= htmlspecialchars($language['languageName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select id="categorySelect" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['categoryId'] ?>">
                                <?= htmlspecialchars($category['categoryName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select id="difficultySelect" required>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="exercise-preview">
                    <div class="question-box">
                        <input type="text" id="questionText" placeholder="Enter the question text..." required>
                    </div>

                    <div class="answer-box" id="answerBox">
                        <div class="placeholder">Drag word tiles here to create the correct answer</div>
                    </div>

                    <div class="word-bank" id="wordBank">
                        <div class="word-input-container">
                            <input type="text" class="word-input" placeholder="Add a word...">
                            <button class="add-word-btn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="word-tiles"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button id="saveExercise" class="btn-primary">
                        <i class="fas fa-save"></i> Save Exercise
                    </button>
                </div>
            </div>
        </div>

        <div class="exercise-grid">
            <h2>Existing Exercises</h2>
            <div class="grid-container" id="exerciseGrid">
                <!-- Exercises will be loaded here dynamically -->
            </div>
        </div>
    </main>

    <script src="../../assets/js/admin/exercises.js"></script>
</body>
</html>
