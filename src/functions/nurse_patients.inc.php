<?php

include "../includes/config.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "SELECT 
                    p.id,
                    CONCAT(p.first_name, ' ', p.last_name) AS full_name,
                    CONCAT(u.first_name, ' ', u.last_name) AS doctor_name,
                    p.status,
                    p.updated_at
                FROM patients p
                LEFT JOIN users u ON p.doctor_id = u.id";

        $stmt= $conn->prepare($sql);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $patients = [];

            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
            $statusCode = 200;
            http_response_code($statusCode);
            echo json_encode([
                'status' => $statusCode === 200 ? 'success' : 'error',
                'data' => $patients
            ]);
            $stmt->close();
        }
        $conn->close();
    } catch (Exception $e) {
        $stmt->close();
        $conn->close();
        echo json_encode(["message" => "Unable to display patients"]);
    }
}