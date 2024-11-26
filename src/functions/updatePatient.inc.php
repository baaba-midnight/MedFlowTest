<?php
include "../includes/config.inc.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id = (int) $_POST['id'];
    $first_name = $_POST['fname'];
    $middle_name = $_POST['mname'];
    $last_name = $_POST['lname'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $doctorId = $_POST['doctorDropdown'];
    $status = $_POST['status'];
    $address = $_POST['address'];
    $notes =  $_POST['notes'];
    $isCritical = $_POST['is_critical'];


    if (empty($id) || empty($first_name) || empty($last_name)) {
        throw new Exception("User ID, first_name, and last_name are required");
    }

    $sql = "UPDATE `patients` SET `first_name`= ?,`middle_name`= ?,`last_name`= ?,`date_of_birth`= ?,`gender`= ?, `address`= ?,`phone`= ?,`notes`= ?,`doctor_id`= ?,`status`= ?,`is_critical`= ? WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssssi', $first_name, $middle_name, $last_name, $dob, $gender, $address, $phone, $notes, $doctorId, $status, $isCrtical, $id);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update patient");
    }

    $stmt->close();
    echo json_encode(["message" => "Patient updated successfully"]);
}