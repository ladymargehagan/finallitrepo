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
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/admin/exercises.css">
</head>
<body class="admin-exercises">
    <?php include 'includes/admin-nav.php'; ?>

    <main class="admin-main">
        <!-- Quick Actions Panel -->
        <div class="quick-actions-panel">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <button class="btn btn-secondary" id="newCategoryBtn">
                    <i class="fas fa-folder-plus"></i> New Category
                </button>
            </div>
        </div>

        <!-- Exercise Creator Section -->
        <div class="exercise-creator">
            <div class="toolbar">
                <button id="newExercise" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Exercise
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
                    <button id="saveExercise" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Exercise
                    </button>
                </div>
            </div>
        </div>

        <!-- Existing Exercises Section -->
        <div class="existing-exercises">
            <div class="exercises-header">
                <h2>Existing Exercises</h2>
                <select id="filterLanguage" class="language-filter">
                    <option value="">All Languages</option>
                    <?php foreach ($languages as $language): ?>
                        <option value="<?= $language['languageId'] ?>">
                            <?= htmlspecialchars($language['languageName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="exercises-grid" id="exercisesGrid">
                <!-- Exercises will be loaded here dynamically -->
            </div>
        </div>
    </main>

    <!-- Edit Exercise Modal -->
    <div id="editExerciseModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Exercise</h2>
            <!-- The form will be populated by JavaScript -->
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete this exercise?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="btn btn-danger">Delete</button>
                <button class="btn btn-secondary close-modal">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Include JavaScript -->
    <script src="../../assets/js/admin/exercises.js"></script>
</body>
</html>

