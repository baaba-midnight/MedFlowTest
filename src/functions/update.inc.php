<?php
include "../includes/config.inc.php";

function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode([
        'status' => $statusCode === 200 ? 'success' : 'error',
        'data' => $data
    ]);
    exit;
}

function handleError($message, $statusCode = 500) {
    sendResponse(['message' => $message], $statusCode);
}

function updatePatient($conn, $data) {
    try {
        // Ensure the patient ID is provided
        if (empty($data['id'])) {
            throw new Exception("Patient ID is required");
        }

        if (empty($data['fname'] || empty($data['lname']))) {
            echo "<script>alert('Enter Required Fields')</script>";
        }

        $sql = "UPDATE `patients` 
                SET `first_name`=?,
                    `middle_name`=?,
                    `last_name`=?,
                    `date_of_birth`=?,
                    `gender`=?,
                    `address`=?,
                    `phone`=?,
                    `notes`=?,
                    `doctor_id`=?,
                    `status`=?,
                    `is_critical`=?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssssss', $data['fname'], $data['mname'], $data['lname'], $data['dob'], $data['gender'], $data['address'], $data['phone'], $data['notes'], $data['doctor_id'], $data['status'], $data['is_critical']);

        if ($stmt->execute()) {
            return ["message" => "Patient updated successfully"];
        }

        $stmt->close();
        
    } catch (Exception $e) {
        // Handle exceptions and return meaningful error messages
        handleError($e->getMessage());
        return ["error" => $e->getMessage()];
    } 
}

function updateUser($conn, $data) {
    try {
        if (empty($data['id'])) {
            throw new Exception("User ID is required");
        }

        $updates = [];
        $params = [];
        $types = "";

        if (!empty($data['name'])) {
            $updates[] = "username = ?";
            $params[] = $data['name'];
            $types .= "s";
        }

        if (!empty($data['password'])) {
            $updates[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            $types .= "s";
        }

        if (!empty($data['role'])) {
            $updates[] = "role = ?";
            $params[] = $data['role'];
            $types .= "s";
        }

        if (empty($updates)) {
            throw new Exception("No fields to update");
        }

        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $params[] = $data['id'];
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update user");
        }

        $stmt->close();
        return ["message" => "User updated successfully"];
    } catch (Exception $e) {
        throw new Exception("Failed to update user: " . $e->getMessage());
    }
}


// main routing for PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    switch($data['type']) {
        case 'updatePatient':
            sendResponse(updatePatient($conn, $data));
            break;
        case 'updateUser':
            sendResponse(updateUser($conn, $data));
            break;
    }
} 