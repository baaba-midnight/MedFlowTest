<?php 
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

function getUsers($conn) {
    try {
        $sql = "SELECT id, first_name, last_name, email, `role` FROM users";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute query");
        }

        $result = $stmt->get_result();
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $stmt->close();
        return $users;
    } catch (Exception $e) {
        throw new Exception("Failed to fetch users: " . $e->getMessage());
    }
}

function getUserById($conn, $userId) {
    try {
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email, `role` FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute query");
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!$user) {
            throw new Exception("User not found");
        }

        $stmt->close();
        return $user;
    } catch (Exception $e) {
        throw new Exception("Failed to fetch user: " . $e->getMessage());
    }
}

function addUser($conn, $data) {
    try {
        if (empty($data['fname']) || empty($data['password']) || empty($data['role'])) {
            throw new Exception("Missing required fields");
        }

        if (trim($data['password']) !== trim($data['confirmPassword'])) {
            throw new Exception("Passwords do not match");
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users(first_name, middle_name, last_name,email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $data['fname'], $data['mname'], $data['lname'], $data['email'], $hashedPassword, $data['role']);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add user");
        }

        $newId = $stmt->insert_id;
        $stmt->close();
        
        return ["message" => "User added successfully", "id" => $newId];
    } catch (Exception $e) {
        throw new Exception("Failed to add user: " . $e->getMessage());
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

function deleteUser($conn, $id) {
    try {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        $id = (int) $id;
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete user");
        }

        if ($stmt->affected_rows === 0) {
            throw new Exception("User not found");
        }

        $stmt->close();
        return ["message" => "User deleted successfully"];
    } catch (Exception $e) {
        throw new Exception("Failed to delete user: " . $e->getMessage());
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
        case 'getUsers':
            sendResponse(getUsers($conn));
            break;

        case 'getUserById':
            if (empty($data['id'])) {
                throw new Exception("User ID is required", 400);
            }
            sendResponse(getUserById($conn, $data['id']));
            break;

        case 'addUser':
            sendResponse(addUser($conn, $data));
            break;

        case 'updateUser':
            sendResponse(updateUser($conn, $data));
            break;

        case 'deleteUser':
            if (empty($data['id'])) {
                throw new Exception("User ID is required", 400);
            }
            sendResponse(deleteUser($conn, $data['id']));
            break;

        default:
            throw new Exception("Invalid type specified", 400);
    }
} catch (Exception $e) {
    handleError($e->getMessage(), $e->getCode() ?: 500);
}