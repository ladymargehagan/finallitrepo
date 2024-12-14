<?php
session_start();
require_once '../config/db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug incoming parameters
error_log("DEBUG: Incoming parameters:");
error_log("Course ID: " . (isset($_GET['course']) ? $_GET['course'] : 'not set'));
error_log("Category: " . (isset($_GET['category']) ? $_GET['category'] : 'not set'));
error_log("User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set'));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get and validate parameters
$languageId = isset($_GET['course']) ? (int)$_GET['course'] : 0;
$categorySlug = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';

// Debug the SQL query
$debugQuery = "
    SELECT 
        w.wordId as exerciseId,
        'translation' as type,
        w.word,
        w.pronunciation,
        w.context_type,
        w.difficulty,
        t.translated_text,
        wc.categoryName,
        l.languageName,
        wc.categoryId
    FROM words w
    JOIN translations t ON w.wordId = t.wordId
    JOIN word_categories wc ON w.categoryId = wc.categoryId
    JOIN languages l ON w.languageId = l.languageId
    WHERE w.languageId = $languageId 
    AND wc.categorySlug = '$categorySlug'
    AND w.wordId NOT IN (
        SELECT wordId 
        FROM learned_words 
        WHERE userId = {$_SESSION['user_id']}
        AND proficiency = 'mastered'
    )
    AND t.is_primary = 1
    ORDER BY RAND()
    LIMIT 1";

error_log("DEBUG: Query being executed: " . $debugQuery);

// Execute the query
$stmt = $pdo->prepare("
SELECT 
    es.exerciseId,
    es.type,
    w.word,
    w.pronunciation,
    w.context_type,
    es.difficulty,
    t.translated_text,
    wc.categoryName,
    l.languageName,
    wc.categoryId
FROM exercise_sets es
JOIN words w ON es.wordId = w.wordId
JOIN translations t ON es.translationId = t.translationId
JOIN word_categories wc ON w.categoryId = wc.categoryId
JOIN languages l ON w.languageId = l.languageId
WHERE w.languageId = ? 
AND wc.categorySlug = ?
AND w.wordId NOT IN (
    SELECT wordId 
    FROM learned_words 
    WHERE userId = ? 
    AND proficiency = 'mastered'
)
ORDER BY RAND()
LIMIT 1
");

try {
    $stmt->execute([$languageId, $categorySlug, $_SESSION['user_id']]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Debug the result
    error_log("DEBUG: Query result: " . json_encode($exercise));
    
    if (!$exercise) {
        // Debug why no exercises were found
        error_log("DEBUG: No exercises found. Checking data:");
        
        // Check if words exist for this language
        $wordCheck = $pdo->prepare("SELECT COUNT(*) FROM words WHERE languageId = ?");
        $wordCheck->execute([$languageId]);
        $wordCount = $wordCheck->fetchColumn();
        error_log("Words for language $languageId: $wordCount");
        
        // Check if category exists
        $catCheck = $pdo->prepare("SELECT COUNT(*) FROM word_categories WHERE categorySlug = ?");
        $catCheck->execute([$categorySlug]);
        $catCount = $catCheck->fetchColumn();
        error_log("Category with slug '$categorySlug': $catCount");
        
        // Check translations
        $transCheck = $pdo->prepare("
            SELECT COUNT(*) 
            FROM words w 
            JOIN translations t ON w.wordId = t.wordId 
            WHERE w.languageId = ?
        ");
        $transCheck->execute([$languageId]);
        $transCount = $transCheck->fetchColumn();
        error_log("Translations for language $languageId: $transCount");
        
        // Set exercise to empty array instead of redirecting
        $exercise = [];
    }
} catch (PDOException $e) {
    error_log("DEBUG: Database error: " . $e->getMessage());
    $exercise = [];
}

// Fetch word bank options
// Fetch word bank options
$stmt = $pdo->prepare("
    SELECT 
        ws.segment_text,
        ws.part_of_speech
    FROM word_segments ws
    JOIN translations t ON ws.translationId = t.translationId
    WHERE t.wordId = ?
    ORDER BY ws.position
");
$stmt->execute([$exercise['exerciseId']]);
$wordBank = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no word segments found, split the translation into words
if (empty($wordBank)) {
    $words = explode(' ', $exercise['translated_text']);
    $wordBank = array_map(function($word) {
        return [
            'segment_text' => $word,
            'part_of_speech' => null
        ];
    }, $words);
}

// Get progress statistics
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_words,
        (SELECT COUNT(*) 
         FROM learned_words lw 
         JOIN words w ON lw.wordId = w.wordId 
         WHERE w.languageId = ? 
         AND w.categoryId = ? 
         AND lw.userId = ?) as learned_words
    FROM words 
    WHERE languageId = ? 
    AND categoryId = ?
");
$stmt->execute([
    $languageId, 
    $exercise['categoryId'], 
    $_SESSION['user_id'],
    $languageId,
    $exercise['categoryId']
]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate progress percentage
$progressPercent = $progress['total_words'] > 0 
    ? ($progress['learned_words'] / $progress['total_words']) * 100 
    : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn <?php echo htmlspecialchars($exercise['languageName']); ?> - <?php echo htmlspecialchars($exercise['categoryName']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/learn.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>
<body>
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo $progressPercent; ?>%"></div>
        </div>
        <div class="hearts"></div>
    </div>

    <main class="learn-container">
        <div class="exercise-container">
            <div class="badge">
                <i class="fas fa-star"></i>
                <span class="exercise-type">
                    <?php echo strtoupper($exercise['type']); ?>
                    <span class="difficulty-badge <?php echo $exercise['difficulty']; ?>">
                        <?php echo ucfirst($exercise['difficulty']); ?>
                    </span>
                </span>
            </div>

            <h2 class="question">
                <?php 
                switch($exercise['type']) {
                    case 'translation':
                        echo 'Translate this to English';
                        break;
                    case 'matching':
                        echo 'Match the correct translation';
                        break;
                    case 'fill-in':
                        echo 'Complete the sentence';
                        break;
                }
                ?>
            </h2>

            <div class="character-display">
                <div class="icon-container">
                    <i class="fas fa-language fa-3x"></i>
                </div>
                <div class="speech-bubble">
                    <span class="original-text" 
                          data-pronunciation="<?php echo htmlspecialchars($exercise['pronunciation']); ?>"
                          data-context="<?php echo htmlspecialchars($exercise['context_type']); ?>">
                        <?php echo htmlspecialchars($exercise['word']); ?>
                    </span>
                    <button class="audio-btn" title="Listen to pronunciation">
                        <i class="fas fa-volume-up"></i>
                    </button>
                </div>
            </div>

            <div class="answer-area">
                <div class="answer-box" id="answerBox"></div>
            </div>

            <div class="word-bank" id="wordBank">
                <?php foreach ($wordBank as $word): ?>
                    <div class="word-tile" 
                         draggable="true"
                         data-part="<?php echo htmlspecialchars($word['part_of_speech']); ?>">
                        <?php echo htmlspecialchars($word['segment_text']); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" id="checkBtn">CHECK</button>
            </div>
        </div>
    </main>

    <div id="courseData" 
         data-exercise-id="<?php echo $exercise['exerciseId']; ?>"
         data-language-id="<?php echo $languageId; ?>"
         data-category="<?php echo htmlspecialchars($categorySlug); ?>"
         data-type="<?php echo htmlspecialchars($exercise['type']); ?>"
         style="display: none;">
    </div>

    <script src="../assets/js/learn-game.js"></script>
</body>
</html> 