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


if($_SERVER["REQUEST_METHOD"] == 'GET'){
    $id = $_GET['id'];
    //validate form data(server side)
    $query = "SELECT 
    p.id,
    CONCAT(p.first_name, ' ', p.middle_name, ' ', p.last_name) AS patient_name,
    p.date_of_birth,
    p.gender,
    p.phone,
    TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) AS age,
    p.status AS patient_status
FROM 
    appointments a
JOIN 
    patients p ON a.patient_id = p.id
WHERE 
    a.doctor_id = ? -- Replace ? with the specific doctor's ID
    AND a.status = 'in_progress'
LIMIT 5;";

    $stmt = $conn->prepare($query);
    //bind parameters to sql statement
    $stmt->bind_param("i",$id);
    //execute statement
    

    if ($stmt -> execute()){
        $results = $stmt -> get_result();
        $patients = [];

        if(!empty($results)){
            while($row = $results -> fetch_assoc()){
                $patients[] = $row;
            }

            echo json_encode($patients);
       
        }else{
            echo "<script> alert('Empty results') </script>";
        }

    
    
    }else{
        echo json_encode("no tasks found, add a new task.");
    }
    $stmt -> close();

}

$conn -> close();

?>

