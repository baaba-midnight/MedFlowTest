<?php

include "../includes/config.inc.php";

$sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS fullName FROM medFlow_users WHERE role = 'doctor'";
$result = $conn->query($sql);

$doctors = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

echo json_encode($doctors);

$conn->close();
?>