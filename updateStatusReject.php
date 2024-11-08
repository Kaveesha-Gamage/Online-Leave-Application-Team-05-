<?php
require_once("DBConnection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start();

if (!isset($_SESSION["sess_user"])) {
    header("Location: index.php");
    exit();
} else {
    // Get parameters and escape them
    $eid = $_GET['eid'];
    $descr = $_GET['descr'];
    $rejection_reason = isset($_GET['rejection_reason']) ? $_GET['rejection_reason'] : '';  // Check if rejection_reason is set

    // Prepare the SQL statement for updating the status
    $stmt = $conn->prepare("UPDATE leaves SET status='Rejected' WHERE eid=? AND descr=?");
    $stmt->bind_param("ss", $eid, $descr);

    if ($stmt->execute()) {
        echo 'Saved!!';

        // Fetch the user's email and name (adjust the column name as needed)
        $userStmt = $conn->prepare("SELECT email, fullname FROM users WHERE id=?");
        $userStmt->bind_param("s", $eid);
        $userStmt->execute();
        $userStmt->bind_result($userEmail, $userName);
        $userStmt->fetch();

        // Debugging output to check fetched values
        if ($userEmail && $userName) {
            echo "Fetched Email: $userEmail, Name: $userName";
        } else {
            echo "No user found with eid: $eid";
        }

        $userStmt->close();

        // Check if user email and name were retrieved
        if (!empty($userEmail) && !empty($userName)) {
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
                    <p>Reason for Rejection: $rejection_reason</p>
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
        }
    } else {
        echo "Query Error: " . $stmt->error;
    }

    // Close the update statement
    $stmt->close();
}

// Display errors
ini_set('display_errors', true);
error_reporting(E_ALL);
?>
