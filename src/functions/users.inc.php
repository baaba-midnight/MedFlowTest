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

function getUsers($conn) {
    try {
        $sql = "SELECT id, username, `role` FROM users";

        $stmt = $conn->prepare($sql);
        $users = [];

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $users = [];

            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            if (empty($users)) {
                return "No users found";
            }

            $stmt->close();
            return $users;
        }
        $conn->close();
    } catch (Exception $e) {
        handleError($e);
        $stmt->close();
        $conn->close();
    }
}

function getUserById($conn, $userId) {
    try {
        $userId = (int) $userId;
        $sql = "SELECT username, `role` FROM users WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row;
        }
        $conn->close();
    } catch (Exception $e) {
        handleError($e);
        $stmt->close();
        $conn->close();
    }
}

function addUser($conn, $name, $password, $role) {
    try {
        // hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // sql query insert a new user
        $sql = "INSERT INTO users (username, password, role)
            VALUES (?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $name, $hashedPassword, $role);

        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error adding user: " . $e->getMessage());
        return false;
    }
}

function deleteUser($conn, $userId) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $userId);

    if ($stmt->execute()) {
        return "success";
    }
}

function updateUser($conn, $userId, $name = null, $password = null, $role = null) {
    try {
        // Start building the SQL query
        $sql = "UPDATE users SET ";
        $fields = [];
        $params = [];
        $types = ""; // Parameter types for binding

        if (!is_null($name)) {
            $fields[] = "username = ?";
            $params[] = $name;
            $types .= "s";
        }

        if (!is_null($password)) {
            $fields[] = "password = ?";
            $params[] = password_hash($password, PASSWORD_BCRYPT);
            $types .= "s";
        }

        if (!is_null($role)) {
            $fields[] = "role = ?";
            $params[] = $role;
            $types .= "s";
        }

        // check if any of the fields are empty
        if (empty($fields)) {
            throw new Exception("No fields provided to update.");
        }

        $sql .= implode(", ", $fields) . " WHERE id = ?";
        $params[] = $userId; 
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
        return true;
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
        case 'addUser':
            sendResponse(addUser($conn, $_GET['name'], $_GET['password'], $_GET['role']));
            break;
        case 'updateUser':
            sendResponse(updateUser($conn, $_GET['id'], $_GET['name'], $_GET['password'], $_GET['role']));
            break;
        case 'deleteUser':
            sendResponse(deleteUser($conn, $_GET['id']));
            break;
        case 'getUsers':
            sendResponse(getUsers($conn));
            break;
        case 'getUserById':
            sendResponse(getUserById($conn, $_GET['id']));
            break;
    }
} else {
    sendResponse(['error' => 'Invalid request method'], 405);
}