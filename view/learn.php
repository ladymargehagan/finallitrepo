<?php
session_start();
require_once '../config/db_connect.php';
require_once '../includes/quiz-functions.php';

// Check if starting a new quiz in a different category
if (isset($_GET['category']) && 
    (!isset($_SESSION['current_category']) || $_SESSION['current_category'] !== $_GET['category'])) {
    // Clear previous quiz data
    $_SESSION['attempted_exercises'] = [];
    $_SESSION['current_category'] = $_GET['category'];
    $_SESSION['exercise_start_time'] = time();
    $_SESSION['correct_answers'] = 0;
    $_SESSION['total_attempts'] = 0;
}

// Initialize attempted exercises array if not exists
if (!isset($_SESSION['attempted_exercises'])) {
    $_SESSION['attempted_exercises'] = [];
}

// Initialize error message variable
$errorMessage = null;

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

// Store these in session as soon as we have them
$_SESSION['last_course_id'] = $languageId;
$_SESSION['last_category_slug'] = $categorySlug;

// Get total exercises count
$stmt = $pdo->prepare("
    SELECT COUNT(*) as total 
    FROM exercise_sets 
    WHERE wordId IN (
        SELECT wordId 
        FROM words 
        WHERE languageId = ? AND categoryId = (
            SELECT categoryId 
            FROM word_categories 
            WHERE categorySlug = ?
        )
    )
");
$stmt->execute([$languageId, $categorySlug]);
$totalExercises = $stmt->fetchColumn();

// Add this for accurate progress calculation
$currentProgress = count($_SESSION['attempted_exercises']);

// Initialize exercise variable
$exercise = null;

// Main exercise query with NOT IN clause to exclude completed exercises
if (empty($_SESSION['attempted_exercises'])) {
    // If no attempted exercises, get any exercise
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
        ORDER BY RAND()
        LIMIT 1
    ");
    $params = [$languageId, $categorySlug];
} else {
    // Get an exercise that hasn't been attempted yet
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
        AND es.exerciseId NOT IN (" . implode(',', array_keys($_SESSION['attempted_exercises'])) . ")
        ORDER BY RAND()
        LIMIT 1
    ");
    $params = [$languageId, $categorySlug];
}

try {
    $stmt->execute($params);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($exercise) {
        $_SESSION['current_quiz_info'] = [
            'language' => $exercise['languageName'],
            'category' => $exercise['categoryName']
        ];
    } else {
        // No more exercises available - quiz is complete
        // Calculate final score
        $totalExercises = count($_SESSION['attempted_exercises']);
        $correctAnswers = array_sum($_SESSION['attempted_exercises']);
        
        // Store quiz results
        $finalScore = ($correctAnswers / $totalExercises) * 100;
        
        // Store in database and redirect to results
        // ... (implement storing logic here)
        
        header('Location: exercise_results.php');
        exit();
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    header('Location: dashboard.php');
    exit();
}

// Get word bank options only if we have an exercise
if ($exercise) {
    error_log("DEBUG: Exercise found: " . json_encode($exercise));
    
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

// Initialize exercise start time if not set
if (!isset($_SESSION['exercise_start_time'])) {
    $_SESSION['exercise_start_time'] = time();
}

// Store exercise session data only after completion
if (isset($_POST['completed']) && $exercise) {
    // Update session with completion data
    $_SESSION['exercise_results']['end_time'] = time();
    
    // Store in database
    $sessionStmt = $pdo->prepare("
        INSERT INTO exercise_sessions 
        (userId, exerciseSetId, startTime, endTime, totalWords, correctWords) 
        VALUES (?, ?, FROM_UNIXTIME(?), NOW(), ?, ?)
    ");
    
    $sessionStmt->execute([
        $_SESSION['user_id'],
        $exercise['exerciseId'],
        $_SESSION['exercise_start_time'],
        $_SESSION['exercise_results']['total_words'],
        $_SESSION['exercise_results']['correct_words']
    ]);
    
    // Store last course and category for "Try Again" button
    $_SESSION['last_course'] = $languageId;
    $_SESSION['last_category'] = $categorySlug;
    
    // Redirect to results page
    header('Location: exercise_results.php');
    exit();
}
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
        
        .progress-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 20px;
            padding: 10px;
        }
        
        .progress-bar {
            background-color: #f0f0f0;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-fill {
            background-color: #4CAF50;
            height: 100%;
            border-radius: 5px;
            transition: width 0.3s ease;
        }
        
        .progress-text {
            text-align: center;
            margin-top: 5px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
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
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php 
                    $progress = ($currentProgress / $totalExercises) * 100;
                    echo $progress . '%';
                ?>"></div>
            </div>
            <div class="progress-text">
                Question <?php echo $currentProgress + 1; ?> of <?php echo $totalExercises; ?>
            </div>
        </div>

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

            <div class="exercise-actions">
                <button id="checkBtn" class="btn btn-primary">Check Answer</button>
            </div>

            <div class="next-question-container" style="display: none;">
                <button id="nextBtn" class="next-button">Next Question</button>
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
        // Add these variables at the top
        let currentExerciseId = <?php echo json_encode($exercise['exerciseId'] ?? null); ?>;
        let exerciseType = <?php echo json_encode($exercise['type'] ?? null); ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            const checkBtn = document.getElementById('checkBtn');
            const nextBtn = document.getElementById('nextBtn');
            const answerBox = document.getElementById('answerBox');
            const nextContainer = document.querySelector('.next-question-container');
            
            // Initially hide the next button container
            nextContainer.style.display = 'none';
            
            checkBtn.addEventListener('click', function() {
                const answer = Array.from(answerBox.children)
                    .map(tile => tile.textContent.trim())
                    .join(' ');
                    
                fetch('/actions/check_answer.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        exerciseId: currentExerciseId,
                        answer: answer
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.isCorrect) {
                        // Show success animation
                        confetti({
                            particleCount: 100,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                        
                        answerBox.classList.add('correct-answer');
                    } else {
                        // Show wrong answer animation
                        answerBox.classList.add('wrong-answer', 'shake');
                    }

                    // Disable check button and word bank immediately after any attempt
                    checkBtn.disabled = true;
                    disableWordBank();

                    // Always show next button after attempt, regardless of correctness
                    nextContainer.style.display = 'block';
                    setTimeout(() => {
                        nextContainer.classList.add('visible');
                    }, 50);

                    // Store the result
                    fetch('/actions/store_attempt.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            exerciseId: currentExerciseId,
                            isCorrect: data.isCorrect,
                            userAnswer: answer,
                            correctAnswer: data.correctAnswer
                        })
                    });
                });
            });

            nextBtn.addEventListener('click', function() {
                window.location.reload();
            });
        });

        function disableWordBank() {
            const wordBank = document.querySelector('.word-bank');
            const tiles = wordBank.getElementsByClassName('word-tile');
            Array.from(tiles).forEach(tile => {
                tile.style.pointerEvents = 'none';
                tile.style.opacity = '0.6';
            });
        }
    </script>
</body>
</html> 