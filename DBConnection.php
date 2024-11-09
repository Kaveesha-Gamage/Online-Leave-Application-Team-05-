<?php
  $conn = mysqli_connect('10.10.10.157','group5','','leave_test');
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error."<br>");
  }
?>
