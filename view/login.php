<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Language Learning Platform</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <h2>Login</h2>
        
        <?php if (isset($_GET['registered'])): ?>
            <div class="success-message">
                <p>Registration successful! Please login.</p>
            </div>
        <?php endif; ?>

        <div id="error-messages" class="error-messages" style="display: none;"></div>

        <form id="loginForm" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="auth-btn">Login</button>
        </form>
        
        <p class="auth-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            fetch('../actions/login_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                const errorDiv = document.getElementById('error-messages');
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    errorDiv.style.display = 'block';
                    errorDiv.innerHTML = data.errors.map(error => 
                        `<p class="error">${error}</p>`
                    ).join('');
                }
            });
        });
    </script>

    <footer class="auth-footer">
        <div class="footer-content">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Support</a>
        </div>
    </footer>
</body>
</html>
