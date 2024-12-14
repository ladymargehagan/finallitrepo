class LearnGame {
    constructor() {
        this.hearts = 3;
        this.currentWord = null;
        this.courseData = document.getElementById('courseData');
        this.languageId = this.courseData.dataset.languageId;
        this.category = this.courseData.dataset.category;
        
        this.initializeElements();
        this.setupEventListeners();
        this.loadNextWord();
        
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
            originalText: document.querySelector('.french-text'),
            exerciseType: document.querySelector('.exercise-type'),
            progressBar: document.querySelector('.progress')
        };

        // Initialize drag and drop for existing tiles
        const wordTiles = document.querySelectorAll('.word-tile');
        wordTiles.forEach(tile => {
            tile.setAttribute('draggable', 'true');
            this.addTileListeners(tile);
        });

        // Add answer box listeners
        this.elements.answerBox.addEventListener('dragover', this.handleDragOver.bind(this));
        this.elements.answerBox.addEventListener('drop', this.handleDrop.bind(this));
    }

    setupEventListeners() {
        this.elements.checkButton.addEventListener('click', () => this.checkAnswer());
        
        const audioBtn = document.querySelector('.audio-btn');
        if (audioBtn) {
            audioBtn.addEventListener('click', () => this.playAudio());
        }
    }

    addTileListeners(tile) {
        tile.addEventListener('dragstart', (e) => {
            e.target.classList.add('dragging');
            e.dataTransfer.setData('text/plain', e.target.textContent);
            e.dataTransfer.effectAllowed = 'move';
        });

        tile.addEventListener('dragend', (e) => {
            e.target.classList.remove('dragging');
        });
    }

    handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        
        const draggable = document.querySelector('.dragging');
        if (draggable) {
            const afterElement = this.getDragAfterElement(e.clientX);
            if (afterElement) {
                this.elements.answerBox.insertBefore(draggable, afterElement);
            } else {
                this.elements.answerBox.appendChild(draggable);
            }
        }
    }

    handleDrop(e) {
        e.preventDefault();
        const draggedTile = document.querySelector('.dragging');
        
        if (draggedTile) {
            // Create new tile in answer box
            const newTile = document.createElement('div');
            newTile.className = 'word-tile in-answer';
            newTile.textContent = draggedTile.textContent;
            newTile.setAttribute('draggable', 'true');
            this.addTileListeners(newTile);
            
            // Add click handler to return tile
            newTile.addEventListener('dblclick', () => {
                this.returnTileToWordBank(newTile);
            });
            
            const afterElement = this.getDragAfterElement(e.clientX);
            if (afterElement) {
                this.elements.answerBox.insertBefore(newTile, afterElement);
            } else {
                this.elements.answerBox.appendChild(newTile);
            }
            
            draggedTile.remove();
        }
    }

    getDragAfterElement(x) {
        const tiles = [...this.elements.answerBox.querySelectorAll('.word-tile:not(.dragging)')];
        
        return tiles.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = x - box.left - box.width / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    returnTileToWordBank(tile) {
        const returnedTile = document.createElement('div');
        returnedTile.className = 'word-tile';
        returnedTile.setAttribute('draggable', 'true');
        returnedTile.textContent = tile.textContent;
        this.addTileListeners(returnedTile);
        this.elements.wordBank.appendChild(returnedTile);
        tile.remove();
    }

    async checkAnswer() {
        const userAnswer = Array.from(this.elements.answerBox.children)
            .map(tile => tile.textContent)
            .join(' ')
            .trim();

        try {
            const response = await fetch('../actions/check_answer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    exerciseId: this.currentWord.id,
                    answer: userAnswer
                })
            });

            const result = await response.json();
            
            if (result.correct) {
                this.handleCorrectAnswer();
            } else {
                this.handleWrongAnswer(result.hint);
            }
        } catch (error) {
            console.error('Error checking answer:', error);
            this.showError('Failed to check answer. Please try again.');
        }
    }

    handleCorrectAnswer() {
        // Success animation
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
        
        this.elements.answerBox.classList.add('correct-answer');
        
        // Show success message
        this.showSuccess('✨ Correct! Well done! ✨');
        
        // Load next word after delay
        setTimeout(() => this.loadNextWord(), 2000);
    }

    handleWrongAnswer(hint) {
        this.elements.answerBox.classList.add('wrong-answer', 'shake');
        this.showHint(hint || 'Try rearranging the words');
        
        setTimeout(() => {
            this.elements.answerBox.classList.remove('wrong-answer', 'shake');
        }, 1000);
    }

    showSuccess(message) {
        const successMsg = document.createElement('div');
        successMsg.className = 'success-message';
        successMsg.innerHTML = message;
        document.querySelector('.exercise-container').appendChild(successMsg);
        
        setTimeout(() => successMsg.remove(), 2000);
    }

    showError(message) {
        const errorMsg = document.createElement('div');
        errorMsg.className = 'error-message';
        errorMsg.textContent = message;
        document.querySelector('.exercise-container').appendChild(errorMsg);
        
        setTimeout(() => errorMsg.remove(), 3000);
    }

    showHint(message) {
        const hintMsg = document.createElement('div');
        hintMsg.className = 'hint-message';
        hintMsg.textContent = message;
        document.querySelector('.exercise-container').appendChild(hintMsg);
        
        setTimeout(() => hintMsg.remove(), 2000);
    }

    initializeTips() {
        const tipsContainer = document.querySelector('.tips-container');
        const tipsIcon = document.querySelector('.tips-icon');
        
        // On mobile, show/hide tips on icon click
        if (window.innerWidth <= 768) {
            tipsIcon.addEventListener('click', () => {
                const tipsContent = document.querySelector('.tips-content');
                tipsContent.style.display = 
                    tipsContent.style.display === 'none' ? 'block' : 'none';
            });
        }

        // Hide tips after 5 seconds on first visit
        if (!localStorage.getItem('tipsShown')) {
            setTimeout(() => {
                tipsContainer.style.opacity = '0.5';
            }, 5000);
            
            // Show tips again on hover
            tipsContainer.addEventListener('mouseenter', () => {
                tipsContainer.style.opacity = '1';
            });

            localStorage.setItem('tipsShown', 'true');
        }
    }
}

// Initialize the game when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new LearnGame();
});