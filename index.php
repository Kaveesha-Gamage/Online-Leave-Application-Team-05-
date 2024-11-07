<?php 
require_once("DBConnection.php"); 
include("functions.php");
session_start();
?>

<?php

 	if (isset($_POST['login'])) {
	 	if (!empty($_POST['username']) && !empty($_POST['password'])) {
	 		$username = mysqli_real_escape_string($conn,$_POST['username']);
	 		$pass = mysqli_real_escape_string($conn,$_POST['password']);

            $login = login($username,$pass,$conn);          
	 	}
	 	else{
		 	echo "Required All fields!";
		} 	
 	}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Online Leave Application</title>
    <style>
        #invalidMsg{
            display:none;
        }
    </style>
</head>
     

<body class="d-flex flex-column min-vh-100 pb-4">

    <!-- header -->
    <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Online Leave Application</a>

            <a id="register" href="signup.php">Sign Up</a>
        </div>
    </nav>
    <!-- header ends -->


   

    <!-- body -->
    <div class="container align-self-center justify-self-center my-auto border border-warning-subtle rounded-5 shadow  p-4" style="border-radius: 15px;">
        <div class="row gx-3">
            <!-- rightComponent -->
            <div class="rightComponent col-md-7 d-flex flex-column justify-content-center ">
                <div class="loginCard container ">
                    <h3 >Welcome !</h3>
                    <hr>
                    <form method="POST" class="loginForm ">
                    <div class="alert alert-danger" id="invalidMsg">
                        <?php
                            if(isset($_POST['login'])){
                                if($login == false)
                                    echo "<script type='text/javascript'>document.getElementById('invalidMsg').style.display = 'block';</script>";
                                    echo "Invalid Username or Password";
                            }
                            else
                                echo "";
                        ?>
                        </div>
                        <div class="mb-3 form-floating">
                            <input class="form-control" type="text" id="username" name="username" placeholder="Enter Employee ID" required>
                            <label for="username">Username</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <input class="form-control" type="password" id="password" name="password" placeholder="Enter Password" required>
                            <label for="password">Password</label>
                        </div>
                        <input type="submit" class="btn btn-lg btn-success" name="login" value="Log In">
                        
                    </form>
                </div>
                <div class="px-4 mt-4 d-flex justify-content-between">
                    <p><a href="forgot_password.php" class="text-decoration-none text-info-emphasis">Forgot Password?</a></p>
                    <p>Don't you have an account? <a href="signup.php" class="text-decoration-none text-info-emphasis">Signup here</a></p>
                </div>
            </div>
            <!-- rightComponent ends -->

            <!-- leftComponent -->
            <div class="leftComponent col-md-5 d-flex justify-content-center align-items-center" >
                <img src="img/front-img2_resized.png" alt="Leave Image" class="img">
            </div>
            <!-- leftComponent ends -->
        </div>
    </div>
    <!-- body ends -->


    <footer class="footer navbar navbar-expand-lg navbar-light bg-light" style="color:white;">
        <div>
        <!-- <p class="text-center">Online Leave Application</p> -->
        <p class="text-center">©2024 DEPARTMENT OF COMPUTER SCIENCE ALL RIGHTS RESERVED</p>
        </div>
    </footer>
</body>
</html>

<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
?>