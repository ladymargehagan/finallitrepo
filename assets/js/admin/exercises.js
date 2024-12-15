class ExerciseCreator {
    constructor() {
        this.init();
        this.loadExistingExercises();
        this.setupWordBank();
        this.setupEventListeners();
        
        // Add category initialization
        this.setupNewCategoryModal();
        const newCategoryBtn = document.getElementById('newCategoryBtn');
        if (newCategoryBtn) {
            newCategoryBtn.addEventListener('click', () => {
                const modal = document.getElementById('newCategoryModal');
                if (modal) modal.style.display = 'block';
            });
        }
    }

    init() {
        this.setupExistingExercisesFilter();
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
        // First remove any existing modal
        const existingModal = document.getElementById('newCategoryModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create new modal
        const modal = document.createElement('div');
        modal.id = 'newCategoryModal';
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Add New Category</h3>
                <form id="newCategoryForm" class="category-form">
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" 
                               id="categoryName" 
                               name="categoryName"
                               class="form-control" 
                               placeholder="Enter category name" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="categoryDescription">Description</label>
                        <textarea id="categoryDescription" 
                                name="categoryDescription"
                                class="form-control" 
                                rows="3" 
                                placeholder="Enter category description"></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" id="cancelCategory">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(modal);

        // Set up event listeners after the modal is added to the DOM
        const form = document.getElementById('newCategoryForm');
        const closeBtn = modal.querySelector('.close');
        const cancelBtn = document.getElementById('cancelCategory');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = {
                categoryName: document.getElementById('categoryName').value.trim(),
                categoryDescription: document.getElementById('categoryDescription').value.trim()
            };
            this.handleNewCategory(formData);
        });

        closeBtn.addEventListener('click', () => this.closeModal());
        cancelBtn.addEventListener('click', () => this.closeModal());

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeModal();
            }
        });
    }

    handleNewCategory(formData) {
        if (!formData.categoryName) {
            alert('Please enter a category name');
            return;
        }

        fetch('../../actions/admin/add_category.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new option to category select
                const select = document.getElementById('categorySelect');
                const option = document.createElement('option');
                option.value = data.categoryId;
                option.textContent = data.categoryName;
                select.appendChild(option);
                
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'alert alert-success';
                successMessage.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <span>Category "${data.categoryName}" added successfully!</span>
                `;
                document.querySelector('.quick-actions-panel').appendChild(successMessage);

                // Remove success message after 3 seconds
                setTimeout(() => {
                    successMessage.remove();
                }, 3000);

                // Close and reset modal
                this.closeModal();
            } else {
                throw new Error(data.error || 'Failed to add category');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding category: ' + error.message);
        });
    }

    closeModal() {
        const modal = document.getElementById('newCategoryModal');
        if (modal) {
            modal.style.display = 'none';
            const form = document.getElementById('newCategoryForm');
            if (form) {
                form.reset();
            }
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

// Initialize the class
const exerciseCreator = new ExerciseCreator();
