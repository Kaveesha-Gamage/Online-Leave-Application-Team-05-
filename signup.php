<?php
require_once("DBConnection.php");
include("functions.php");
session_start();
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <title>Sign Up</title>
  <style>
    h1 {
      text-align: center;
      font-size: 2rem;
      font-weight: bold;
      padding-top: 1.5rem;
    }

    form {
      padding: 30px;
    }

    input,
    textarea {
      margin: 5px;
      font-size: 1.1em !important;
      outline: none;
    }

    /* input[type=radio]
    {
      width: max-content;
      padding: 5px;
      margin-top: 20px;
      margin-bottom: 20px;
      margin-left: 30px;
      margin-right: 5px;
    } */

    textarea {
      height: 80px;
    }

    #err {
      display: none;
      padding: 1.5em;
      padding-left: 4em;
      font-size: 1.2em;
      font-weight: bold;
      margin-top: 1em;
    }

    .error {
      color: #FF0000;
    }
  </style>

</head>

<body class="d-flex flex-column min-vh-100 pb-4">
  <!-- php code -->
  <?php
  $nameErr = $empIDErr = $emailErr = $phoneErr = $passwordErr = $repasswordErr = $genderErr = "";
  $fullname = $empID = $email = $phone = $password = $repassword = $gender = "";
  global $validate;

  if (isset($_POST['submit'])) {

    if (empty($_POST['fullname'])) {
      $fullnameErr = "Please Enter Fullname";
      $validate = false;
    } else {
      $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
      $validate = true;
    }

    if (empty($_POST['empID'])) {
      $empIDErr = "Please Enter your Employee ID";
      $validate = false;
    } else {
      $empID = mysqli_real_escape_string($conn, $_POST['empID']);
      $validate = true;
    }

    if (empty($_POST['email'])) {
      $emailErr = "Please Enter Email";
      $validate = false;
    } else {
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $validate = true;
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Please Enter valid email";
        $validate = false;
      }
    }

    if (empty($_POST['phone'])) {
      $phoneErr = "Please Enter Phone Number";
      $validate = false;
    } else {
      $phone = mysqli_real_escape_string($conn, $_POST['phone']);
      $validate = true;
      if (strlen($phone) > 10 || strlen($phone) < 10 || !preg_match("/[0-9]/", $phone)) {
        $phoneErr = "Please Enter valid Phone Number";
        $validate = false;
      }
    }

    if (empty($_POST['password'])) {
      $passwordErr = "Please Enter Password";
      $validate = false;
    } else {
      $password = mysqli_real_escape_string($conn, $_POST['password']);
      $validate = true;
    }

    if (empty($_POST['repassword'])) {
      $repasswordErr = "Please Enter re-password";
      $validate = false;
    } else {
      $repassword = mysqli_real_escape_string($conn, $_POST['repassword']);
      $validate = true;
      if ($password !== $repassword) {
        $repasswordErr = "Password and Confirm Password don't match";
        $validate = false;
      }
    }

    if (empty($_POST['gender'])) {
      $genderErr = "Please Select Gender";
      $validate = false;
    } else {
      $gender = mysqli_real_escape_string($conn, $_POST['gender']);
      $validate = true;
    }


    $dept = $_POST['department'];
    $type = 'employee';


    if ($validate) {
      signup($fullname, $empID, $email, $password, $phone, $repassword, $gender, $dept, $type, $conn);
    }
  }

  ini_set('display_errors', true);
  error_reporting(E_ALL);
  ?>


  <!-- navbar -->
  <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">Online Leave Application</a>

      <a id="register" href="index.php">Login</a>
    </div>
  </nav>

  <div class="container align-self-center justify-self-center my-auto border border-warning-subtle rounded-5 shadow  p-sm-3 m-5 mt-3" style="border-radius: 15px;">
    <h1>Let's Register</h1>
    <div class="container">
      <div class="alert alert-danger" id="err" role="alert">
      </div>

      <!--form-->
      <form method="POST" autocomplete="off">

        <!--Employee ID-->
        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="empID" id="empID" value="<?php echo $empID; ?>" placeholder="Employee ID" required>
          <label for="empID">Employee ID</label>
          <span class="error"><?php echo $empIDErr; ?></span>
        </div>
        <!--Name-->
        <div class="form-floating mb-3">
          <input type="text" class="form-control" name="fullname" id="fullname" value="<?php echo $fullname; ?>" placeholder="Fullname" Required>
          <label for="Fullname">Fullname</label>
          <span class="error"><?php echo $nameErr; ?></span>
        </div>

        <!--Email id-->
        <div class="form-floating mb-3">
          <input class="form-control" type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="Enter your email">
          <label for="email">Email address</label>
          <span class="error"><?php echo $emailErr; ?></span>
        </div>

        <!--Phone No.-->
        <div class="form-floating mb-3">
          <input class="form-control" type="tel" name="phone" id="phone" value="<?php echo $phone; ?>" placeholder="Enter your Phone no.">
          <label for="phone">Phone No.</label>
          <span class="error"><?php echo $phoneErr; ?></span>
        </div>

        <!--Password.-->
        <div class="form-floating mb-3">
          <input class="form-control" type="password" name="password" id="password" value="<?php echo $password; ?>" placeholder="Enter your password">
          <label for="password">Password</label>
          <span class="error"><?php echo $passwordErr; ?></span>
        </div>

        <!--Confirm Password.-->
        <div class="form-floating mb-3">
          <input class="form-control" type="password" name="repassword" id="confirmPassword" value="<?php echo $repassword ?>" placeholder="Re-Enter password">
          <label for="confirmPassword">Confirm Password</label>
          <span class="error"><?php echo $repasswordErr; ?></span>
        </div>

        <div class="row row-cols-1 row-cols-lg-2 justify-content-between align-items-center">
          <div class="col d-flex align-items-center mb-3">
            <label for="gender" class="form-label">Gender:</label>
            <input type="radio" id="gender" name="gender" class=" form-check-input" <?php if (isset($gender) && $gender == "Male") echo "checked" ?> value="Male">
            <label for="male" class=" form-check-label">Male</label>
            <input type="radio" id="gender" name="gender" class=" form-check-input" <?php if (isset($gender) && $gender == "Female") echo "checked" ?> value="Female">
            <label for="female" class=" form-check-label">Female</label>
            <span class=" error"><?php echo $genderErr; ?></span>
          </div>

          <div class="col mb-3">
            <select name="department" required class="form-select form-select-lg">
              <option>Select your Department</option>
              <option>Computer Science</option>
              <option>Physics</option>
              <option>Mathematics and Statistics</option>
              <option>Chemistry</option>
              <option>Botany</option>
              <option>Fisheries</option>
              <option>Zoology</option>
            </select>
            <!-- <label>Department : </label> -->
          </div>
        </div>
        <br>


        <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-primary fw-bolder" style="background-color: #0f0283">
      </form>
    </div>
  </div>



  <!--Footer-->
  <footer class="footer bg-light" style="color:white;">
    <div>
      <!-- <p class="text-center">&copy; Online Leave Application</p> -->
      <p class="text-center">©2024 DEPARTMENT OF COMPUTER SCIENCE ALL RIGHTS RESERVED</p>
    </div>
  </footer>


</body>

</html>