<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$courseId = isset($_GET['course']) ? (int)$_GET['course'] : 0;
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';

// Verify this is a valid course and category
if ($courseId === 0 || $category === '') {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn French - Basic Phrases</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/learn.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>
<body>
    <!-- Progress Bar at top -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress" style="width: 30%"></div>
        </div>
        <div class="hearts">
            <i class="fas fa-heart"></i>
            <i class="fas fa-heart"></i>
            <i class="fas fa-heart"></i>
        </div>
    </div>

    <!-- Main Learning Interface -->
    <main class="learn-container">
        <div class="exercise-container">
            <!-- New Word Badge -->
            <div class="badge">
                <i class="fas fa-star"></i>
                NEW WORD
            </div>

            <!-- Question -->
            <h2 class="question">Write this in English</h2>

            <!-- Character Display -->
            <div class="character-display">
                <div class="icon-container">
                    <i class="fas fa-language fa-3x"></i>
                </div>
                <div class="speech-bubble">
                    <span class="french-text">C'est mal</span>
                    <button class="audio-btn">
                        <i class="fas fa-volume-up"></i>
                    </button>
                </div>
            </div>

            <!-- Answer Input Area -->
            <div class="answer-area">
                <div class="answer-box" id="answerBox"></div>
            </div>

            <!-- Word Bank -->
            <div class="word-bank" id="wordBank">
                <div class="word-tile" draggable="true">n't</div>
                <div class="word-tile" draggable="true">bad</div>
                <div class="word-tile" draggable="true">It's</div>
                <div class="word-tile" draggable="true">you</div>
                <div class="word-tile" draggable="true">really</div>
                <div class="word-tile" draggable="true">the</div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary" id="checkBtn">CHECK</button>
            </div>
        </div>
    </main>
</body>

<script>
let currentWordId = null;
const courseId = new URLSearchParams(window.location.search).get('course');
const category = new URLSearchParams(window.location.search).get('category');

// Add the loadNextWord function
async function loadNextWord() {
    try {
        const response = await fetch(`../actions/get_next_word.php?courseId=${courseId}&category=${category}`);
        const data = await response.json();
        
        if (data.success) {
            // Clear previous word
            answerBox.innerHTML = '';
            wordBank.innerHTML = '';
            
            // Update current word ID
            currentWordId = data.word.id;
            
            // Update the French text
            document.querySelector('.french-text').textContent = data.word.original;
            
            // Add pronunciation if available
            if (data.word.pronunciation) {
                document.querySelector('.french-text').setAttribute('data-pronunciation', data.word.pronunciation);
            }
            
            // Create new word tiles
            data.word.wordTiles.forEach(word => {
                const tile = document.createElement('div');
                tile.className = 'word-tile';
                tile.setAttribute('draggable', 'true');
                tile.textContent = word;
                
                // Add drag event listeners
                tile.addEventListener('dragstart', handleDragStart);
                tile.addEventListener('dragend', handleDragEnd);
                
                wordBank.appendChild(tile);
            });
            
            // Remove any success/error messages
            const messages = document.querySelectorAll('.success-message, .error-message');
            messages.forEach(msg => msg.remove());
            
            // Reset answer box styles
            answerBox.classList.remove('correct-answer', 'wrong-answer');
            
            // Animate new word entrance
            document.querySelector('.exercise-container').classList.add('fade-in');
            setTimeout(() => {
                document.querySelector('.exercise-container').classList.remove('fade-in');
            }, 500);
            
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error loading next word:', error);
        // Show error message to user
        const errorMsg = document.createElement('div');
        errorMsg.className = 'error-message';
        errorMsg.textContent = 'Failed to load next word. Please try again.';
        document.querySelector('.exercise-container').appendChild(errorMsg);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const wordBank = document.getElementById('wordBank');
    const answerBox = document.getElementById('answerBox');
    const wordTiles = document.querySelectorAll('.word-tile');
    
    // Initialize drag and drop
    wordTiles.forEach(tile => {
        tile.setAttribute('draggable', 'true');
        tile.addEventListener('dragstart', handleDragStart);
        tile.addEventListener('dragend', handleDragEnd);
    });

    // Add event listeners for the answer box
    answerBox.addEventListener('dragover', handleDragOver);
    answerBox.addEventListener('drop', handleDrop);

    function handleDragStart(e) {
        e.target.classList.add('dragging');
        e.dataTransfer.setData('text/plain', e.target.textContent);
        e.dataTransfer.effectAllowed = 'move';
    }

    function handleDragEnd(e) {
        e.target.classList.remove('dragging');
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    }

    function handleDrop(e) {
        e.preventDefault();
        const draggedTile = document.querySelector('.dragging');
        
        if (draggedTile) {
            // Create new tile in answer box
            const newTile = document.createElement('div');
            newTile.className = 'word-tile in-answer';
            newTile.textContent = draggedTile.textContent;
            
            // Add click handler to remove tile
            newTile.addEventListener('click', function() {
                // Create new tile back in word bank
                const returnedTile = document.createElement('div');
                returnedTile.className = 'word-tile';
                returnedTile.textContent = this.textContent;
                returnedTile.setAttribute('draggable', 'true');
                returnedTile.addEventListener('dragstart', handleDragStart);
                returnedTile.addEventListener('dragend', handleDragEnd);
                wordBank.appendChild(returnedTile);
                
                // Remove from answer box
                this.remove();
            });
            
            answerBox.appendChild(newTile);
            draggedTile.remove();
        }
    }

    // Check button functionality
    document.getElementById('checkBtn').addEventListener('click', async function() {
        const userAnswer = Array.from(answerBox.children)
            .map(tile => tile.textContent)
            .join(' ');
        
        try {
            const response = await fetch('../actions/check_answer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `answer=${encodeURIComponent(userAnswer)}&wordId=${encodeURIComponent(currentWordId)}`
            });

            const result = await response.json();
            
            if (result.correct) {
                // Success animation
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
                
                answerBox.classList.add('correct-answer');
                
                // Disable dragging on tiles
                answerBox.querySelectorAll('.word-tile').forEach(tile => {
                    tile.setAttribute('draggable', 'false');
                    tile.classList.add('completed');
                });
                
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.className = 'success-message';
                successMsg.innerHTML = '✨ Correct! Well done! ✨';
                document.querySelector('.exercise-container').appendChild(successMsg);
                
                // Load next word after delay
                setTimeout(loadNextWord, 2000);
            } else {
                // Error animation
                answerBox.classList.add('wrong-answer');
                answerBox.classList.add('shake');
                
                // Show hint message
                const hintMsg = document.createElement('div');
                hintMsg.className = 'hint-message';
                hintMsg.textContent = 'Try rearranging the words';
                document.querySelector('.exercise-container').appendChild(hintMsg);
                
                setTimeout(() => {
                    answerBox.classList.remove('shake');
                    answerBox.classList.remove('wrong-answer');
                    hintMsg.remove();
                }, 2000);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Load the first word when the page loads
    loadNextWord();
    
    // Add audio button functionality
    document.querySelector('.audio-btn').addEventListener('click', function() {
        const text = document.querySelector('.french-text').textContent;
        const pronunciation = document.querySelector('.french-text').getAttribute('data-pronunciation');
        
        // If you have a text-to-speech service, you can implement it here
        // For now, we'll just log to console
        console.log('Playing audio for:', text, pronunciation);
    });
});

// Update the answer box event listeners to handle internal reordering
function initializeAnswerBox() {
    const answerBox = document.getElementById('answerBox');
    
    answerBox.addEventListener('dragover', handleAnswerBoxDragOver);
    answerBox.addEventListener('drop', handleAnswerBoxDrop);
}

function handleAnswerBoxDragOver(e) {
    e.preventDefault();
    const draggingTile = document.querySelector('.dragging');
    const tiles = [...answerBox.querySelectorAll('.word-tile:not(.dragging)')];
    
    // Find the tile we're dragging over
    const nextTile = tiles.find(tile => {
        const rect = tile.getBoundingClientRect();
        const centerX = rect.x + rect.width / 2;
        return e.clientX < centerX;
    });
    
    if (nextTile) {
        answerBox.insertBefore(draggingTile, nextTile);
    } else {
        answerBox.appendChild(draggingTile);
    }
}

function handleAnswerBoxDrop(e) {
    e.preventDefault();
    const draggedTile = document.querySelector('.dragging');
    
    if (draggedTile) {
        if (draggedTile.parentElement === wordBank) {
            // Create new tile in answer box if coming from word bank
            const newTile = document.createElement('div');
            newTile.className = 'word-tile in-answer';
            newTile.setAttribute('draggable', 'true');
            newTile.textContent = draggedTile.textContent;
            
            // Add drag events for reordering
            newTile.addEventListener('dragstart', handleDragStart);
            newTile.addEventListener('dragend', handleDragEnd);
            
            // Add click to return to word bank
            newTile.addEventListener('dblclick', function() {
                returnTileToWordBank(this);
            });
            
            const dropPosition = findDropPosition(e.clientX);
            if (dropPosition.nextTile) {
                answerBox.insertBefore(newTile, dropPosition.nextTile);
            } else {
                answerBox.appendChild(newTile);
            }
            
            draggedTile.remove();
        }
    }
}

function findDropPosition(x) {
    const tiles = [...answerBox.querySelectorAll('.word-tile')];
    return {
        nextTile: tiles.find(tile => {
            const rect = tile.getBoundingClientRect();
            return x < (rect.left + rect.right) / 2;
        })
    };
}

function returnTileToWordBank(tile) {
    const returnedTile = document.createElement('div');
    returnedTile.className = 'word-tile';
    returnedTile.setAttribute('draggable', 'true');
    returnedTile.textContent = tile.textContent;
    returnedTile.addEventListener('dragstart', handleDragStart);
    returnedTile.addEventListener('dragend', handleDragEnd);
    wordBank.appendChild(returnedTile);
    tile.remove();
}
</script>
</html> 