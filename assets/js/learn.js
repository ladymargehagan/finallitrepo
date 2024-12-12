class LearnGame {
    constructor() {
        this.hearts = 3;
        this.currentWordIndex = 0;
        this.words = [];
        this.initialized = false;

        // DOM elements
        this.heartsContainer = document.querySelector('.hearts');
        this.answerBox = document.querySelector('.answer-box');
        this.wordBank = document.querySelector('.word-bank');
        this.checkButton = document.querySelector('.btn-check');
        
        this.initialize();
    }

    async initialize() {
        try {
            // Fetch words from the database
            const response = await fetch('/api/words/basic-phrases');
            this.words = await response.json();
            
            this.renderHearts();
            this.loadWord(this.currentWordIndex);
            this.initialized = true;

            // Add event listeners
            this.checkButton.addEventListener('click', () => this.checkAnswer());
        } catch (error) {
            console.error('Failed to initialize game:', error);
        }
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

    checkAnswer() {
        const userAnswer = Array.from(this.answerBox.children)
            .map(tile => tile.textContent)
            .join(' ')
            .trim();

        const correctAnswer = this.words[this.currentWordIndex].translation;

        if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
            // Correct answer
            this.answerBox.classList.add('correct-answer');
            
            // Update progress in database
            this.updateProgress();

            // Move to next word after delay
            setTimeout(() => {
                this.answerBox.classList.remove('correct-answer');
                this.currentWordIndex++;
                
                if (this.currentWordIndex < this.words.length) {
                    this.loadWord(this.currentWordIndex);
                } else {
                    this.showCompletionMessage();
                }
            }, 1000);
        } else {
            // Wrong answer
            this.answerBox.classList.add('wrong-answer', 'shake');
            this.loseHeart();
            
            setTimeout(() => {
                this.answerBox.classList.remove('wrong-answer', 'shake');
            }, 500);
        }
    }

    async updateProgress() {
        try {
            await fetch('/api/progress/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    wordId: this.words[this.currentWordIndex].wordId
                })
            });
        } catch (error) {
            console.error('Failed to update progress:', error);
        }
    }

    loadWord(index) {
        if (!this.words[index]) return;
        
        // Clear previous word
        this.answerBox.innerHTML = '';
        this.wordBank.innerHTML = '';

        // Load new word and scramble answer options
        const currentWord = this.words[index];
        const words = currentWord.translation.split(' ');
        const scrambledWords = this.scrambleArray([...words]);

        // Create word tiles
        scrambledWords.forEach(word => {
            const tile = document.createElement('div');
            tile.className = 'word-tile';
            tile.textContent = word;
            tile.draggable = true;
            this.wordBank.appendChild(tile);
        });

        // Update question display
        document.querySelector('.french-text').textContent = currentWord.word;
    }

    scrambleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    showCompletionMessage() {
        const message = document.createElement('div');
        message.className = 'success-message';
        message.textContent = 'Congratulations! You've completed all words!';
        document.querySelector('.exercise-container').appendChild(message);
    }
}

// Initialize game when document is ready
document.addEventListener('DOMContentLoaded', () => {
    const game = new LearnGame();
}); 