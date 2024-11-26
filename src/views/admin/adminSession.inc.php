<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (session_start() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    // Redirect to login if user is not logged in
    header('Location: ../../auth/login.php');
    exit();
} else {
    // Check if role is not admin, then redirect to login
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../../auth/login.php');
        exit();
    }

    // If user is logged in, assign the appropriate role variables
    $userId = $_SESSION['id'];
    $username = $_SESSION['full_name'];
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
}
