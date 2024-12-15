// Modal handling
const modal = document.getElementById('languageModal');
const form = document.getElementById('languageForm');

function showAddLanguageModal() {
    document.getElementById('modalTitle').textContent = 'Add New Language';
    form.reset();
    form.removeAttribute('data-edit');
    modal.style.display = 'block';
}

function hideModal() {
    modal.style.display = 'none';
}

function editLanguage(language) {
    document.getElementById('modalTitle').textContent = 'Edit Language';
    document.getElementById('languageId').value = language.languageId;
    document.getElementById('languageName').value = language.languageName;
    document.getElementById('level').value = language.level;
    document.getElementById('description').value = language.description;
    form.setAttribute('data-edit', 'true');
    modal.style.display = 'block';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == modal) {
        hideModal();
    }
}

// Form submission
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
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Operation failed: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        alert('Operation failed: ' + error.message);
    }
});

// Toggle language status
async function toggleLanguage(languageId, currentStatus) {
    if (!confirm(`Are you sure you want to ${currentStatus ? 'deactivate' : 'activate'} this language?`)) {
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
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to update language status');
        }
    } catch (error) {
        alert('Operation failed: ' + error.message);
    }
} 
}); 