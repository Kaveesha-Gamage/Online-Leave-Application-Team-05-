<?php
require_once("DBConnection.php");
$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT fullname FROM users WHERE empID='$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    echo json_encode(['fullname' => '']);
}
?>