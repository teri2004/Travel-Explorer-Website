
<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the current session
session_destroy();

// Redirect to admin login page
header("Location: admin_login.php");
exit;
?>
