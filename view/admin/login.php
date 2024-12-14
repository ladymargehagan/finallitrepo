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
    <link rel="stylesheet" href="../../assets/css/admin/styles.css">
    <link rel="stylesheet" href="../../assets/css/admin/login.css">
</head>
<body class="admin-login">
    <div class="login-container">
        <h1>Admin Login</h1>
        <form id="adminLoginForm" class="admin-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <div id="error-message" class="error-message"></div>
    </div>
    <script src="../../assets/js/admin/login.js"></script>
</body>
</html>
