<?php 
include "../includes/config.inc.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $notes = $_POST['notes'];
    $doctor_id = $_POST['doctorDropdownADD'] && $_POST['doctorDropdownADD'] !== "" ? $_POST['doctorDropdownADD'] : NULL;
    $is_critical =  $_POST['is_critical'] == "1" ? TRUE : FALSE;
    $status = $_POST['status'];
        
    $sql = "INSERT INTO patients (
            first_name, 
            middle_name,
            last_name,
            date_of_birth, 
            gender, 
            address, 
            phone, 
            notes, 
            doctor_id, 
            `status`,
            is_critical
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "sssssssssss",
        $fname,
        $mname,
        $lname,
        $dob,
        $gender,
        $address,
        $phone,
        $notes,
        $doctor_id,
        $status,
        $is_critical
    );

    if ($stmt->execute()) {
        echo $_POST['role'];
        if ($_POST['role'] === 'Admin' or $_POST['role'] === 'admin') {
            header('Location: ../views/admin/manage_patients.php');
        } elseif ($_POST['role'] === 'nurse' or $_POST['role'] === 'Nurse') {
            header('Location: ../views/nurse/manage_patients.php');
        }
    }
    $stmt->close();
}
$conn->close();