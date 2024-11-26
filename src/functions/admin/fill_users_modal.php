<?php

//db connection
include '../../includes/config.inc.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Check for form data
if($_SERVER["REQUEST_METHOD"] == 'GET'){
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];
        $query = "SELECT 
    first_name,
    middle_name,
    last_name,
    email,
    role
FROM 
    medFlow_users
WHERE 
    id = ?;
        ";
        $stmt = $conn->prepare($query);
        //bind parameters to sql statement
        $stmt->bind_param('i', $user_id);
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