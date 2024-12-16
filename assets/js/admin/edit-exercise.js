class ExerciseEditor {
    constructor() {
        this.form = document.getElementById('editExerciseForm');
        this.setupEventListeners();
    }

    setupEventListeners() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.updateExercise();
        });

        // Add handler for adding new options
        document.getElementById('addOption').addEventListener('click', () => {
            const optionsContainer = document.getElementById('otherOptions');
            const newOption = document.createElement('div');
            newOption.className = 'option-input';
            newOption.innerHTML = `
                <input type="text" class="word-option">
                <button type="button" class="remove-option">×</button>
            `;
            optionsContainer.appendChild(newOption);
        });

        // Add handler for removing options
        document.getElementById('otherOptions').addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-option')) {
                e.target.parentElement.remove();
            }
        });
    }

    async updateExercise() {
        try {
            const exerciseId = document.getElementById('exerciseId').value;
            const formData = {
                exerciseId: exerciseId,
                languageId: document.getElementById('languageSelect').value,
                categoryId: document.getElementById('categorySelect').value,
                difficulty: document.getElementById('difficultySelect').value,
                questionText: document.getElementById('questionText').value,
                pronunciationText: document.getElementById('pronunciationText').value,
                wordBank: this.getWordBankData()
            };

            const response = await fetch('../../actions/admin/update_exercise.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            if (result.success) {
                // Force a full page reload to ensure fresh data
                window.location.href = 'exercises.php?updated=true';
            } else {
                throw new Error(result.error || 'Failed to update exercise');
            }
        } catch (error) {
            console.error('Error updating exercise:', error);
            alert('Error updating exercise: ' + error.message);
        }
    }

    getWordBankData() {
        const correctAnswer = document.getElementById('correctAnswer').value;
        const otherOptions = Array.from(document.querySelectorAll('.word-option'))
            .map(input => input.value)
            .filter(value => value.trim() !== '');

        return [
            { text: correctAnswer, isAnswer: true },
            ...otherOptions.map(option => ({
                text: option,
                isAnswer: false
            }))
        ];
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ExerciseEditor();
});
