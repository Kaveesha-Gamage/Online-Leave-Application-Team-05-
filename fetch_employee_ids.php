<?php
require_once("DBConnection.php");
$department = mysqli_real_escape_string($conn, $_GET['department']);
$query = "SELECT empID FROM users WHERE department='$department'";
$result = mysqli_query($conn, $query);
$employees = [];

while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}

echo json_encode($employees);
?>