<aside class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-language"></i>
        <h2>Admin Panel</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="exercises.php" <?php echo basename($_SERVER['PHP_SELF']) == 'exercises.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-dumbbell"></i>
            <span>Exercises</span>
        </a>
        <a href="languages.php" <?php echo basename($_SERVER['PHP_SELF']) == 'languages.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-globe"></i>
            <span>Languages</span>
        </a>
        <a href="users.php" <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'class="active"' : ''; ?>>
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="../../actions/admin/logout.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside> 