<?php
require_once("DBConnection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start();

if (!isset($_SESSION["sess_user"])) {
    header("Location: index.php");
} else {

    $eid = $_GET['eid'];
    $descr = $_GET['descr'];
    $reason = $_GET['reason'];

    $add_to_db = mysqli_query($conn, "UPDATE leaves SET status='Rejected', rejection_reason='$reason' WHERE eid='$eid' AND descr='$descr'");

    if ($add_to_db) {
        echo "Saved!!";
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "kvgz.1218@gmail.com";  // replace with actual email
            $mail->Password = "juodyixyzrndffhg";      // replace with actual password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom("kvgz.1218@gmail.com", "Leave Management System");
            $mail->addAddress($userEmail, $userName);
            $mail->isHTML(true);
            $mail->Subject = "Leave Request Rejected";
            $mail->Body = "
					  <h3>Leave Request Update</h3>
					  <p>Dear $userName,</p>
					  <p>Your leave request for the following reason has been <strong>rejected</strong>:</p>
					  <p>Reason for Rejection: $reason</p>
					  <p><em>$descr</em></p>
					  <p>Please contact your manager for further details.</p>
					  <p>Thank you,</p>
					  <p>Leave Management System</p>
				  ";

            if ($mail->send()) {
                echo '<script>alert("Leave Application Rejected and notification sent to user.")</script>';
            } else {
                echo '<script>alert("Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '")</script>';
            }

            echo '<script>window.location.href="admin.php";</script>';
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        header("Location: admin.php");
    } else {
        echo "Query Error : " . "UPDATE leaves SET status='Rejected' WHERE eid='" . $eid . "' AND descr='" . $desc . "'" . "<br>" . mysqli_error($conn);
    }
}

ini_set('display_errors', true);
error_reporting(E_ALL);
