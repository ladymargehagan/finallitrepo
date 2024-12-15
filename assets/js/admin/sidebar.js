// Logout confirmation
function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = '../../actions/admin/logout.php';
    }
} 