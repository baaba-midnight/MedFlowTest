<?php 

include "../includes/config.inc.php";

header("Content-Type: application/json");

function sendResponse($data, $statusCode=200) {
    http_response_code($statusCode);
    
    if ($data === "success") {
        echo $data;
    } else {
        echo json_encode($data);
    }
    exit;
}

function handleError($e) {
    sendResponse(['error' => 'Database error: ' . $e->GETMessage()], 500);
}

function getPatients($conn) {
    try {
        $sql = "SELECT 
                id,
                `name`,
                FLOOR(DATEDIFF(CURDATE(), date_of_birth) / 365.25) AS age,
                gender,
                admission_date,
                `status`
                FROM patients;";

        $stmt = $conn->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->GET_result();
            $patients = [];

            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }

            if (empty($patients)) {
                return "No patients found";
            }

            $stmt->close();
            return $patients;
        }
        $conn->close();
    } catch (Exception $e) {
        handleError($e);
        $stmt->close();
        $conn->close();
    }
}

function getPatientById($conn, $patient_id) {
    try {
        $sql = "SELECT 
                id,
                `name`,
                FLOOR(DATEDIFF(CURDATE(), date_of_birth) / 365.25) AS age,
                gender,
                admission_date,
                `status`,
                phone,
                notes
                FROM patients
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $patient_id = (int) $patient_id;
        $stmt->bind_param('i', $patient_id);

        if ($stmt->execute()) {
            $result = $stmt->GET_result();
            $patient = $result->fetch_assoc();

            if (!$patient) {
                throw new Exception("No patient found with specified ID");
            }

            $stmt->close();
            return $patient;
        }
        $conn->close();
    } catch (Exception $e) {
        handleError($e);
        $stmt->close();
        $conn->close();
    }
}

function addPatient($conn, $name, $date_of_birth, $gender, $address, $phone, $notes, $doctor_id, $status) {
    if (empty($name) || empty($date_of_birth) || empty($status)) {
        throw new Exception("Fill all required fields");
    }

    $date_of_birth = date('Y-m-d', strtotime($date_of_birth));
    $sql = "INSERT INTO patients(`name`, date_of_birth, gender, `address`, phone, notes, doctor_id, `status`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssssssis',
        $name,
        $date_of_birth,
        $gender,
        $address,
        $phone,
        $notes,
        $doctor_id,
        $status
    );

    if ($stmt->execute()) {
        echo "success";
    } else  {
        echo "error_add";
    }

    $stmt->close();
    $conn->close();
}

function deletePatient($conn, $patient_id) {
    if (empty($patient_id)) {
        error_log("No recieved id");
        echo "error_fields";
        exit;
    }

    $query = "DELETE FROM patients WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $patient_id);

    if ($stmt->execute()) {
        return "success";
    } else  {
        return "error_delete";
    }
    $stmt->close();
    $conn->close();
}

function updatePatient($conn, $patient_id, $name = null, $date_of_birth = null, $gender = null, $address = null, $phone = null, $notes=null, $doctor_id, $status=null) {
    try {
        // Start building the SQL query
        $sql = "UPDATE patients SET ";
        $fields = [];
        $params = [];
        $types = ""; // Parameter types for binding

        if (!is_null($name)) {
            $fields[] = "name = ?";
            $params[] = $name;
            $types .= "s";
        }

        if (!is_null($date_of_birth)) {
            $fields[] = "date_of_birth = ?";
            $params[] = $date_of_birth;
            $types .= "s";
        }

        if (!is_null($gender)) {
            $fields[] = "gender = ?";
            $params[] = $gender;
            $types .= "s";
        }

        if (!is_null($address)) {
            $fields[] = "address = ?";
            $params[] = $address;
            $types .= "s";
        }

        if (!is_null($phone)) {
            $fields[] = "phone = ?";
            $params[] = $phone;
            $types .= "s";
        }

        if (!is_null($doctor_id)) {
            $fields[] = "doctor_id = ?";
            $params[] = $doctor_id;
            $types .= "s";
        }

        if (!is_null($notes)) {
            $fields[] = "notes = ?";
            $params[] = $notes;
            $types .= "s";
        }

        if (!is_null($status)) {
            $fields[] = "status = ?";
            $params[] = $status;
            $types .= "s";
        }

        $sql .= implode(", ", $fields) . " WHERE id = ?";
        $params[] = $patient_id; 
        $types .= "i";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        // Bind parameters dynamically
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();
        return "success";
    } catch (Exception $e) {
        handleError($e);
        $stmt->close();
        $conn->close();
    }
}

// main routing
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $type = $_GET['type'] ?? '';

    switch ($type) {
        case 'addPatient':
            $name = $_GET['fname'] . ' ' . $_GET['mname'] . ' ' . $_GET['lname'];
            sendResponse(addPatient($conn, $name, $_GET['dob'], $_GET['gender'], $_GET['address'], $_GET['phone'], $_GET['notes'], $_GET['doctor_id'], $_GET['status']));
            break;
        case 'updatePatient':
            $name = $_GET['fname'] . '' . $_GET['mname'] . '' . $_GET['lname'];
            sendResponse(updatePatient($conn, $_GET['id'], $name, $_GET['dob'], $_GET['gender'], $_GET['address'], $_GET['phone'], $_GET['notes'], $_GET['doctorDropdown'], $_GET['status']));
            break;
        case 'deletePatient':
            sendResponse(deletePatient($conn, $_GET['id']));
            break;
        case 'getPatients':
            sendResponse(getPatients($conn));
            break;
        case 'getPatientById':
            sendResponse(getPatientById($conn, $_GET['id']));
            break;
        default:
            sendResponse(['error' => 'Invalid type specified'], 400);
    }
} else {
    sendResponse(['error' => 'Invalid request method'], 405);
}