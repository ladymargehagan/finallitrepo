class LearnGame {
    constructor() {
        this.hearts = 3;
        this.currentWordIndex = 0;
        this.currentWord = null;
        this.initialized = false;

        // DOM elements
        this.heartsContainer = document.querySelector('.hearts');
        this.answerBox = document.querySelector('.answer-box');
        this.wordBank = document.querySelector('.word-bank');
        this.checkButton = document.querySelector('.btn-check');
        this.progressBar = document.querySelector('.progress');
        
        if (!this.heartsContainer || !this.answerBox || !this.wordBank || !this.checkButton) {
            throw new Error('Required DOM elements not found');
        }
        
        this.initialize();
    }

    async initialize() {
        try {
            await this.loadNextWord();
            this.renderHearts();
            this.setupEventListeners();
            this.initialized = true;
        } catch (error) {
            console.error('Failed to initialize game:', error);
            this.showError('Failed to initialize game. Please refresh the page.');
        }
    }

    setupEventListeners() {
        this.checkButton.addEventListener('click', () => this.checkAnswer());
        this.setupDragAndDrop();
    }

    async loadNextWord() {
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const courseId = urlParams.get('course');
            const category = urlParams.get('category');

            const response = await fetch(`../actions/get_next_word.php?courseId=${courseId}&category=${category}`);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            this.currentWord = data.word;
            this.renderWord();
        } catch (error) {
            console.error('Failed to load word:', error);
            this.showError('Failed to load next word. Please try again.');
        }
    }

    renderWord() {
        this.answerBox.innerHTML = '';
        this.wordBank.innerHTML = '';

        document.querySelector('.french-text').textContent = this.currentWord.original;

        this.currentWord.wordTiles.forEach(word => {
            const tile = document.createElement('div');
            tile.className = 'word-tile';
            tile.textContent = word;
            tile.draggable = true;
            this.wordBank.appendChild(tile);
        });
    }

    async checkAnswer() {
        const userAnswer = Array.from(this.answerBox.children)
            .map(tile => tile.textContent)
            .join(' ')
            .trim();

        try {
            const response = await fetch('../actions/check_answer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    wordId: this.currentWord.id,
                    answer: userAnswer
                })
            });

            const data = await response.json();

            if (data.success) {
                if (data.correct) {
                    this.handleCorrectAnswer();
                } else {
                    this.handleWrongAnswer();
                }
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error checking answer:', error);
            this.showError('Failed to check answer. Please try again.');
        }
    }

    handleCorrectAnswer() {
        this.answerBox.classList.add('correct-answer');
        
        setTimeout(async () => {
            this.answerBox.classList.remove('correct-answer');
            await this.loadNextWord();
        }, 1000);
    }

    handleWrongAnswer() {
        this.answerBox.classList.add('wrong-answer', 'shake');
        this.loseHeart();
        
        setTimeout(() => {
            this.answerBox.classList.remove('wrong-answer', 'shake');
        }, 500);
    }

    setupDragAndDrop() {
        this.wordBank.addEventListener('dragstart', (e) => {
            if (e.target.classList.contains('word-tile')) {
                e.target.classList.add('dragging');
            }
        });

        this.wordBank.addEventListener('dragend', (e) => {
            if (e.target.classList.contains('word-tile')) {
                e.target.classList.remove('dragging');
            }
        });

        this.answerBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            const draggable = document.querySelector('.dragging');
            if (draggable) {
                this.answerBox.appendChild(draggable);
            }
        });
    }

    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        document.querySelector('.exercise-container').appendChild(errorDiv);

        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }

    renderHearts() {
        this.heartsContainer.innerHTML = '';
        for (let i = 0; i < this.hearts; i++) {
            const heart = document.createElement('i');
            heart.className = 'fas fa-heart';
            this.heartsContainer.appendChild(heart);
        }
    }

    loseHeart() {
        this.hearts--;
        this.renderHearts();
        
        if (this.hearts === 0) {
            this.gameOver();
        }
    }

    gameOver() {
        // Show game over message
        const message = document.createElement('div');
        message.className = 'game-over-message';
        message.textContent = 'Game Over! Try again.';
        document.querySelector('.exercise-container').appendChild(message);

        // Reset game after delay
        setTimeout(() => {
            this.hearts = 3;
            this.currentWordIndex = 0;
            this.renderHearts();
            this.loadWord(this.currentWordIndex);
            message.remove();
        }, 2000);
    }
}

// Initialize game when document is ready
document.addEventListener('DOMContentLoaded', () => {
    try {
        new LearnGame();
    } catch (error) {
        console.error('Failed to initialize game:', error);
        const container = document.querySelector('.exercise-container');
        if (container) {
            container.innerHTML = '<div class="error-message">Failed to load game. Please refresh the page.</div>';
        }
    }
}); 