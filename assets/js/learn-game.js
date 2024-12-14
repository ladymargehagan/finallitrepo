class LearnGame {
    constructor() {
        this.hearts = 3;
        this.currentExercise = null;
        this.courseData = document.getElementById('courseData');
        this.languageId = this.courseData.dataset.languageId;
        this.category = this.courseData.dataset.category;
        
        this.initializeElements();
        this.setupEventListeners();
        this.initializeExercise();
        
        // Add tips functionality
        this.initializeTips();
    }

    initializeElements() {
        this.elements = {
            hearts: document.querySelector('.hearts'),
            answerBox: document.getElementById('answerBox'),
            wordBank: document.getElementById('wordBank'),
            checkButton: document.getElementById('checkBtn'),
            questionText: document.querySelector('.question'),
            exerciseType: document.querySelector('.exercise-type')
        };
    }

    setupEventListeners() {
        // Setup drag and drop
        this.setupDragAndDrop();
        
        // Check button click
        if (this.elements.checkButton) {
            this.elements.checkButton.addEventListener('click', () => this.checkAnswer());
        }
    }

    initializeExercise() {
        // Get exercise data from the page
        this.currentExercise = {
            id: this.courseData.dataset.exerciseId,
            type: this.courseData.dataset.type
        };
        
        // Initialize word bank tiles
        const wordTiles = document.querySelectorAll('.word-tile');
        wordTiles.forEach(tile => {
            tile.draggable = true;
            tile.addEventListener('dragstart', this.handleDragStart.bind(this));
        });
    }

    setupDragAndDrop() {
        const answerBox = this.elements.answerBox;
        const wordBank = this.elements.wordBank;

        // Answer box drop handling
        answerBox.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        answerBox.addEventListener('drop', (e) => {
            e.preventDefault();
            const tile = document.querySelector('.dragging');
            if (tile) {
                answerBox.appendChild(tile);
            }
        });

        // Word bank drop handling
        wordBank.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        wordBank.addEventListener('drop', (e) => {
            e.preventDefault();
            const tile = document.querySelector('.dragging');
            if (tile) {
                wordBank.appendChild(tile);
            }
        });
    }

    handleDragStart(e) {
        const tile = e.target;
        tile.classList.add('dragging');
        e.dataTransfer.setData('text/plain', '');
        
        // Remove dragging class when drag ends
        tile.addEventListener('dragend', () => {
            tile.classList.remove('dragging');
        }, { once: true });
    }

    async checkAnswer() {
        const userAnswer = Array.from(this.elements.answerBox.children)
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
                    exerciseId: this.currentExercise.id,
                    answer: userAnswer
                })
            });

            const data = await response.json();
            
            if (data.success) {
                if (data.correct) {
                    this.handleCorrectAnswer();
                } else {
                    this.handleWrongAnswer(data.hint);
                }
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Error checking answer:', error);
            this.showError('Failed to check answer. Please try again.');
        }
    }

    handleCorrectAnswer() {
        // Show success message
        this.showMessage('Correct!', 'success');
        
        // Reload page after delay to get next exercise
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    handleWrongAnswer(hint) {
        this.hearts--;
        this.updateHearts();
        this.showMessage(hint || 'Try again!', 'error');
        
        if (this.hearts <= 0) {
            this.gameOver();
        }
    }

    updateHearts() {
        if (this.elements.hearts) {
            this.elements.hearts.textContent = '❤️'.repeat(this.hearts);
        }
    }

    showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        messageDiv.textContent = message;
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 2000);
    }

    gameOver() {
        // Show game over message and reload page
        this.showMessage('Game Over!', 'error');
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }

    initializeTips() {
        const tipsContainer = document.querySelector('.tips-container');
        if (tipsContainer) {
            const tipsToggle = tipsContainer.querySelector('.tips-toggle');
            const tipsContent = tipsContainer.querySelector('.tips-content');
            
            tipsToggle.addEventListener('click', () => {
                tipsContent.style.display = 
                    tipsContent.style.display === 'none' ? 'block' : 'none';
            });
        }
    }
}

// Initialize the game when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new LearnGame();
});