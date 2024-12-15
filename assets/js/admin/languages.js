document.getElementById('newLanguageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('../../actions/admin/add_language.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Refresh languages dropdown
            location.reload();
        } else {
            alert(data.error || 'Failed to add language');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});

// Modal control functions
function showModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function editLanguage(languageId) {
    // Implement edit functionality
}

function toggleLanguageStatus(languageId) {
    if (confirm('Are you sure you want to change this language\'s status?')) {
        fetch('../../actions/admin/toggle_language_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ languageId: languageId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error updating language status');
            }
        });
    }
}

// Form submission handling
document.getElementById('addLanguageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideModal('newLanguageModal');
            window.location.reload();
        } else {
            alert(data.message || 'Error adding language');
        }
    });
}); 