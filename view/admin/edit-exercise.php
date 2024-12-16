<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Get exercise ID from URL
$exerciseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch exercise data
$stmt = $pdo->prepare("
    SELECT es.*, w.original_text, w.pronunciation, w.languageId, w.categoryId, 
           w.difficulty, wb.segment_text as answer_text
    FROM exercise_sets es
    JOIN words w ON es.wordId = w.wordId
    JOIN exercise_word_bank ewb ON es.exerciseId = ewb.exerciseId
    JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
    WHERE es.exerciseId = ? AND ewb.is_answer = 1
");
$stmt->execute([$exerciseId]);
$exerciseData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exerciseData) {
    header('Location: exercises.php');
    exit();
}

// Fetch languages and categories
$languages = $pdo->query("SELECT languageId, languageName FROM languages WHERE active = 1")->fetchAll();
$categories = $pdo->query("SELECT categoryId, categoryName FROM word_categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Exercise - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/admin/exercises.css">
    <link rel="stylesheet" href="../../assets/css/admin/dashboard.css">
</head>
<body class="admin-exercises">
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Edit Exercise</h1>
                <a href="exercises.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Exercises
                </a>
            </div>

            <div class="exercise-editor">
                <form id="editExerciseForm">
                    <input type="hidden" id="exerciseId" value="<?= $exerciseId ?>">
                    
                    <div class="form-group">
                        <label for="languageSelect">Language</label>
                        <select id="languageSelect" required>
                            <?php foreach ($languages as $language): ?>
                                <option value="<?= $language['languageId'] ?>" 
                                    <?= $exerciseData['languageId'] == $language['languageId'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($language['languageName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="categorySelect">Category</label>
                        <select id="categorySelect" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['categoryId'] ?>" 
                                    <?= $exerciseData['categoryId'] == $category['categoryId'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['categoryName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="difficultySelect">Difficulty</label>
                        <select id="difficultySelect" required>
                            <option value="easy" <?= $exerciseData['difficulty'] == 'easy' ? 'selected' : '' ?>>Easy</option>
                            <option value="medium" <?= $exerciseData['difficulty'] == 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="hard" <?= $exerciseData['difficulty'] == 'hard' ? 'selected' : '' ?>>Hard</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="questionText">Word to Translate</label>
                        <input type="text" 
                               id="questionText" 
                               value="<?= htmlspecialchars($exerciseData['original_text']) ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="pronunciationText">Pronunciation</label>
                        <input type="text" 
                               id="pronunciationText" 
                               value="<?= htmlspecialchars($exerciseData['pronunciation']) ?>"
                               placeholder="e.g., bohn-ZHOOR">
                    </div>

                    <div class="form-group">
                        <label>Word Bank Options</label>
                        <div class="word-bank-editor">
                            <div class="correct-answer">
                                <label>Correct Translation</label>
                                <input type="text" id="correctAnswer" 
                                       value="<?= htmlspecialchars($exerciseData['answer_text'] ?? '') ?>" 
                                       required>
                            </div>
                            <div class="other-options">
                                <label>Other Options</label>
                                <div id="otherOptions">
                                    <?php
                                    $bankWords = $pdo->prepare("
                                        SELECT wb.segment_text, ewb.is_answer 
                                        FROM exercise_word_bank ewb
                                        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
                                        WHERE ewb.exerciseId = ?
                                        ORDER BY ewb.position
                                    ");
                                    $bankWords->execute([$exerciseId]);
                                    
                                    foreach ($bankWords as $word): 
                                        if (!$word['is_answer']): ?>
                                            <div class="option-input">
                                                <input type="text" 
                                                       value="<?= htmlspecialchars($word['segment_text']) ?>" 
                                                       class="word-option">
                                                <button type="button" class="remove-option">×</button>
                                            </div>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                                <button type="button" id="addOption" class="btn btn-secondary">
                                    <i class="fas fa-plus"></i> Add Option
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="exercises.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Exercise
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../../assets/js/admin/edit-exercise.js"></script>
</body>
</html>
