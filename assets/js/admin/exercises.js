class ExerciseCreator {
    constructor() {
        this.initializeElements();
        this.setupEventListeners();
        this.loadExistingExercises();
    }

    initializeElements() {
        this.answerBox = document.getElementById('answerBox');
        this.wordBank = document.getElementById('wordBank');
        this.wordInput = document.querySelector('.word-input');
        this.addWordBtn = document.querySelector('.add-word-btn');
        this.saveBtn = document.getElementById('saveExercise');
        this.exerciseGrid = document.getElementById('exerciseGrid');
    }

    setupEventListeners() {
        this.addWordBtn.addEventListener('click', () => this.addWord());
        this.wordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.addWord();
        });
        this.saveBtn.addEventListener('click', () => this.saveExercise());

        // Setup drag and drop
        this.setupDragAndDrop();
    }

    addWord() {
        const word = this.wordInput.value.trim();
        if (!word) return;

        const wordTile = this.createWordTile(word);
        document.querySelector('.word-tiles').appendChild(wordTile);
        this.wordInput.value = '';
        this.wordInput.focus();
    }

    createWordTile(word) {
        const tile = document.createElement('div');
        tile.className = 'word-tile';
        tile.textContent = word;
        tile.draggable = true;
        
        tile.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', word);
            tile.classList.add('dragging');
        });

        tile.addEventListener('dragend', () => {
            tile.classList.remove('dragging');
        });

        return tile;
    }

    setupDragAndDrop() {
        this.answerBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.answerBox.classList.add('drag-over');
        });

        this.answerBox.addEventListener('dragleave', () => {
            this.answerBox.classList.remove('drag-over');
        });

        this.answerBox.addEventListener('drop', (e) => {
            e.preventDefault();
            this.answerBox.classList.remove('drag-over');
            
            const word = e.dataTransfer.getData('text/plain');
            const wordTile = this.createWordTile(word);
            
            // Remove placeholder if it exists
            const placeholder = this.answerBox.querySelector('.placeholder');
            if (placeholder) placeholder.remove();
            
            this.answerBox.appendChild(wordTile);
        });
    }

    async saveExercise() {
        const exerciseData = {
            languageId: document.getElementById('languageSelect').value,
            categoryId: document.getElementById('categorySelect').value,
            difficulty: document.getElementById('difficultySelect').value,
            questionText: document.getElementById('questionText').value,
            answer: Array.from(this.answerBox.querySelectorAll('.word-tile'))
                .map(tile => tile.textContent)
                .join(' '),
            wordBank: Array.from(document.querySelectorAll('.word-tiles .word-tile'))
                .map(tile => tile.textContent)
        };

        try {
            const response = await fetch('../../actions/admin/save_exercise.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(exerciseData)
            });

            const result = await response.json();
            if (result.success) {
                alert('Exercise saved successfully!');
                this.loadExistingExercises();
                this.resetForm();
            } else {
                alert('Error saving exercise: ' + result.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to save exercise');
        }
    }

    async loadExistingExercises() {
        try {
            const response = await fetch('../../actions/admin/get_exercises.php');
            const data = await response.json();
            
            if (data.success && data.exercises) {
                this.exerciseGrid.innerHTML = data.exercises
                    .map(exercise => this.createExerciseCard(exercise))
                    .join('');
            } else {
                console.error('Failed to load exercises:', data.error);
            }
        } catch (error) {
            console.error('Error loading exercises:', error);
        }
    }

    createExerciseCard(exercise) {
        return `
            <div class="exercise-card">
                <h3>${exercise.questionText || 'No question text'}</h3>
                <div class="meta">
                    <span>${exercise.languageName || 'Unknown language'}</span>
                    <span>${exercise.categoryName || 'Unknown category'}</span>
                    <span>Difficulty: ${exercise.difficulty || 'Not set'}</span>
                </div>
                <div class="actions">
                    <button onclick="exerciseCreator.editExercise(${exercise.exerciseId})" class="btn-secondary">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="exerciseCreator.deleteExercise(${exercise.exerciseId})" class="btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        `;
    }

    resetForm() {
        document.getElementById('questionText').value = '';
        this.answerBox.innerHTML = '<div class="placeholder">Drag word tiles here to create the correct answer</div>';
        document.querySelector('.word-tiles').innerHTML = '';
    }
}

// Initialize the exercise creator when the document loads
document.addEventListener('DOMContentLoaded', () => {
    window.exerciseCreator = new ExerciseCreator();
});
