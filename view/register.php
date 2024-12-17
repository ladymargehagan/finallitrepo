<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Language Learning Platform</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <style>
        .field-error {
            color: #dc3545;
            font-size: 0.8em;
            margin-top: 5px;
            display: none;
        }
        
        .invalid {
            border-color: #dc3545 !important;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <h2>Create Account</h2>
        
        <div id="error-messages" class="error-messages" style="display: none;"></div>

        <form id="registerForm" class="auth-form">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" required>
            </div>
            
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <small>Minimum 8 characters, 1 uppercase, 1 number, 1 special character</small>
            </div>
            
            <button type="submit" class="auth-btn">Register</button>
        </form>
        
        <p class="auth-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <footer class="auth-footer">
        <div class="footer-content">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Support</a>
        </div>
    </footer>

    <script src="../assets/js/auth/register.js"></script>
</body>
</html> 