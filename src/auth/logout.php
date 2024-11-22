<?php
session_start();

session_unset();     // Unset all session variables
session_destroy();   // Destroy the session
setcookie(session_name(), '', time() - 3600, '/'); // Clear the session cookie

// redirect to the login page
header("Location: login.php");
exit();
?>