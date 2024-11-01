<?php

require_once("DBConnection.php");
session_start();

if(!isset($_SESSION["sess_user"])){
    header("Location: index.php");
    exit(); // Ensure no further code is executed after redirection
} else {
    // Get parameters and escape them
    $eid = $_GET['eid'];
    $descr = $_GET['descr'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE leaves SET status='Accepted' WHERE eid=? AND descr=?");
    
    // Bind parameters to the statement
    $stmt->bind_param("ss", $eid, $descr); // 'ss' means both are strings

    // Execute the statement
    if($stmt->execute()){
        echo 'Saved!!';
        header("Location: admin.php");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "Query Error: " . $stmt->error; // Output the error
    }

    // Close the statement
    $stmt->close();
}

// Display errors
ini_set('display_errors', true);
error_reporting(E_ALL);
?>
