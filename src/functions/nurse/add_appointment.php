<?php
include "../../includes/config.inc.php";

// Set headers for JSON response and CORS if needed
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    $patientId = (int) $data['patientId'];
    $doctorId = (int) $data['doctorAssign'];
    $status = 'in_progress';

    // update the patient's table with respective doctor_id
    $sql = "UPDATE `patients` 
        SET `doctor_id`= ?
        WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $doctorId, $patientId);
    $stmt->execute();

    $sql = "INSERT INTO `appointments`(`patient_id`, `doctor_id`, `status`) VALUES (?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $patientId, $doctorId, $status);
    $stmt->execute();

    $statusCode = 200;
    http_response_code($statusCode);
    echo json_encode([
        "status" => $statusCode === 200 ? 'success' : 'error',
        "message" => "Appointment added successfully"
    ]);
} else {
    $statusCode = 400;
    echo json_encode([
        "status" => $statusCode === 200 ? 'success' : 'error',
        "message" => "Not found"
    ]);
}