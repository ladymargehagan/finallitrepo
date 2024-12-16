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
        this.updateHearts();
        this.exerciseStartTime = Date.now() / 1000;
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
    }

    setupEventListeners() {
        this.setupDragAndDrop();
        
        if (this.elements.checkButton) {
            this.elements.checkButton.addEventListener('click', () => this.checkAnswer());
        }

        const endSessionBtn = document.getElementById('endSessionBtn');
        if (endSessionBtn) {
            endSessionBtn.addEventListener('click', () => this.endSession());
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

    updateProgress(progressData) {
        if (!progressData) return;
        
        // Only update progress if we have new progress data
        if (progressData.progress !== undefined) {
            if (this.elements.progressElements.bar) {
                this.elements.progressElements.bar.style.width = `${progressData.progress}%`;
            }

            if (this.elements.progressElements.percentage) {
                this.elements.progressElements.percentage.textContent = 
                    `Learning Progress: ${Math.round(progressData.progress)}%`;
            }

            // Update counts only if provided
            if (progressData.counts) {
                const counts = progressData.counts;
                if (this.elements.progressElements.learning) {
                    this.elements.progressElements.learning.textContent = `Learning: ${counts.learning_count}`;
                }
                if (this.elements.progressElements.familiar) {
                    this.elements.progressElements.familiar.textContent = `Familiar: ${counts.familiar_count}`;
                }
                if (this.elements.progressElements.mastered) {
                    this.elements.progressElements.mastered.textContent = `Mastered: ${counts.mastered_count}`;
                }
            }
        }
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
                    answer: userAnswer,
                    startTime: this.exerciseStartTime || Date.now() / 1000
                })
            });

            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                if (data.correct) {
                    this.showResultModal(true);
                } else {
                    this.handleWrongAnswer();
                }
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Error checking answer:', error);
            this.showError('Failed to check answer. Please try again.');
        }
    }

    showResultModal(isCorrect, message = null) {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        
        const modal = document.createElement('div');
        modal.className = 'result-modal';
        
        const content = `
            <div class="result-icon ${isCorrect ? 'correct' : 'incorrect'}">
                ${isCorrect ? '✓' : '✗'}
            </div>
            <div class="result-message">
                ${isCorrect ? 'Correct!' : message || 'Try again!'}
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
        button.onclick = () => {
            overlay.classList.remove('show');
            modal.classList.remove('show');
            setTimeout(() => {
                overlay.remove();
                modal.remove();
                if (isCorrect) {
                    window.location.href = new URL(window.location.href).toString();
                }
            }, 300);
        };
    }

    handleCorrectAnswer() {
        // Progress update happens in showResultModal's continue button click handler
        this.showResultModal(true);
    }

    handleWrongAnswer() {
        this.hearts--;
        this.updateHearts();
        
        if (this.hearts <= 0) {
            this.showGameOverModal();
        } else {
            this.showResultModal(false, 'Try again!');
        }
    }

    showGameOverModal() {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        
        const modal = document.createElement('div');
        modal.className = 'result-modal game-over';
        
        const content = `
            <div class="result-icon">
                <i class='bx bx-x-circle'></i>
            </div>
            <div class="result-message">
                Better luck next time!
            </div>
            <button class="modal-button">Try Again</button>
        `;
        
        modal.innerHTML = content;
        document.body.appendChild(overlay);
        document.body.appendChild(modal);
        
        setTimeout(() => {
            overlay.classList.add('show');
            modal.classList.add('show');
        }, 10);
        
        const button = modal.querySelector('.modal-button');
        button.onclick = () => {
            overlay.classList.remove('show');
            modal.classList.remove('show');
            setTimeout(() => {
                overlay.remove();
                modal.remove();
                this.resetExercise();
            }, 300);
        };
    }

    resetExercise() {
        this.hearts = 3;
        this.updateHearts();
        window.location.reload(); // Reload for new exercise while keeping progress
    }

    initializeTips() {
        const tipsToggle = document.querySelector('.tips-toggle, .bulb-icon');
        const tipsContent = document.querySelector('.quick-tips, .tips-content');
        
        if (tipsToggle && tipsContent) {
            tipsToggle.style.cursor = 'pointer';
            
            tipsToggle.addEventListener('click', () => {
                const isVisible = tipsContent.style.display !== 'none';
                tipsContent.style.display = isVisible ? 'none' : 'block';
                localStorage.setItem('tipsVisible', !isVisible);
            });

            // Set initial state
            const shouldBeVisible = localStorage.getItem('tipsVisible') !== 'false';
            tipsContent.style.display = shouldBeVisible ? 'block' : 'none';
        }
    }

    updateHearts() {
        if (this.elements.hearts) {
            this.elements.hearts.innerHTML = '';
            
            // Add filled hearts based on remaining hearts
            for (let i = 0; i < this.hearts; i++) {
                const heart = document.createElement('i');
                heart.className = 'bx bxs-heart';
                heart.style.color = '#ff4b4b';  // Red color
                heart.classList.add('heart-beat'); // Add animation class
                this.elements.hearts.appendChild(heart);
            }

            // Add empty hearts for lost ones
            for (let i = this.hearts; i < 3; i++) {
                const heart = document.createElement('i');
                heart.className = 'bx bx-heart';
                heart.style.color = '#ccc';  // Gray color
                this.elements.hearts.appendChild(heart);
            }
        }
    }

    loadExercise() {
        this.initializeExercise();
        this.exerciseStartTime = Date.now() / 1000;
    }

    async endSession() {
        try {
            console.log('Attempting to end session...');
            const response = await fetch('../actions/end_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                // Store current course and category for the "Try Again" button
                sessionStorage.setItem('last_course', this.languageId);
                sessionStorage.setItem('last_category', this.category);
                
                // Redirect to results page
                window.location.href = 'exercise_results.php';
            } else {
                console.error('Server returned error:', data.error);
                alert('Failed to end session. Please try again.');
            }
        } catch (error) {
            console.error('Error ending session:', error);
            alert('Failed to end session. Please try again.');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new LearnGame();
});
