<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get and validate parameters
$languageId = isset($_GET['course']) ? (int)$_GET['course'] : 0;
$categorySlug = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';

// Fetch vocabulary words for this category
try {
    $stmt = $pdo->prepare("
        SELECT 
            w.wordId,
            w.word,
            w.pronunciation,
            t.translated_text,
            wc.categoryName,
            l.languageName
        FROM words w
        JOIN translations t ON w.wordId = t.wordId
        JOIN word_categories wc ON w.categoryId = wc.categoryId
        JOIN languages l ON w.languageId = l.languageId
        WHERE w.languageId = ? 
        AND wc.categorySlug = ?
        ORDER BY w.wordId
    ");
    
    $stmt->execute([$languageId, $categorySlug]);
    $vocabulary = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($vocabulary)) {
        header('Location: course_page.php?languageId=' . $languageId);
        exit();
    }
    
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: course_page.php?languageId=' . $languageId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn <?php echo htmlspecialchars($vocabulary[0]['categoryName']); ?> Vocabulary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/vocabulary.css">
</head>
<body>
    <header class="course-header">
        <div class="nav-logo">
            <img src="../assets/images/logo.png" alt="Logo" class="logo-image">
        </div>
        <h1>Learn <?php echo htmlspecialchars($vocabulary[0]['categoryName']); ?> Vocabulary</h1>
        <div class="auth-buttons">
            <a href="course_page.php?languageId=<?php echo $languageId; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </header>

    <div class="vocabulary-container">
        <div class="flashcards-grid">
            <?php foreach ($vocabulary as $word): ?>
            <div class="flashcard">
                <div class="flashcard-inner">
                    <div class="flashcard-front">
                        <div class="word"><?php echo htmlspecialchars($word['word']); ?></div>
                        <div class="pronunciation"><?php echo htmlspecialchars($word['pronunciation']); ?></div>
                    </div>
                    <div class="flashcard-back">
                        <div class="word"><?php echo htmlspecialchars($word['translated_text']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="action-buttons">
            <a href="learn.php?course=<?php echo $languageId; ?>&category=<?php echo $categorySlug; ?>" class="btn btn-primary btn-large">
                Start Exercises <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.flashcard').forEach(card => {
            card.addEventListener('click', () => {
                card.classList.toggle('flipped');
            });
        });
    </script>
</body>
</html> 