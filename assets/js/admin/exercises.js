class ExerciseCreator {
    constructor() {
        this.init();
        this.loadExistingExercises();
        this.setupWordBank();
        this.setupEventListeners();
    }

    init() {
        this.setupExistingExercisesFilter();
        this.setupNewCategoryModal();
    }

    setupWordBank() {
        const addWordBtn = document.querySelector('.add-word-btn');
        const wordInput = document.querySelector('.word-input');
        const wordTiles = document.querySelector('.word-tiles');

        if (addWordBtn && wordInput && wordTiles) {
            addWordBtn.addEventListener('click', () => {
                const word = wordInput.value.trim();
                if (word) {
                    const tile = document.createElement('div');
                    tile.className = 'word-tile';
                    tile.draggable = true;
                    tile.textContent = word;
                    wordTiles.appendChild(tile);
                    wordInput.value = '';
                    this.setupDragAndDrop(tile);
                }
            });

            // Also allow Enter key to add words
            wordInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    addWordBtn.click();
                }
            });
        }
    }

    setupDragAndDrop(tile) {
        tile.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', tile.textContent);
            tile.classList.add('dragging');
        });

        tile.addEventListener('dragend', () => {
            tile.classList.remove('dragging');
        });

        const answerBox = document.getElementById('answerBox');
        if (answerBox) {
            answerBox.addEventListener('dragover', (e) => {
                e.preventDefault();
            });

            answerBox.addEventListener('drop', (e) => {
                e.preventDefault();
                
                // Clear placeholder if it exists
                const placeholder = answerBox.querySelector('.placeholder');
                if (placeholder) {
                    placeholder.remove();
                }

                const text = e.dataTransfer.getData('text/plain');
                // Find the tile by iterating through word-tiles and matching text content
                const draggedTile = Array.from(document.querySelectorAll('.word-tile'))
                    .find(tile => tile.textContent.trim() === text);
                
                if (draggedTile) {
                    answerBox.appendChild(draggedTile);
                }
            });
        }
    }

    setupNewCategoryModal() {
        const showModalBtn = document.querySelector('[onclick="showModal(\'newCategoryModal\')"]');
        if (showModalBtn) {
            showModalBtn.addEventListener('click', () => this.showNewCategoryModal());
        }
    }

    showNewCategoryModal() {
        // Create modal if it doesn't exist
        let modal = document.getElementById('newCategoryModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'newCategoryModal';
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <h3>Add New Category</h3>
                    <form id="newCategoryForm">
                        <div class="form-group">
                            <input type="text" id="categoryName" placeholder="Category Name" required>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="exerciseCreator.closeModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Category</button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);

            // Setup form submission
            const form = modal.querySelector('#newCategoryForm');
            form.addEventListener('submit', (e) => this.handleNewCategory(e));
        }
        modal.style.display = 'block';
    }

    closeModal() {
        const modal = document.getElementById('newCategoryModal');
        if (modal) modal.style.display = 'none';
    }

    async handleNewCategory(e) {
        e.preventDefault();
        const categoryName = document.getElementById('categoryName').value;
        
        try {
            const response = await fetch('../../actions/admin/add_category.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ categoryName })
            });

            const data = await response.json();
            if (data.success) {
                // Refresh category select options
                const select = document.getElementById('categorySelect');
                const option = document.createElement('option');
                option.value = data.categoryId;
                option.textContent = categoryName;
                select.appendChild(option);
                this.closeModal();
            } else {
                alert(data.error || 'Failed to add category');
            }
        } catch (error) {
            console.error('Error adding category:', error);
            alert('Failed to add category');
        }
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
        const saveButton = document.getElementById('saveExercise');
        console.log('Save button found:', saveButton);
        if (saveButton) {
            saveButton.addEventListener('click', (e) => {
                console.log('Save button clicked');
                e.preventDefault();
                this.saveExercise();
            });
        } else {
            console.error('Save button not found in DOM');
        }
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
        try {
            const questionText = document.getElementById('questionText').value;
            const languageId = document.getElementById('languageSelect').value;
            const categoryId = document.getElementById('categorySelect').value;
            const difficulty = document.getElementById('difficultySelect').value;
            
            // Get answer box and word tiles
            const answerBox = document.getElementById('answerBox');
            const wordTiles = document.querySelector('.word-tiles').children;
            
            // Get answer tiles (correct answer/translation)
            const answerTiles = Array.from(answerBox.children)
                .filter(el => el.classList.contains('word-tile'));
            
            if (!answerTiles.length) {
                alert('Please create an answer by dragging words to the answer box');
                return;
            }

            const translation = answerTiles[0].textContent.trim();

            // Create word bank array
            const wordBank = [
                // The correct answer/translation
                {
                    text: translation,
                    isAnswer: true
                },
                // The distractors
                ...Array.from(wordTiles).map(tile => ({
                    text: tile.textContent.trim(),
                    isAnswer: false
                }))
            ];

            const exerciseData = {
                question: questionText,
                translation: translation, // Add translation explicitly
                languageId: parseInt(languageId),
                categoryId: parseInt(categoryId),
                difficulty,
                wordBank
            };

            console.log('Sending exercise data:', exerciseData);

            const response = await fetch('../../actions/admin/save_exercise.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(exerciseData)
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server response:', errorText);
                throw new Error(`Server returned ${response.status}: ${errorText}`);
            }
            
            const result = await response.json();
            if (result.success) {
                alert('Exercise saved successfully!');
                await this.loadExistingExercises();
                this.resetForm();
            } else {
                throw new Error(result.error || 'Failed to save exercise');
            }
        } catch (error) {
            console.error('Error saving exercise:', error);
            alert('Error saving exercise: ' + error.message);
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
                <button class="btn btn-secondary edit-btn" data-id="${exercise.exerciseId}">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger delete-btn" data-id="${exercise.exerciseId}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        `;

        // Add event listeners to the buttons
        const editBtn = card.querySelector('.edit-btn');
        const deleteBtn = card.querySelector('.delete-btn');

        editBtn.addEventListener('click', () => {
            // Get the exercise data and populate the form
            fetch(`../../actions/admin/get_single_exercise.php?id=${exercise.exerciseId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the main form instead of a modal
                    document.getElementById('questionText').value = data.question_text;
                    document.getElementById('languageSelect').value = data.languageId;
                    document.getElementById('categorySelect').value = data.categoryId;
                    document.getElementById('difficultySelect').value = data.difficulty;

                    // Clear and populate word bank
                    const wordTiles = document.querySelector('.word-tiles');
                    wordTiles.innerHTML = '';
                    const answerBox = document.getElementById('answerBox');
                    answerBox.innerHTML = '';

                    // Add word bank tiles
                    data.wordBank.forEach(word => {
                        const tile = document.createElement('div');
                        tile.className = 'word-tile';
                        tile.draggable = true;
                        tile.textContent = word.segment_text;

                        if (word.is_answer) {
                            // Place answer in answer box
                            const answerTile = tile.cloneNode(true);
                            answerBox.appendChild(answerTile);
                        } else {
                            // Place other words in word bank
                            wordTiles.appendChild(tile);
                        }
                    });

                    // Update save button to indicate editing mode
                    const saveBtn = document.getElementById('saveExercise');
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Update Exercise';
                    saveBtn.dataset.editId = exercise.exerciseId;

                    // Scroll to the form
                    document.querySelector('.exercise-form').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading exercise data');
                });
        });

        deleteBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to delete this exercise?')) {
                fetch('../../actions/admin/delete_exercise.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ exerciseId: exercise.exerciseId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        card.remove(); // Remove the card from the UI
                        alert('Exercise deleted successfully');
                    } else {
                        throw new Error(data.error || 'Failed to delete exercise');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting exercise');
                });
            }
        });

        return card;
    }

    resetForm() {
        const questionText = document.getElementById('questionText');
        const answerBox = document.getElementById('answerBox');
        const wordTiles = document.querySelector('.word-tiles');

        if (questionText) questionText.value = '';
        if (answerBox) {
            answerBox.innerHTML = '<div class="placeholder">Drag word tiles here to create the correct answer</div>';
        }
        if (wordTiles) wordTiles.innerHTML = '';
    }
}

// Initialize the ExerciseCreator when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.exerciseCreator = new ExerciseCreator();
});
