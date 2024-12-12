<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get language ID from URL
$languageId = isset($_GET['languageId']) ? (int)$_GET['languageId'] : 0;

// Fetch language details
try {
    $stmt = $pdo->prepare("
        SELECT l.*, ue.status 
        FROM languages l
        JOIN user_enrollments ue ON l.languageId = ue.languageId
        WHERE l.languageId = ? AND ue.userId = ? AND ue.status = 'active'
    ");
    $stmt->execute([$languageId, $_SESSION['user_id']]);
    $course = $stmt->fetch();

    if (!$course) {
        header('Location: dashboard.php');
        exit();
    }

    // Get learned words count
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as wordsLearned 
        FROM learned_words 
        WHERE userId = ? AND wordId IN (
            SELECT wordId FROM words WHERE languageId = ?
        )
    ");
    $stmt->execute([$_SESSION['user_id'], $languageId]);
    $progress = $stmt->fetch();

    // Replace the hardcoded categories array with database query
    try {
        // Fetch categories and word counts for this language
        $stmt = $pdo->prepare("
            SELECT 
                wc.*,
                COUNT(w.wordId) as wordCount
            FROM word_categories wc
            LEFT JOIN words w ON wc.categoryId = w.categoryId 
                AND w.languageId = ?
            GROUP BY wc.categoryId
            ORDER BY wc.categoryId
        ");
        $stmt->execute([$languageId]);
        $categories = $stmt->fetchAll();

    } catch (PDOException $e) {
        error_log($e->getMessage());
        $categories = [];
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: dashboard.php');
    exit('Something went wrong');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn <?php echo htmlspecialchars($course['languageName']); ?> - Language Learning Platform</title>
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

    <!-- Main Content -->
    <main class="course-content">
        <h1>Learn <?php echo htmlspecialchars($course['languageName']); ?></h1>
        <div class="categories">
            <div class="category-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card" onclick="window.location.href='french_basic.php?languageId=<?= $languageId ?>&category=<?= $category['categorySlug'] ?>'">
                        <div class="category-icon">
                            <?php if ($category['categorySlug'] === 'greetings-basics'): ?>
                                <span class="number-badge">1</span>
                            <?php endif; ?>
                            <i class="<?= htmlspecialchars($category['icon']) ?>"></i>
                        </div>
                        <div class="category-info">
                            <h3><?= htmlspecialchars($category['categoryName']) ?></h3>
                            <p><?= htmlspecialchars($category['description']) ?></p>
                            <span class="word-count"><?= $category['wordCount'] ?> words</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html> 