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


// Execute the query
$stmt = $pdo->prepare("
    SELECT 
        es.exerciseId,
        es.wordId,
        w.word,
        w.pronunciation,
        w.context_type,
        w.difficulty,
        es.type,
        wc.categoryName,
        wc.categoryId,
        l.languageName
    FROM exercise_sets es
    JOIN words w ON es.wordId = w.wordId
    JOIN word_categories wc ON w.categoryId = wc.categoryId
    JOIN languages l ON w.languageId = l.languageId
    WHERE w.languageId = ? 
    AND wc.categorySlug = ?
    AND w.wordId NOT IN (
        SELECT wordId FROM learned_words 
        WHERE userId = ? AND proficiency = 'mastered'
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

try {
    $stmt->execute([$languageId, $categorySlug, $_SESSION['user_id']]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Initialize error message
    $errorMessage = null;
    
    // Check for specific error conditions
    if (!$exercise) {
        // Check what's missing
        $wordCheck = $pdo->prepare("SELECT COUNT(*) FROM words WHERE languageId = ? AND categoryId = (SELECT categoryId FROM word_categories WHERE categorySlug = ?)");
        $wordCheck->execute([$languageId, $categorySlug]);
        $wordCount = $wordCheck->fetchColumn();
        
        if ($wordCount == 0) {
            $errorMessage = "No words available in this category yet. Please try another category.";
        } else {
            $learnedCheck = $pdo->prepare("
                SELECT COUNT(*) FROM words w 
                WHERE w.languageId = ? 
                AND w.categoryId = (SELECT categoryId FROM word_categories WHERE categorySlug = ?)
                AND w.wordId NOT IN (
                    SELECT wordId FROM learned_words 
                    WHERE userId = ? AND proficiency = 'mastered'
                )
            ");
            $learnedCheck->execute([$languageId, $categorySlug, $_SESSION['user_id']]);
            $remainingWords = $learnedCheck->fetchColumn();
            
            if ($remainingWords == 0) {
                $errorMessage = "Congratulations! You've mastered all words in this category.";
            }
        }
        
        if (!$errorMessage) {
            $errorMessage = "Unable to load exercise. Please try again later.";
        }
    }
} catch (PDOException $e) {
    $errorMessage = "Database error occurred. Please try again later.";
    error_log("Database error: " . $e->getMessage());
}

// After getting the exercise, add debug logging
if ($exercise) {
    error_log("DEBUG: Exercise found: " . json_encode($exercise));
    
    // Get word bank options with more detailed query
    $wordBankStmt = $pdo->prepare("
        SELECT 
            wb.bankWordId,
            wb.segment_text,
            wb.part_of_speech,
            ewb.is_answer,
            ewb.position
        FROM exercise_word_bank ewb
        JOIN word_bank wb ON ewb.bankWordId = wb.bankWordId
        WHERE ewb.exerciseId = ?
    ");
    
    error_log("DEBUG: Executing word bank query for exerciseId: " . $exercise['exerciseId']);
    $wordBankStmt->execute([$exercise['exerciseId']]);
    $wordBank = $wordBankStmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("DEBUG: Word bank results: " . json_encode($wordBank));
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
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }
        
        .modal-buttons {
            margin-top: 20px;
        }
        
        .modal-buttons button {
            margin: 0 10px;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .tips-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            max-width: 300px;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .tips-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
        
        .tips-icon {
            position: absolute;
            top: -15px;
            left: -15px;
            background: #ffd700;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        
        .tips-content h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
        }
        
        .tips-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .tips-content li {
            margin: 8px 0;
            font-size: 14px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .tips-content li i {
            color: #4CAF50;
            width: 20px;
        }
        
        /* Make tips collapsible on mobile */
        @media (max-width: 768px) {
            .tips-container {
                bottom: 10px;
                right: 10px;
                max-width: calc(100% - 40px);
            }
            
            .tips-content {
                display: none;
            }
            
            .tips-container:hover .tips-content {
                display: block;
            }
        }
    </style>
</head>
<body>
    <?php if ($errorMessage): ?>
    <!-- Error Modal -->
    <div id="errorModal" class="modal" style="display: block;">
        <div class="modal-content">
            <h3>Notice</h3>
            <p><?php echo htmlspecialchars($errorMessage); ?></p>
            <div class="modal-buttons">
                <button onclick="window.location.href='dashboard.php'" class="btn btn-secondary">
                    Return to Dashboard
                </button>
                <?php if (strpos($errorMessage, "mastered") !== false): ?>
                    <button onclick="window.location.href='progress.php'" class="btn btn-primary">
                        View Progress
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $progressPercent; ?>%"></div>
            </div>
            <div class="hearts"></div>
        </div>

        <div class="tips-container">
            <div class="tips-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div class="tips-content">
                <h4>Quick Tips:</h4>
                <ul>
                    <li><i class="fas fa-arrows-alt"></i> Drag words from the word bank to form your answer</li>
                    <li><i class="fas fa-mouse-pointer"></i> Double-click any word in your answer to send it back</li>
                    <li><i class="fas fa-exchange-alt"></i> Drag words within your answer to reorder them</li>
                </ul>
            </div>
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
                             data-part="<?php echo htmlspecialchars($word['part_of_speech']); ?>"
                             data-is-answer="<?php echo $word['is_answer']; ?>"
                             data-position="<?php echo $word['position']; ?>">
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
    <script>
        // Handle modal close and navigation
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('errorModal');
            if (modal) {
                // Prevent modal from closing when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        e.stopPropagation();
                    }
                });
            }
        });
    </script>
    <?php endif; ?>
</body>
</html> 