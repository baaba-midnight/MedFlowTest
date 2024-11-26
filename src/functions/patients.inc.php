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
                p.id,
                p.first_name,
                p.middle_name,
                p.last_name,
                p.date_of_birth,
                FLOOR(DATEDIFF(CURDATE(), p.date_of_birth) / 365.25) AS age,
                p.gender,
                p.admission_date,
                p.`status`,
                p.phone,
                p.address,
                p.notes,
                p.doctor_id
                FROM patients p
                LEFT JOIN medFlow_users u ON p.id = u.id
                WHERE p.id = ?";

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
    // echo $data['fname'];
    try {
        // Validate required fields
        $required = ['fname', 'lname', 'dob', 'gender', 'status'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }

        $fname = trim($data['fname']);
        $mname = trim($data['mname']);
        $lname = trim($data['lname']);
        $address = trim($data['address']) ?? '';
        $phone = trim($data['phone']) ?? '';
        $notes = trim($data['notes']) ?? '';
        $doctor_id = isset($data['doctorDropdownADD']) && $data['doctorDropdownADD'] !== "" ? $data['doctorDropdownADD'] : NULL;
        $is_critical =  $data['is_critical'] == "1" ? TRUE : FALSE;

        echo json_encode($doctor_id);
        
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

        if ($stmt->errno) {
            echo "Binding parameters failed: " . $stmt->error;
        }

        $stmt->bind_param(
            "sssssssssss",
            $fname,
            $mname,
            $lname,
            $data['dob'],
            $data['gender'],
            $address,
            $phone,
            $notes,
            $doctor_id,
            $data['status'],
            $is_critical
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

function updatePatientNotes($conn, $data) {
    try {
        $id = $data['id'];
        $notes = $data['notes'];
        $sql = "UPDATE `patients` SET `notes`= ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $notes, $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update patient notes");
        }

        if ($stmt->affected_rows === 0) {
            throw new Exception("Patient not found");
        }

        $stmt->close();
        return ["message" => "Patient notes updated successfully"];
    } catch (Exception $e) {
        throw new Exception("Failed to update patient notes: " . $e->getMessage());
    }
}

// Main request handling
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Only POST method is allowed", 405);
    }

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    // echo $data;

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

        case 'deletePatient':
            if (empty($data['id'])) {
                throw new Exception("Patient ID is required", 400);
            }
            sendResponse(deletePatient($conn, $data['id']));
            break;
        case 'updatePatientNotes':
            if (empty($data['id'])) {
                throw new Exception("Patient ID is required, 400");
            }
            sendResponse(updatePatientNotes($conn, $data));
            break;
        default:
            throw new Exception("Invalid type specified", 400);
    }
} catch (Exception $e) {
    handleError($e->getMessage(), $e->getCode() ?: 500);
}