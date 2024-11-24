<?php

        // 'appointments' => "SELECT a.id, p.name AS patient_name, u.username AS doctor_name, a.created_at 
        // FROM appointments a
        // JOIN patients p ON a.patient_id = p.id
        // JOIN users u ON a.doctor_id = u.id",
//get all tables for different buttons

require_once '../../includes/config.inc.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if (isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];


    // Define custom queries for each table
    $queries = [
        'patients' => "SELECT p.id, CONCAT(p.first_name, ' ', p.middle_name, ' ', p.last_name) AS name, p.admission_date FROM patients p WHERE p.doctor_id = ? ORDER BY p.admission_date DESC;",
        'critical' => "SELECT
                    id,
                    CONCAT(first_name, ' ', middle_name, ' ', last_name) AS name
                FROM patients
                WHERE is_critical = TRUE;"
    ];

    // Check if the table is in the whitelist
    if (array_key_exists($table, $queries)) {
        $sql = $queries[$table]; // Get the query for the requested table
        $stmt = $conn->prepare($sql);
        if ($table == "patients"){
            $stmt->bind_param("i",$id);
        }
        if ($stmt -> execute()){
            $results = $stmt -> get_result();
            $records = [];
    
            if(!empty($results)){
                while($row = $results -> fetch_assoc()){
                    $records[] = $row;
                }
                echo json_encode($records);
           
            } else{
                echo "<script>alert(Nothing found)</script>";
            }
        
        } else {
            echo json_encode(["message" => "No records found in the $table table"]);
        }
    } else {
        echo json_encode(["error" => "Invalid table specified"]);
    }
} else {
    echo json_encode(["error" => "No table specified"]);
}

$conn->close();
}

?>

