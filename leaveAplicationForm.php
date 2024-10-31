<?php
require_once("DBConnection.php");
session_start();
global $row;
if(!isset($_SESSION["sess_user"])){
  header("Location: index.php");
}
else{
?>

<?php 
  $reasonErr = $absenceErr = $absencePlusReason = $ActorEmployeeID = $absence = "";
  global $leaveApplicationValidate;
  if(isset($_POST['submit'])){
    if(empty($_POST['absence'])){
      $absenceErr = "Please select absence type";
      $leaveApplicationValidate = false;
    }
    else{
      $arr = $_POST['absence'];
      $absence = implode(",",$arr);
      $leaveApplicationValidate = true;
    }

    if(empty($_POST['fromdate'])){
      $fromdateErr = "Please Enter starting date";
      $leaveApplicationValidate = false;
    }
    else{
      $fromdate = mysqli_real_escape_string($conn,$_POST['fromdate']);
      $leaveApplicationValidate = true;
    }

    if(empty($_POST['todate'])){
      $todateErr = "Please Enter ending date";
      $leaveApplicationValidate = false;
    }
    else{
      $todate = mysqli_real_escape_string($conn,$_POST['todate']);
      $leaveApplicationValidate = true;
    }

    $reason = mysqli_real_escape_string($conn,$_POST['reason']);
    if(empty($reason)){
      $reasonErr = "Please give reason for the leave in detail";
      $leaveApplicationValidate = false;
    }
    else{
      $absencePlusReason = $absence." : ".$reason;
      $leaveApplicationValidate = true;
    }

    if(empty($_POST['ActorDepartment'])){
      $actIDErr = "Please Enter Actor's Department";
      $leaveApplicationValidate = false;
    }
    else{
      $ActorDepartment = mysqli_real_escape_string($conn,$_POST['ActorDepartment']);
      $leaveApplicationValidate = true;
    }

    if(empty($_POST['ActorEmployeeID'])){
      $actIDErr = "Please Enter Actor's EmployeeID";
      $leaveApplicationValidate = false;
    }
    else{
      $ActorEmployeeID = mysqli_real_escape_string($conn,$_POST['ActorEmployeeID']);
      $leaveApplicationValidate = true;
    }

    if(empty($_POST['Actorfullname'])){
      $actnameErr = "Please Enter Actor's name";
      $leaveApplicationValidate = false;
    }
    else{
      $Actorfullname = mysqli_real_escape_string($conn,$_POST['Actorfullname']);
      $leaveApplicationValidate = true;
    }
    
    $status = "Pending";
    
    if($leaveApplicationValidate){
      // for empID
      $empID = $_SESSION["sess_user"]; // Updated to fetch empID from session
      $eid_query = mysqli_query($conn, "SELECT id, email, fullname FROM users WHERE empID='" . $empID . "'");
      
      $row = mysqli_fetch_array($eid_query);

      // Extract the employee's email and full name
      $employeeEmail = $row['email'];
      $employeeFullName = $row['fullname'];
      
      $query = "INSERT INTO leaves(eid, empID, ename, descr, fromdate, todate, ActorDepartment, ActorEmployeeID, Actorfullname, status) VALUES({$row['id']},'{$empID}','{$employeeFullName}','$absencePlusReason', '$fromdate', '$todate', '$ActorDepartment', '$ActorEmployeeID','$Actorfullname', '$status')";
      $execute = mysqli_query($conn,$query);
      if($execute){
        echo '<script>alert("Leave Application Submitted. Please wait for approval status!")</script>';

        // Send email to admin using PHPMailer
       /* require_once "./PHPMailer/PHPMailer.php";
        require_once "./PHPMailer/SMTP.php";
        require_once "./PHPMailer/Exception.php";
        require './vendor/autoload.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        // SMTP settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com"; // Or your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = "kvgz.1218@gmail.com"; // Replace with your email
        $mail->Password = 'dpwv fguk escn bnmm'; // App-specific password or SMTP password
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";

        // Email settings
        $mail->isHTML(true);
        $mail->setFrom($employeeEmail, $employeeFullName); // Set "From" to the employee's email and name
        $mail->addAddress("testdata1324@gmail.com"); // Replace with the admin's email
        $mail->Subject = "New Leave Request from $employeeFullName";

        // Leave details
        $mailContent = "<h3>New Leave Request</h3>
            <p><b>Employee Name:</b> $employeeFullName</p>
            <p><b>Leave Type:</b> $absence</p>
            <p><b>From:</b> $fromdate</p>
            <p><b>To:</b> $todate</p>
            <p><b>Reason:</b> $reason</p>
            <p><b>Acting Employee ID:</b> $ActorEmployeeID</p>
            <p><b>Acting Employee Name:</b> $Actorfullname</p>
            <p><b>Status:</b> Pending</p>";

        $mail->Body = $mailContent;

        if ($mail->send()) {
            echo '<script>alert("Leave Application Submitted. Email notification sent to admin.")</script>';
        } else {
            echo "Email sending failed: " . $mail->ErrorInfo;
        } */
      }
      else{
        echo "Query Error : " . $query . "<br>" . mysqli_error($conn);
      }
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <title>Leave Application</title>
  <style>
    h1 {
      text-align: center;
      font-size: 2.5em;
      font-weight: bold;
      padding-top: 1em;
      margin-bottom: -0.5em;
    }

    form {
      padding: 40px;
    }

    input,
    textarea {
      margin: 5px;
      font-size: 1.1em !important;
      outline: none;
    }

    label {
      margin-top: 0.5em;
      font-size: 1.1em !important;
    }

    label.form-check-label {
      margin-top: 0px;
    }

    #err {
      display: none;
      padding: 1.5em;
      padding-left: 4em;
      font-size: 1.2em;
      font-weight: bold;
      margin-top: 1em;
    }

    table {
      width: 90% !important;
      margin: 1.5rem auto !important;
      font-size: 1.1em !important;
    }

    .error {
      color: #FF0000;
    }
  </style>

  <script>
    const validate = () => {

      let desc = document.getElementById('leaveDesc').value;
      let checkbox = document.getElementsByClassName("form-check-input");
      let errDiv = document.getElementById('err');

      let checkedValue = [];
      for (let i = 0; i < checkbox.length; i++) {
        if (checkbox[i].checked === true)
          checkedValue.push(checkbox[i].id);
      }

      let errMsg = [];

      if (desc === "") {
        errMsg.push("Please enter the reason and date of leave");
      }

      if (checkedValue.length < 1) {
        errMsg.push("Please select the type of Leave");
      }

      if (errMsg.length > 0) {
        errDiv.style.display = "block";
        let msgs = "";

        for (let i = 0; i < errMsg.length; i++) {
          msgs += errMsg[i] + "<br/>";
        }

        errDiv.innerHTML = msgs;
        scrollTo(0, 0);
        return;
      }
    }
    function fetchEmployeeIDs(department) {
      if (department === "Select your Department") return;

      axios.get(`fetch_employee_ids.php?department=${department}`)
        .then(response => {
          const employeeIDField = document.getElementById('ActorEmployeeID');
          const actorFullnameField = document.getElementById('Actorfullname');
          employeeIDField.value = ''; // Clear previous value
          actorFullnameField.value = ''; // Clear previous name

          // Clear existing options
          employeeIDField.innerHTML = '<option value="">Select Employee ID</option>';

          // Populate employee IDs dropdown
          response.data.forEach(emp => {
            const option = document.createElement('option');
            option.value = emp.empID;
            option.textContent = emp.empID;
            employeeIDField.appendChild(option);
          });
        })
        .catch(error => console.error('Error fetching employee IDs:', error));
    }

    function fetchEmployeeName(employeeID) {
      if (!employeeID) return;

      axios.get(`fetch_employee_name.php?id=${employeeID}`)
        .then(response => {
          const actorFullnameField = document.getElementById('Actorfullname');
          actorFullnameField.value = response.data.fullname; // Set full name
        })
        .catch(error => console.error('Error fetching employee name:', error));
    }
  </script>


</head>

<body>
  <!--Navbar-->
  <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Online Leave Application</a>
      <ul class="nav justify-content-end">
           
            <li class="nav-item">
                <a class="nav-link" href="myhistory.php" style="color:white;">My Leave History</a>
            </li>
            <li class="nav-item">
            <button id="logout" onclick="window.location.href='logout.php';">Logout</button>
            </li>
            </ul>

      
    </div>
  </nav>


  <h1>Leave Application</h1>

  <div class="container">
    <div class="alert alert-danger" id="err" role="alert">
    </div>
  
    <form method="POST">
      
  
    <label><b>Select Leave Type :</b></label>
        <!-- Error message if type of absence isn't selected -->
        <span class="error"><?php echo "&nbsp;" . $absenceErr; ?></span><br/>
        <div class="form-check">
            <input class="form-check-input" name="absence[]" type="radio" value="Sick" id="Sick">
            <label class="form-check-label" for="Sick">Sick</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" name="absence[]" type="radio" value="Casual" id="Casual">
            <label class="form-check-label" for="Casual">Casual</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" name="absence[]" type="radio" value="Vacation" id="Vacation">
            <label class="form-check-label" for="Vacation">Vacation</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" name="absence[]" type="radio" value="Duty" id="Duty">
            <label class="form-check-label" for="Duty">Duty</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" name="absence[]" type="radio" value="Other" id="Other">
            <label class="form-check-label" for="Other">Others</label>
        </div> 
        <br/>
  
      <div class="mb-3 ">
        <label for="dates"><b>From -</b></label>
        <input type="date" name="fromdate">
  
        <label for="dates"><b>To -</b></label>
        <input type="date" name="todate">
      </div>
  
      <div class="mb-3">
        <label for="leaveDesc" class="form-label"><b>Please mention reasons for your leave days :</b></label>
        <!-- error message if reason of the leave is not given -->
        <span class="error"><?php echo "&nbsp;".$reasonErr ?></span>
        <textarea class="form-control" name="reason" id="leaveDesc" rows="4" placeholder="Enter Here..." required></textarea>
      </div>

      <div class="mb-3">
        <label for="adderss" class="form-label"><b> Address of the applicant during the leave : </b></label>
        <input type="text" class="form-control" name="Address" id="Address" placeholder="Address during the leave" Required>
      </div>

      <!--Acting arrangement details-->
      <div class="mb-3">
        <label for="actorDepartment" class="form-label"><b> Acting employee's Department : </b></label><br/>
        <select name="ActorDepartment" onchange="fetchEmployeeIDs(this.value)" required>
          <option>Select your Department</option>
          <option>Computer Science</option>
          <option>Physics</option>
          <option>Mathematics and Statistics</option>
          <option>Chemistry</option>
          <option>Botany</option>
          <option>Fisheries</option>
          <option>Zoology</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="actorEmployeeID" class="form-label"><b> Acting employee's Employee ID : </b></label>
        <select class="form-control" id="ActorEmployeeID" onchange="fetchEmployeeName(this.value)" required>
          <option value="">Select Employee ID</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="Fullname" class="form-label"><b> Acting employee's Fullname : </b></label>
        <input type="text" class="form-control" name="Actorfullname" id="Actorfullname" placeholder="Actor's Fullname" readonly>
      </div>
      
      <br/>
      <input type="submit" name="submit" value="Submit Leave Request" class="btn btn-success">
    </form>
  
    
  </div>

  <footer class="footer navbar navbar-expand-lg navbar-light bg-light" style="color:white;">
    <div>
    <p class="text-center">Online Leave Application</p>
      <p class="text-center">©2024 DEPARTMENT OF COMPUTER SCIENCE ALL RIGHTS RESERVED</p>
    </div>
  </footer>
</html>

<?php
}
?>