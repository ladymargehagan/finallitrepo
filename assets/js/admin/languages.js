// Modal handling
const modal = document.getElementById('languageModal');
const form = document.getElementById('languageForm');
const closeBtn = document.querySelector('.close');

// Show modal for adding new language
function showAddLanguageModal() {
    document.getElementById('modalTitle').textContent = 'Add New Language';
    form.reset();
    document.getElementById('languageId').value = '';
    form.removeAttribute('data-edit');
    modal.style.display = 'block';
}

// Hide modal
function hideModal() {
    modal.style.display = 'none';
}

// Close modal when clicking (X)
closeBtn.onclick = hideModal;

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == modal) {
        hideModal();
    }
}

// Edit language
function editLanguage(language) {
    document.getElementById('modalTitle').textContent = 'Edit Language';
    document.getElementById('languageId').value = language.languageId;
    document.getElementById('languageName').value = language.languageName;
    document.getElementById('level').value = language.level;
    document.getElementById('description').value = language.description;
    form.setAttribute('data-edit', 'true');
    modal.style.display = 'block';
}

// Form submission handler
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const isEdit = form.hasAttribute('data-edit');
    const formData = new FormData(form);
    formData.append('action', isEdit ? 'edit' : 'add');

    try {
        const response = await fetch('../../actions/admin/manage_languages.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            hideModal();
            // Show success message
            alert(isEdit ? 'Language updated successfully!' : 'New language added successfully!');
            location.reload(); // Refresh to show changes
        } else {
            alert('Operation failed: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Operation failed: ' + error.message);
    }
});

// Toggle language status (activate/deactivate)
async function toggleLanguage(languageId, currentStatus) {
    const confirmMessage = currentStatus ? 
        'Are you sure you want to deactivate this language?' : 
        'Are you sure you want to activate this language?';
    
    if (!confirm(confirmMessage)) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'toggle');
    formData.append('languageId', languageId);
    formData.append('active', currentStatus ? 0 : 1);

    try {
        const response = await fetch('../../actions/admin/manage_languages.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            location.reload(); // Refresh to show changes
        } else {
            alert('Failed to update language status: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update language status: ' + error.message);
    }
}

// Add error handling for the close button
document.querySelectorAll('.close-modal').forEach(button => {
    button.onclick = hideModal;
});

// Prevent form submission on enter key
form.onkeypress = function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
    }
};