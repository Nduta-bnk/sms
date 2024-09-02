<?php
session_start(); // Start the session

// Check if the user role is set in the session
$user_role = $_SESSION['user_role'] ?? '';

// Destroy all session variables
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

// Redirect based on user role
if ($user_role === 'admin') {
    header("Location: adminlogin.php");
} else {
    header("Location: login.php");
}
exit;
?>
<script>document.addEventListener('DOMContentLoaded', function() {
    // Clear the login flag
    localStorage.removeItem('isLoggedIn');
});
</script>