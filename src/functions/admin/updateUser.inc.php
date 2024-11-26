<?php
include "../../includes/config.inc.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // $id = (int) $_POST['id'];
    $id = 1;
    $first_name = $_POST['fname'];
    $middle_name = $_POST['mname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashpassword = password_hash($password, PASSWORD_BCRYPT);

    if (empty($id) || empty($first_name) || empty($last_name)) {
        throw new Exception("User ID, first_name, and last_name are required");
    }

    $sql = "UPDATE `users` SET `first_name`= ?,`middle_name`= ?,`last_name`=?,`email`=?,`password`= ?,`role`=? WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $first_name, $middle_name, $last_name, $email, $hashpassword, $role, $id);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update user");
    }

    $stmt->close();
    header("Location: ../../views/admin/manage_users.php");
}