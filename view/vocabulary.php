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
    <link rel="stylesheet" href="../assets/css/course.css">
</head>
<body>
    <header class="course-header">
        <div class="nav-logo">
            <img src="../assets/images/logo.svg" alt="Logo" class="logo-image">
        </div>
        <a href="../view/course_page.php" class="back-to-course"></a>
    </header>

    <div class="vocabulary-container">
        <div class="vocabulary-header">
            <h1>Vocabulary Practice</h1>
        </div>
        <div class="card-navigation">
            <span class="progress-indicator">Card <span id="currentCard">1</span> of <?php echo count($vocabulary); ?></span>
        </div>

        <div class="single-card-container">
            <?php foreach ($vocabulary as $index => $word): ?>
                <div class="flashcard <?php echo $index === 0 ? 'active' : ''; ?>" 
                     style="<?php echo $index === 0 ? '' : 'display: none;'; ?>"
                     data-index="<?php echo $index; ?>">
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

        <div class="navigation-controls">
            <button class="btn btn-secondary" id="prevBtn" disabled>
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            <button class="btn btn-next" id="nextBtn">
                Next <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <div class="tips-container">
            <div class="tips-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div class="tips-content">
                <h4>Quick Tips:</h4>
                <ul>
                    <li><span class="icon"><i class="fas fa-mouse-pointer"></i></span> Click the card to see its translation</li>
                    <li><span class="icon"><i class="fas fa-arrows-alt"></i></span> Use navigation buttons to move between cards</li>
                    <li><span class="icon"><i class="fas fa-graduation-cap"></i></span> Practice each word before moving on</li>
                </ul>
            </div>
        </div>

        <div class="action-buttons" style="display: none;" id="startExercise">
            <a href="learn.php?course=<?php echo $languageId; ?>&category=<?php echo $categorySlug; ?>" 
               class="btn btn-primary btn-large">
                Start Exercises <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.flashcard');
            const currentCardSpan = document.getElementById('currentCard');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const startExercise = document.getElementById('startExercise');
            let currentIndex = 0;

            function updateCard(index) {
                cards.forEach((card, idx) => {
                    if (idx === index) {
                        card.style.display = 'block';
                        card.classList.add('active');
                    } else {
                        card.style.display = 'none';
                        card.classList.remove('active');
                        // Reset flip state for hidden cards
                        card.querySelector('.flashcard-inner').classList.remove('flipped');
                    }
                });
                
                currentCardSpan.textContent = index + 1;
                
                // Update button states
                prevBtn.disabled = index === 0;
                if (index === cards.length - 1) {
                    nextBtn.textContent = 'Finish';
                    nextBtn.classList.add('btn-next');
                } else {
                    nextBtn.textContent = 'Next';
                    nextBtn.classList.remove('btn-next');
                }
            }

            // Add click event for card flipping
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    // Only flip if card is active/visible
                    if (this.classList.contains('active')) {
                        const inner = this.querySelector('.flashcard-inner');
                        inner.classList.toggle('flipped');
                    }
                });
            });

            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCard(currentIndex);
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentIndex < cards.length - 1) {
                    currentIndex++;
                    updateCard(currentIndex);
                } else {
                    startExercise.style.display = 'block';
                    nextBtn.style.display = 'none';
                }
            });

            // Initialize first card
            updateCard(0);
        });
    </script>
</body>
</html> 