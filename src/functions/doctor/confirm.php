<?php

require_once '../../includes/config.inc.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $id = $_POST['id'];
    //validate form data(server side)
    $query = "UPDATE appointments
    SET status = 'completed'
    WHERE id = ?;";

    $stmt = $conn->prepare($query);
    //bind parameters to sql statement
    $stmt->bind_param("i",$id);
    //execute statement
    

    if ($stmt -> execute()){
        echo "<script> alert('Task Updated!!.') </script>";
    }else{
        echo "<script> alert('Failed to insert') </script>";
    }    
}



$conn -> close();

?>

