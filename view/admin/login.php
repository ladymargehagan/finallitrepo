<?php
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/admin/login.css">
</head>
<body class="admin-login">
    <div class="login-container">
        <h1>
            <i class="fas fa-user-shield"></i>
            Admin Login
        </h1>
        <form id="adminLoginForm" class="admin-form">
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Email
                </label>
                <input type="email" id="email" name="email" required 
                       placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password">
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Login
            </button>
        </form>
        <div id="error-message" class="error-message"></div>
    </div>
    <script src="../../assets/js/admin/login.js"></script>
</body>
</html>
