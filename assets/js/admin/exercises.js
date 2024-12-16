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
        this.setupSearchFunctionality();
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

    async saveExercise() {
        try {
            const questionText = document.getElementById('questionText').value;
            const pronunciationText = document.getElementById('pronunciationText').value;
            const languageId = document.getElementById('languageSelect').value;
            const categoryId = document.getElementById('categorySelect').value;
            const difficulty = document.getElementById('difficultySelect').value;

            // Get word bank items and identify the correct answer
            const wordBank = [];
            const answerBox = document.getElementById('answerBox');
            const wordTiles = document.querySelector('.word-tiles');

            // Get the answer from the answer box
            if (answerBox.children.length > 0) {
                const answerTile = answerBox.children[0];
                wordBank.push({
                    text: answerTile.textContent,
                    isAnswer: true
                });
            }

            // Get remaining words from word tiles
            Array.from(wordTiles.children).forEach(tile => {
                wordBank.push({
                    text: tile.textContent,
                    isAnswer: false
                });
            });

            if (!questionText || !languageId || !categoryId || !difficulty || wordBank.length === 0) {
                throw new Error('Please fill in all required fields and add words to the word bank');
            }

            const exerciseData = {
                question: questionText,
                pronunciation: pronunciationText,
                languageId: parseInt(languageId),
                categoryId: parseInt(categoryId),
                difficulty,
                wordBank
            };

            const response = await fetch('../../actions/admin/save_exercise.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(exerciseData)
            });

            if (!response.ok) {
                throw new Error('Failed to save exercise');
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
                <h3>${exercise.questionText}</h3>
                <span class="difficulty-badge ${exercise.difficulty.toLowerCase()}">
                    ${exercise.difficulty}
                </span>
            </div>
            <div class="exercise-meta">
                <span class="category">${exercise.categoryName}</span>
                <span class="language">${exercise.languageName}</span>
            </div>
            <div class="exercise-actions">
                <button class="btn btn-danger delete-btn" data-id="${exercise.exerciseId}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        `;

        // Add event listeners to the buttons
        const deleteBtn = card.querySelector('.delete-btn');

        deleteBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to delete this exercise?')) {
                deleteExercise(exercise.exerciseId);
            }
        });

        const actionButtons = document.createElement('div');
        actionButtons.className = 'card-actions';
        
        const editButton = document.createElement('a');
        editButton.href = `edit-exercise.php?id=${exercise.exerciseId}`;
        editButton.className = 'btn btn-edit';
        editButton.innerHTML = '<i class="fas fa-edit"></i> Edit';
        
        actionButtons.appendChild(editButton);
        actionButtons.appendChild(deleteBtn);
        card.appendChild(actionButtons);
        
        card.setAttribute('data-exercise-id', exercise.exerciseId);
        
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

    setupSearchFunctionality() {
        const searchInput = document.getElementById('exerciseSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const exerciseCards = document.querySelectorAll('.exercise-card');
                
                exerciseCards.forEach(card => {
                    const question = card.querySelector('h3').textContent.toLowerCase();
                    const category = card.querySelector('.category').textContent.toLowerCase();
                    const language = card.querySelector('.language').textContent.toLowerCase();
                    
                    const matches = question.includes(searchTerm) || 
                                  category.includes(searchTerm) || 
                                  language.includes(searchTerm);
                                      
                    card.style.display = matches ? 'flex' : 'none';
                });
            });
        }
    }
}

// Initialize the class
const exerciseCreator = new ExerciseCreator();

// Add these event listeners and functions
document.addEventListener('DOMContentLoaded', function() {
    const deleteCategoryModal = document.getElementById('deleteCategoryModal');
    const deleteCategoryBtn = document.getElementById('deleteCategoryBtn');
    const categorySelect = document.getElementById('categoryToDelete');
    const confirmCategoryDelete = document.getElementById('confirmCategoryDelete');
    const warningMessage = deleteCategoryModal.querySelector('.warning-message');

    // Open delete category modal
    deleteCategoryBtn.addEventListener('click', function() {
        deleteCategoryModal.style.display = 'block';
    });

    // Close modal when clicking the X or Cancel
    deleteCategoryModal.querySelector('.close').addEventListener('click', function() {
        deleteCategoryModal.style.display = 'none';
    });

    deleteCategoryModal.querySelector('.close-modal').addEventListener('click', function() {
        deleteCategoryModal.style.display = 'none';
    });

    // Check category usage when selected
    categorySelect.addEventListener('change', async function() {
        const categoryId = this.value;
        if (categoryId) {
            try {
                const response = await fetch(`../../actions/admin/check_category_usage.php?categoryId=${categoryId}`);
                const data = await response.json();
                
                if (data.exerciseCount > 0) {
                    warningMessage.style.display = 'block';
                    warningMessage.querySelector('span').textContent = 
                        `This category contains ${data.exerciseCount} exercises that will also be deleted.`;
                } else {
                    warningMessage.style.display = 'none';
                }
            } catch (error) {
                console.error('Error checking category usage:', error);
            }
        }
    });

    // Handle category deletion
    confirmCategoryDelete.addEventListener('click', async function() {
        const categoryId = categorySelect.value;
        if (!categoryId) {
            alert('Please select a category to delete');
            return;
        }

        if (!confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch('../../actions/admin/delete_category.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `categoryId=${categoryId}`
            });

            const data = await response.json();
            
            if (data.success) {
                alert('Category deleted successfully');
                location.reload(); // Reload page to update categories
            } else {
                alert('Error deleting category: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while deleting the category');
        }

        deleteCategoryModal.style.display = 'none';
    });
});

function deleteExercise(exerciseId) {
    fetch('../../actions/admin/delete_exercise.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ exerciseId: exerciseId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the exercise card from the UI
            const exerciseCard = document.querySelector(`[data-exercise-id="${exerciseId}"]`);
            if (exerciseCard) {
                exerciseCard.remove();
            }
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
