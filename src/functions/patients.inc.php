<?php
// patients.inc.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../includes/config.inc.php";

// Set headers for JSON response and CORS if needed
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

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

function getPatients($conn) {
    try {
        $sql = "SELECT 
                id,
                first_name,
                middle_name,
                last_name,
                FLOOR(DATEDIFF(CURDATE(), date_of_birth) / 365.25) AS age,
                gender,
                admission_date,
                `status`
                FROM patients";

        $stmt = $conn->prepare($sql);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute query");
        }

        $result = $stmt->get_result();
        $patients = [];

        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }

        $stmt->close();
        return $patients;
    } catch (Exception $e) {
        throw new Exception("Failed to fetch patients: " . $e->getMessage());
    }
}

function getPatientById($conn, $id) {
    try {
        $sql = "SELECT 
                id,
                first_name,
                middle_name,
                last_name,
                date_of_birth,
                FLOOR(DATEDIFF(CURDATE(), date_of_birth) / 365.25) AS age,
                gender,
                admission_date,
                `status`,
                phone,
                address,
                notes,
                doctor_id
                FROM patients 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute query");
        }

        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        
        if (!$patient) {
            throw new Exception("Patient not found");
        }

        $stmt->close();
        return $patient;
    } catch (Exception $e) {
        throw new Exception("Failed to fetch patient: " . $e->getMessage());
    }
}

function addPatient($conn, $data) {
    try {
        // Validate required fields
        $required = ['fname', 'lname', 'dob', 'gender', 'status'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
        
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
                admission_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssss",
            trim($data['fname']),
            trim($data['mname']),
            trim($data['lname']),
            $data['dob'],
            $data['gender'],
            trim($data['address']) ?? '',
            trim($data['phone']) ?? '',
            trim($data['notes']) ?? '',
            $data['doctor_id'] ?? NULL,
            $data['status']
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to add patient");
        }

        $newId = $stmt->insert_id;
        $stmt->close();
        
        return ["message" => "Patient added successfully", "id" => $newId];
    } catch (Exception $e) {
        throw new Exception("Failed to add patient: " . $e->getMessage());
    }
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
            echo "<script>alert('Patient Updated Successfully')</script>";
        }

        $stmt->close();
        return ["message" => "Patient updated successfully"];
    } catch (Exception $e) {
        // Handle exceptions and return meaningful error messages
        return ["error" => $e->getMessage()];
    } 
}

function deletePatient($conn, $id) {
    try {
        $sql = "DELETE FROM patients WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete patient");
        }

        if ($stmt->affected_rows === 0) {
            throw new Exception("Patient not found");
        }

        $stmt->close();
        return ["message" => "Patient deleted successfully"];
    } catch (Exception $e) {
        throw new Exception("Failed to delete patient: " . $e->getMessage());
    }
}

// Main request handling
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Only POST method is allowed", 405);
    }

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON data", 400);
    }

    if (empty($data['type'])) {
        throw new Exception("Type parameter is required", 400);
    }

    switch ($data['type']) {
        case 'getPatients':
            sendResponse(getPatients($conn));
            break;

        case 'getPatientById':
            if (empty($data['id'])) {
                throw new Exception("Patient ID is required", 400);
            }
            sendResponse(getPatientById($conn, $data['id']));
            break;

        case 'addPatient':
            sendResponse(addPatient($conn, $data));
            break;

        case 'updatePatient':
            sendResponse(updatePatient($conn, $data));
            break;

        case 'deletePatient':
            if (empty($data['id'])) {
                throw new Exception("Patient ID is required", 400);
            }
            sendResponse(deletePatient($conn, $data['id']));
            break;

        default:
            throw new Exception("Invalid type specified", 400);
    }
} catch (Exception $e) {
    handleError($e->getMessage(), $e->getCode() ?: 500);
}