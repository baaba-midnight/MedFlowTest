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
    // Check if role is not 2, then redirect to login
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../../auth/login.php');
        exit();
    }

    // If user is logged in and has an appropriate role
    $userId = $_SESSION['id'];
    $fname = $_SESSION['first_name'];
    $lname = $_SESSION['last_name'];
    $role = $_SESSION['role'];
}
