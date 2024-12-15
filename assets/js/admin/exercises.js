class ExerciseCreator {
    constructor() {
        this.init();
        this.loadExistingExercises();
    }

    init() {
        this.loadWords();
        this.loadBankWords();
        this.setupEventListeners();
        this.setupExistingExercisesFilter();
    }

    setupExistingExercisesFilter() {
        const filterSelect = document.getElementById('filterLanguage');
        if (filterSelect) {
            filterSelect.addEventListener('change', () => {
                this.loadExistingExercises(filterSelect.value);
            });
        }
    }

    async loadWords() {
        try {
            const response = await fetch('../../actions/admin/get_words.php');
            const data = await response.json();
            if (data.success) {
                const wordSelect = document.getElementById('wordSelect');
                data.words.forEach(word => {
                    const option = document.createElement('option');
                    option.value = word.wordId;
                    option.textContent = word.word_text;
                    wordSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading words:', error);
        }
    }

    async loadBankWords() {
        try {
            const response = await fetch('../../actions/admin/get_bank_words.php');
            const data = await response.json();
            if (data.success) {
                const bankWordSelect = document.getElementById('bankWordSelect');
                data.words.forEach(word => {
                    const option = document.createElement('option');
                    option.value = word.bankWordId;
                    option.textContent = word.segment_text;
                    bankWordSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading bank words:', error);
        }
    }

    setupEventListeners() {
        document.getElementById('wordSelect').addEventListener('change', (e) => {
            this.loadTranslations(e.target.value);
        });

        document.getElementById('addWordOption').addEventListener('click', () => {
            this.addWordBankOption();
        });

        document.getElementById('saveExercise').addEventListener('click', () => {
            this.saveExercise();
        });
    }

    async loadTranslations(wordId) {
        try {
            const response = await fetch(`../../actions/admin/get_translations.php?wordId=${wordId}`);
            const data = await response.json();
            if (data.success) {
                const translationSelect = document.getElementById('translationSelect');
                translationSelect.innerHTML = '<option value="">Select translation...</option>';
                data.translations.forEach(translation => {
                    const option = document.createElement('option');
                    option.value = translation.translationId;
                    option.textContent = translation.translation_text;
                    translationSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading translations:', error);
        }
    }

    addWordBankOption() {
        const bankWordSelect = document.getElementById('bankWordSelect');
        const selectedOptions = document.getElementById('selectedOptions');
        
        if (bankWordSelect.value) {
            const option = bankWordSelect.options[bankWordSelect.selectedIndex];
            const wordOption = document.createElement('div');
            wordOption.className = 'word-option';
            wordOption.innerHTML = `
                <span>${option.text}</span>
                <label>
                    <input type="radio" name="correct_answer" value="${option.value}">
                    Correct Answer
                </label>
                <button class="remove-option">×</button>
            `;
            selectedOptions.appendChild(wordOption);
        }
    }

    async saveExercise() {
        const selectedOptions = document.getElementById('selectedOptions');
        const wordBankOptions = Array.from(selectedOptions.children).map(option => {
            const radio = option.querySelector('input[type="radio"]');
            return {
                bankWordId: radio.value,
                isAnswer: radio.checked,
            };
        });

        // Check if we have at least one correct answer
        if (!wordBankOptions.some(option => option.isAnswer)) {
            alert('Please mark at least one option as the correct answer');
            return;
        }

        const exerciseData = {
            wordId: document.getElementById('wordSelect').value,
            translationId: document.getElementById('translationSelect').value,
            type: document.getElementById('typeSelect').value,
            difficulty: document.getElementById('difficultySelect').value,
            wordBankOptions: wordBankOptions
        };

        // Validate all required fields
        if (!exerciseData.wordId || !exerciseData.translationId || 
            !exerciseData.type || !exerciseData.difficulty || 
            !exerciseData.wordBankOptions.length) {
            alert('Please fill in all required fields');
            return;
        }

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

    async loadExistingExercises(languageId = '') {
        try {
            const response = await fetch(`../../actions/admin/get_exercises.php?languageId=${languageId}`);
            if (!response.ok) throw new Error('Failed to fetch exercises');
            
            const exercises = await response.json();
            const grid = document.getElementById('exercisesGrid');
            
            if (!grid) return;
            grid.innerHTML = ''; // Clear existing exercises

            if (!exercises.length) {
                grid.innerHTML = '<p class="no-exercises">No exercises found</p>';
                return;
            }

            exercises.forEach(exercise => {
                const card = this.createExerciseCard(exercise);
                grid.appendChild(card);
            });
        } catch (error) {
            console.error('Error loading exercises:', error);
            const grid = document.getElementById('exercisesGrid');
            if (grid) {
                grid.innerHTML = '<p class="error-message">Error loading exercises. Please try again.</p>';
            }
        }
    }

    createExerciseCard(exercise) {
        const card = document.createElement('div');
        card.className = 'exercise-card';
        card.innerHTML = `
            <div class="exercise-header">
                <h3>${exercise.question}</h3>
                <span class="difficulty-badge ${exercise.difficulty.toLowerCase()}">
                    ${exercise.difficulty}
                </span>
            </div>
            <div class="exercise-meta">
                <span class="category">${exercise.categoryName}</span>
                <span class="language">${exercise.languageName}</span>
            </div>
            <div class="exercise-actions">
                <button onclick="exerciseCreator.editExercise(${exercise.exerciseId})" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button onclick="exerciseCreator.deleteExercise(${exercise.exerciseId})" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        `;
        return card;
    }

    resetForm() {
        document.getElementById('questionText').value = '';
        this.answerBox.innerHTML = '<div class="placeholder">Drag word tiles here to create the correct answer</div>';
        document.querySelector('.word-tiles').innerHTML = '';
    }
}

// Initialize the ExerciseCreator when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.exerciseCreator = new ExerciseCreator();
});
