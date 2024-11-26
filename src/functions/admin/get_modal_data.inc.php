<?php
require_once '../../includes/config.inc.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function sendResponse($data, $statusCode=200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function handleError($e) {
    sendResponse(['error' => 'Database error: ' . $e->getMessage()], 500);
}

// get active patients details
function getPatients() {
    global $conn;

    try {
        $query = "SELECT
                    p.id,
                    CONCAT(p.first_name, ' ', p.last_name) as `name`,
                    p.admission_date,
                    CONCAT(u.first_name, ' ', u.last_name) as doctor_name
                FROM patients p
                JOIN appointments a ON p.id = a.patient_id
                JOIN users u ON u.id = a.doctor_id
                WHERE p.status = 'inpatient' OR p.status = 'outpatient'
                ORDER BY p.admission_date DESC;";

        $stmt = $conn->prepare($query);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            $patientActive = [];
                
            while($row = $result->fetch_assoc()) {
                $patientActive[] = [
                    'patientId' => $row['id'],
                    'name' => $row['name'],
                    'admissionDate' => $row['admission_date'],
                    'doctor' => $row['doctor_name']
                ];
            }
            
            return $patientActive;
        }
    } catch (Exception $e) {
        handleError($e);
    }
}

function getCriticalPatients() {
    global $conn;
    try {
        // get critical patients
        $query = "SELECT
                    patients.id,
                    CONCAT(patients.first_name, ' ', patients.last_name) as `name`
                FROM patients
                WHERE patients.is_critical = TRUE";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $patientCritical = [];
        while($row = $result->fetch_assoc()) {
            $patientCritical[] = [
                'patientId' => $row['id'],
                'name' => $row['name']
            ];
        } 

        return $patientCritical;
    } catch (Exception $e) {
        handleError($e);
    }
}

// get appointment data
function getAppointments() {
    global $conn;

    try {
        $query = "SELECT
                    a.created_at,
                    CONCAT(p.first_name,' ', p.last_name)  as patient_name,
                    CONCAT(u.first_name, ' ', u.last_name) as doctor_name,
                    a.`status`
                FROM appointments a
                JOIN users u ON u.id = a.doctor_id
                JOIN patients p ON p.id = a.patient_id
                WHERE a.`status` = 'in_progress'";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $patientAppointments = [];
    
        while($row = $result->fetch_assoc()) {
            $patientAppointments[] = [
                'date' => $row['created_at'],
                'patient' => $row['patient_name'],
                'doctor' => $row['doctor_name'],
                'status' => $row['status']
            ];
        } 
        return $patientAppointments;
    } catch (Exception $e) {
        handleError($e);
    }
}

function getStaff() {
    global $conn;

    try {
        $query = "SELECT
                    CONCAT(u.first_name, ' ', u.last_name) AS username,
                    u.`role`
                FROM Users u
                WHERE role = 'doctor' OR role = 'nurse';";
            
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $staff = [];
        while($row = $result->fetch_assoc()) {
            $staff[] = [
                'name' => $row['username'],
                'role' => $row['role']
            ];
        }

        return $staff;
    } catch (Exception $e) {
        handleError($e);
    }
}

// maing routing
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $type = $_GET['type'] ?? '';

    switch($type) {
        case 'patients':
            sendResponse(getPatients());
            break;
        case 'critical':
            sendResponse(getCriticalPatients());
            break;
        case 'staff':
            sendResponse(getStaff());
            break;
        case 'appointments':
            sendResponse(getAppointments());
            break;
        default:
            sendResponse(['error' => 'Invalid type specified'], 400);
    }
} else {
    sendResponse(['error' => 'Invalid request method'], 405);
}