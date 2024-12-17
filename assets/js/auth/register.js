// Validation regex patterns
const validationPatterns = {
    name: /^[a-zA-Z\s'-]{2,50}$/,
    email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
};

// Real-time validation functions
const validateField = (field, value, pattern) => {
    const isValid = pattern.test(value);
    const input = document.getElementById(field);
    const errorDiv = input.parentElement.querySelector('.field-error') || 
                    createErrorElement(input.parentElement);
    
    if (!isValid) {
        input.classList.add('invalid');
        errorDiv.style.display = 'block';
        errorDiv.textContent = getErrorMessage(field);
    } else {
        input.classList.remove('invalid');
        errorDiv.style.display = 'none';
    }
    return isValid;
};

const createErrorElement = (parent) => {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    parent.appendChild(errorDiv);
    return errorDiv;
};

const getErrorMessage = (field) => {
    const messages = {
        firstName: 'First name should only contain letters, spaces, hyphens, or apostrophes (2-50 characters)',
        lastName: 'Last name should only contain letters, spaces, hyphens, or apostrophes (2-50 characters)',
        email: 'Please enter a valid email address',
        password: 'Password must contain at least 8 characters, including uppercase, lowercase, number and special character'
    };
    return messages[field];
};

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    const errorMessages = document.getElementById('error-messages');
    
    // Add real-time validation listeners
    ['firstName', 'lastName', 'email', 'password'].forEach(field => {
        const input = document.getElementById(field);
        input.addEventListener('input', (e) => {
            validateField(field, e.target.value, 
                field.includes('Name') ? validationPatterns.name :
                validationPatterns[field]);
        });
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorMessages.style.display = 'none';
        errorMessages.innerHTML = '';
        
        const formData = {
            firstName: document.getElementById('firstName').value,
            lastName: document.getElementById('lastName').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        // Validate all fields before submission
        const isFirstNameValid = validateField('firstName', formData.firstName, validationPatterns.name);
        const isLastNameValid = validateField('lastName', formData.lastName, validationPatterns.name);
        const isEmailValid = validateField('email', formData.email, validationPatterns.email);
        const isPasswordValid = validateField('password', formData.password, validationPatterns.password);

        if (!isFirstNameValid || !isLastNameValid || !isEmailValid || !isPasswordValid) {
            errorMessages.style.display = 'block';
            errorMessages.innerHTML = '<p class="error">Please correct the errors before submitting.</p>';
            return;
        }

        try {
            const response = await fetch('../actions/register_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            console.log('Server Response:', data);
            
            if (data.success) {
                window.location.href = 'login.php?registered=true';
            } else {
                errorMessages.style.display = 'block';
                errorMessages.innerHTML = data.errors.map(error => 
                    `<p class="error">${error}</p>`
                ).join('');
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            errorMessages.style.display = 'block';
            errorMessages.innerHTML = '<p class="error">An error occurred. Please try again later.</p>';
        }
    });
}); 