<?php
require_once("DBConnection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate token and expiration
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 1800; // 30 minutes from now
        
        // Store token and expiration in database
        $query = "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'";
        mysqli_query($conn, $query);
        
        // Set up PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'kvgz.1218@gmail.com'; // Your Gmail address
            $mail->Password = 'juodyixyzrndffhg'; // Your Gmail password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;


            // Recipients
            $mail->setFrom('kvgz.1218@gmail.com', 'Online Leave Application');
            $mail->addAddress($email);

            // Content
            $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click on this link to reset your password: <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            echo 'A password reset link has been sent to your email.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="forgot_password.php" method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <input type="submit" name="submit" value="Send Reset Link">
    </form>
</body>
</html>
