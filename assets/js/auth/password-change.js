document.addEventListener('DOMContentLoaded', () => {
    const passwordForm = document.querySelector('.password-form');
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';
    passwordForm.insertBefore(errorMessage, passwordForm.firstChild);

    // Password validation regex
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    passwordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorMessage.style.display = 'none';
        
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        // Validation checks
        if (!passwordRegex.test(newPassword)) {
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'New password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character';
            return;
        }

        if (newPassword !== confirmPassword) {
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'New passwords do not match';
            return;
        }

        try {
            const response = await fetch('../actions/update_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                })
            });

            const data = await response.json();
            
            if (data.success) {
                errorMessage.style.display = 'block';
                errorMessage.style.color = '#28a745';
                errorMessage.textContent = 'Password updated successfully!';
                passwordForm.reset();
            } else {
                errorMessage.style.display = 'block';
                errorMessage.style.color = '#dc3545';
                errorMessage.textContent = data.error || 'Failed to update password';
            }
        } catch (error) {
            errorMessage.style.display = 'block';
            errorMessage.style.color = '#dc3545';
            errorMessage.textContent = 'An error occurred. Please try again.';
        }
    });
}); 