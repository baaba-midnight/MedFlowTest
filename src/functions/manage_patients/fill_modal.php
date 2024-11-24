<?php

//db connection
include '../../includes/config.inc.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Check for form data
if($_SERVER["REQUEST_METHOD"] == 'GET'){
    if (isset($_GET['id'])) {
        $patient_id = $_GET['id'];
        $query = "SELECT 
    patients.id AS patient_id,
    patients.first_name AS patient_first_name,
    patients.middle_name AS patient_middle_name,
    patients.last_name AS patient_last_name,
    patients.date_of_birth,
    patients.gender,
    patients.address,
    patients.phone,
    patients.notes,
    patients.status,
    users.id AS doctor_id
FROM 
    patients
LEFT JOIN 
    users
ON 
    patients.doctor_id = users.id
WHERE 
    patients.id = ?
    AND users.role = 'doctor';
        ";
        $stmt = $conn->prepare($query);
        //bind parameters to sql statement
        $stmt->bind_param('i', $patient_id);
        //execute statement
        

        if ($stmt -> execute()){
            $results = $stmt -> get_result();


            if(!empty($results)){
                $row = $results -> fetch_assoc();
                echo json_encode($row);
        
            }else{
                echo "<script> alert('No patient data found') </script>";    }

        
        
        }else{
            echo json_encode("no patient data retrieved");
        }
    }
}else {
    echo "False";
    echo json_encode('ID is missing');
}

$conn -> close();
?>