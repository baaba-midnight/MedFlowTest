<?php
// Check if the user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect the user to the dashboard or other protected page

    if ($_SESSION['id'] === 'admin') {
        header('Location: views/admin/dashboard.php');
    } elseif ($_SESSION['id'] === 'doctor') {
        header('Location: views/doctor/dashboard.php');
    } else if ($_SESSION['id'] === 'nurse') {
        header('Location: views/nurse/manage_patients.php');
    }
    exit;
}

// Redirect the user to the login page
header('Location: src/auth/login.php');
exit;