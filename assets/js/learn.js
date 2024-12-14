class LearnGame {
    constructor(container, courseId, category) {
        this.container = container;
        this.courseId = courseId;
        this.category = category;
        this.currentExercise = null;
        this.hearts = 3;

        // Get DOM elements
        this.answerBox = container.querySelector('.answer-box');
        this.wordBank = container.querySelector('.word-bank');
        this.checkButton = container.querySelector('#checkBtn');
        
        // Initialize
        this.setupEventListeners();
        this.loadNextWord();
    }

    async loadNextWord() {
        try {
            const response = await fetch('../actions/get_next_word.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    courseId: this.courseId,
                    category: this.category
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.currentExercise = data.word;
                this.renderWord();
            } else {
                throw new Error(data.error || 'Failed to load word');
            }
        } catch (error) {
            console.error('Error loading word:', error);
            this.showError('Failed to load next word. Please try again.');
        }
    }

    renderWord() {
        // Clear previous word
        this.answerBox.innerHTML = '';
        this.wordBank.innerHTML = '';

        // Update the original text display
        document.querySelector('.french-text').textContent = this.currentExercise.original;

        // Create word tiles from segments
        this.currentExercise.segments.forEach(segment => {
            const tile = document.createElement('div');
            tile.className = 'word-tile';
            tile.textContent = segment;
            tile.draggable = true;
            this.wordBank.appendChild(tile);
        });

        // Shuffle the word bank tiles
        this.shuffleWordBank();
    }

    shuffleWordBank() {
        const tiles = Array.from(this.wordBank.children);
        tiles.forEach(tile => {
            tile.style.order = Math.floor(Math.random() * tiles.length);
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

    setupEventListeners() {
        // Drag and drop functionality
        this.setupDragAndDrop();

        // Check button
        this.checkButton.addEventListener('click', () => this.checkAnswer());

        // Reset button (if exists)
        const resetBtn = this.container.querySelector('#resetBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.resetAnswer());
        }
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
                const afterElement = this.getDragAfterElement(this.answerBox, e.clientY);
                if (afterElement) {
                    this.answerBox.insertBefore(draggable, afterElement);
                } else {
                    this.answerBox.appendChild(draggable);
                }
            }
        });
    }

    getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.word-tile:not(.dragging)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    resetAnswer() {
        // Move all tiles back to word bank
        Array.from(this.answerBox.children).forEach(tile => {
            this.wordBank.appendChild(tile);
        });
        this.shuffleWordBank();
    }

    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        this.container.appendChild(errorDiv);

        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }
}