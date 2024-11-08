<?php
require_once("DBConnection.php");
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM users WHERE id = $id";
    
    if (mysqli_query($conn, $deleteQuery)) {
        // Redirect back with a success flag
        echo "<script>window.location.href = 'list_emp.php?delete_success=1';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "ERROR!";
}
?>
