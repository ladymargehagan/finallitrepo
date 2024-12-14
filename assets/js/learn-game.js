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
        this.initializeProgress();
    }

    initializeElements() {
        this.elements = {
            hearts: document.querySelector('.hearts'),
            answerBox: document.getElementById('answerBox'),
            wordBank: document.getElementById('wordBank'),
            checkButton: document.getElementById('checkBtn'),
            questionText: document.querySelector('.question'),
            exerciseType: document.querySelector('.exercise-type'),
            progressElements: {
                bar: document.querySelector('.progress-fill'),
                percentage: document.querySelector('.progress-text'),
                learning: document.querySelector('.learning'),
                familiar: document.querySelector('.familiar'),
                mastered: document.querySelector('.mastered')
            }
        };
        
        this.currentProgress = parseFloat(this.elements.progressElements.bar.style.width) || 0;
    }

    setupEventListeners() {
        this.setupDragAndDrop();
        
        if (this.elements.checkButton) {
            this.elements.checkButton.addEventListener('click', () => this.checkAnswer());
        }
    }

    initializeExercise() {
        this.currentExercise = {
            id: this.courseData.dataset.exerciseId,
            type: this.courseData.dataset.type
        };
        
        const wordTiles = document.querySelectorAll('.word-tile');
        wordTiles.forEach(tile => {
            tile.draggable = true;
            tile.addEventListener('dragstart', this.handleDragStart.bind(this));
        });
    }

    setupDragAndDrop() {
        const answerBox = this.elements.answerBox;
        const wordBank = this.elements.wordBank;

        // Double-click to return words
        answerBox.addEventListener('dblclick', (e) => {
            const tile = e.target.closest('.word-tile');
            if (tile) {
                wordBank.appendChild(tile);
            }
        });

        // Drag within answer box for reordering
        answerBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            const draggingTile = document.querySelector('.dragging');
            if (!draggingTile) return;

            const siblings = [...answerBox.querySelectorAll('.word-tile:not(.dragging)')];
            const nextSibling = siblings.find(sibling => {
                const box = sibling.getBoundingClientRect();
                return e.clientX <= box.left + box.width / 2;
            });

            if (nextSibling) {
                answerBox.insertBefore(draggingTile, nextSibling);
            } else {
                answerBox.appendChild(draggingTile);
            }
        });

        // Word bank drop handling
        wordBank.addEventListener('dragover', (e) => e.preventDefault());
        answerBox.addEventListener('dragover', (e) => e.preventDefault());

        wordBank.addEventListener('drop', (e) => {
            e.preventDefault();
            const tile = document.querySelector('.dragging');
            if (tile) wordBank.appendChild(tile);
        });

        answerBox.addEventListener('drop', (e) => {
            e.preventDefault();
            const tile = document.querySelector('.dragging');
            if (tile) answerBox.appendChild(tile);
        });
    }

    handleDragStart(e) {
        const tile = e.target;
        tile.classList.add('dragging');
        e.dataTransfer.setData('text/plain', '');
        
        tile.addEventListener('dragend', () => {
            tile.classList.remove('dragging');
        }, { once: true });
    }

    initializeProgress() {
        this.progressElements = {
            bar: document.querySelector('.progress-fill'),
            percentage: document.querySelector('.progress-text'),
            learning: document.querySelector('.learning'),
            familiar: document.querySelector('.familiar'),
            mastered: document.querySelector('.mastered')
        };

        // Initialize progress animation
        this.currentProgress = parseFloat(this.progressElements.bar.style.width) || 0;
    }

    updateProgress(progressData) {
        const newProgress = progressData.progress;
        const counts = progressData.counts;

        // Animate progress bar
        this.animateProgress(this.currentProgress, newProgress);
        this.currentProgress = newProgress;

        // Update counts with animation
        this.animateCount(this.progressElements.learning, counts.learning_count);
        this.animateCount(this.progressElements.familiar, counts.familiar_count);
        this.animateCount(this.progressElements.mastered, counts.mastered_count);

        // Update percentage text
        this.progressElements.percentage.textContent = 
            `Learning Progress: ${Math.round(newProgress)}%`;
    }

    animateProgress(start, end) {
        const duration = 1000; // 1 second
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const current = start + (end - start) * this.easeOutQuad(progress);
            this.progressElements.bar.style.width = `${current}%`;

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    animateCount(element, newValue) {
        const currentValue = parseInt(element.textContent.match(/\d+/)[0]);
        const duration = 1000;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const current = Math.round(currentValue + (newValue - currentValue) * this.easeOutQuad(progress));
            element.textContent = element.textContent.replace(/\d+/, current);

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    easeOutQuad(t) {
        return t * (2 - t);
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
                    this.progressData = data.progress;
                    this.showResultModal(true);
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

    showResultModal(isCorrect) {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        
        const modal = document.createElement('div');
        modal.className = 'result-modal';
        
        const content = `
            <div class="result-icon ${isCorrect ? 'correct' : 'incorrect'}">
                ${isCorrect ? '✓' : '✗'}
            </div>
            <div class="result-message">
                ${isCorrect ? 'Correct!' : 'Try again!'}
            </div>
            <button class="modal-button">
                ${isCorrect ? 'Continue' : 'OK'}
            </button>
        `;
        
        modal.innerHTML = content;
        
        document.body.appendChild(overlay);
        document.body.appendChild(modal);
        
        setTimeout(() => {
            overlay.classList.add('show');
            modal.classList.add('show');
        }, 10);
        
        const button = modal.querySelector('.modal-button');
        
        if (isCorrect) {
            button.onclick = () => {
                if (this.progressData) {
                    this.updateProgress(this.progressData);
                }
                
                overlay.classList.remove('show');
                modal.classList.remove('show');
                
                setTimeout(() => {
                    overlay.remove();
                    modal.remove();
                    // Get the current URL and reload with a new exercise
                    const currentUrl = new URL(window.location.href);
                    window.location.href = currentUrl.toString();
                }, 300);
            };
        } else {
            button.onclick = () => {
                overlay.classList.remove('show');
                modal.classList.remove('show');
                setTimeout(() => {
                    overlay.remove();
                    modal.remove();
                }, 300);
            };
        }
    }

    handleCorrectAnswer() {
        // Progress update happens in showResultModal's continue button click handler
        this.showResultModal(true);
    }

    handleWrongAnswer(hint) {
        this.hearts--;
        this.updateHearts();
        this.showResultModal(false);
        
        if (this.hearts <= 0) {
            this.gameOver();
        }
    }

    showProficiencyLevel(level) {
        const levelColors = {
            learning: '#FFA726',  // Orange
            familiar: '#66BB6A',  // Green
            mastered: '#42A5F5'   // Blue
        };

        const notification = document.createElement('div');
        notification.className = 'proficiency-notification';
        notification.innerHTML = `
            <div class="level-icon">
                <i class='bx ${this.getProficiencyIcon(level)}'></i>
            </div>
            <div class="level-text">
                Word Proficiency: ${level.charAt(0).toUpperCase() + level.slice(1)}
            </div>
        `;

        document.body.appendChild(notification);
        
        // Trigger animation
        setTimeout(() => notification.classList.add('show'), 100);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    getProficiencyIcon(level) {
        switch(level) {
            case 'learning': return 'bx-book-reader';
            case 'familiar': return 'bx-book-bookmark';
            case 'mastered': return 'bx-crown';
            default: return 'bx-book';
        }
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

    // ... (keep your existing utility methods)
}

document.addEventListener('DOMContentLoaded', () => {
    new LearnGame();
});
