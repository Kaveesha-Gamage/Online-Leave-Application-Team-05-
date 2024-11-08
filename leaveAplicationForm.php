<?php
require_once("DBConnection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

session_start();
global $row;
if (!isset($_SESSION["sess_user"])) {
  header("Location: index.php");
} else {
?>

  <?php
  $fileErr = $reasonErr = $absenceErr = $absencePlusReason = $ActorEmployeeID = $absence = "";
  global $leaveApplicationValidate;
  if (isset($_POST['submit'])) {
    $leaveApplicationValidate = true;
    $fileErr = '';

    // File upload handling
    $uploadOk = true;
    $filePath = "";

    if (isset($_FILES['leaveFile']) && $_FILES['leaveFile']['error'] == UPLOAD_ERR_OK) {
      $target_dir = "uploads/";
      $filePath = $target_dir . basename($_FILES["leaveFile"]["name"]);
      $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

      // Allow only PDF files
      if ($fileType != "pdf") {
        echo '<script>alert("Only PDF files are allowed.")</script>';
        $uploadOk = false;
      } else {
        // Move file to the target directory
        if (!move_uploaded_file($_FILES["leaveFile"]["tmp_name"], $filePath)) {
          echo '<script>alert("Error uploading file.")</script>';
          $uploadOk = false;
        }
      }
    } else if (isset($_FILES['leaveFile']) && $_FILES['leaveFile']['error'] != UPLOAD_ERR_NO_FILE) {
      // Handle errors other than "no file uploaded"
      echo '<script>alert("File upload error: ' . $_FILES['leaveFile']['error'] . '")</script>';
      $uploadOk = false;
    }

    // Save leave request if upload was successful
    if ($leaveApplicationValidate && $uploadOk)
      // Fetch employee details
      $empID = $_SESSION["sess_user"]; // Updated to fetch empID from session
    $eid_query = mysqli_query($conn, "SELECT id, email, fullname FROM users WHERE empID='" . $empID . "'");
    $row = mysqli_fetch_array($eid_query);

    // Extract employee's email and full name
    $employeeEmail = $row['email'];
    $employeeFullName = $row['fullname'];

    // Insert leave request with file path
    $query = "INSERT INTO leaves(eid, empID, ename, descr, fromdate, todate, ActorDepartment, ActorEmployeeID, Actorfullname, status, file_path) 
                VALUES({$row['id']}, '{$empID}', '{$employeeFullName}', '$absencePlusReason', '$fromdate', '$todate', '$ActorDepartment', '$ActorEmployeeID', '$Actorfullname', '$status', '$filePath')";

    $execute = mysqli_query($conn, $query);

    $status = "Pending";

    if ($leaveApplicationValidate) {
      // for empID
      $empID = $_SESSION["sess_user"]; // Updated to fetch empID from session
      $eid_query = mysqli_query($conn, "SELECT id, email, fullname FROM users WHERE empID='" . $empID . "'");

      $row = mysqli_fetch_array($eid_query);

      // Extract the employee's email and full name
      $employeeEmail = $row['email'];
      $employeeFullName = $row['fullname'];

      $query = "INSERT INTO leaves(eid, empID, ename, descr, fromdate, todate, ActorDepartment, ActorEmployeeID, Actorfullname, status) VALUES({$row['id']},'{$empID}','{$employeeFullName}','$absencePlusReason', '$fromdate', '$todate', '$ActorDepartment', '$ActorEmployeeID','$Actorfullname', '$status')";
      $execute = mysqli_query($conn, $query);
      if ($execute) {
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "kvgz.1218@gmail.com";  // replace with actual email
        $mail->Password = "juodyixyzrndffhg";      // replace with actual password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";

        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';


        // Email settings
        $mail->setFrom("kvgz.1218@gmail.com", "Leave Management System");
        $mail->addAddress("testdata1324@gmail.com");  // admin email address
        $mail->isHTML(true);
        $mail->Subject = "New Leave Application Submitted by $employeeFullName";
        $mail->Body = "
           <h3>Leave Application Details:</h3>
           <p><strong>Employee ID:</strong> $empID</p>
           <p><strong>Employee Name:</strong> $employeeFullName</p>
           <p><strong>Leave Type:</strong> $absence</p>
           <p><strong>From Date:</strong> $fromdate</p>
           <p><strong>To Date:</strong> $todate</p>
           <p><strong>Reason:</strong> $reason</p>
         ";

        if ($mail->send()) {
          echo '<script>alert("Leave Application Submitted and notification sent to admin Successfully! Please wait for approval status.")</script>';
        } else {
          echo '<script>alert("Leave submitted but email notification failed: ' . $mail->ErrorInfo . '")</script>';
        }
        echo '<script>window.location.href="leaveAplicationForm.php";</script>';
        exit();
      } else {
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
        font-size: 2rem;
        font-weight: bold;
        padding-top: 1em;
        margin-bottom: -0.5em;
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

      label {
        /* margin-top: 0.5em; */
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
        let errDiv = document.getElementById('err');

        // Get the radio buttons for absence
        let absenceRadios = document.getElementsByName("absence[]");
        let selectedAbsence = Array.from(absenceRadios).some(radio => radio.checked);

        let errMsg = [];

        if (desc === "") {
          errMsg.push("Please enter the reason for leave.");
        }

        if (!selectedAbsence) {
          errMsg.push("Please select the type of Leave.");
        }

        // Show error messages if any
        if (errMsg.length > 0) {
          errDiv.style.display = "block";
          let msgs = "";

          for (let i = 0; i < errMsg.length; i++) {
            msgs += errMsg[i] + "<br/>";
          }

          errDiv.innerHTML = msgs;
          scrollTo(0, 0);
          return false; // Prevent form submission
        }

        return true; // Allow form submission
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

    <script>
      const validateAndSubmit = () => {
        let desc = document.getElementById('leaveDesc').value;
        let errDiv = document.getElementById('err');
        let absenceRadios = document.getElementsByName("absence[]");
        let selectedAbsence = Array.from(absenceRadios).some(radio => radio.checked);
        let errMsg = [];

        if (desc === "") {
          errMsg.push("Please enter the reason for leave.");
        }
        if (!selectedAbsence) {
          errMsg.push("Please select the type of Leave.");
        }

        if (errMsg.length > 0) {
          errDiv.style.display = "block";
          errDiv.innerHTML = errMsg.join("<br/>");
          scrollTo(0, 0);
          return false; // Prevent form submission if validation fails
        }

        alert("Leave Application Submitted Successfully!");
        return true; // Allow form submission if validation passes
      };
    </script>

    <script>
      function updateToDate() {
        // Get the selected "From" date
        const fromDate = document.querySelector('input[name="fromdate"]').value;
        const toDateField = document.querySelector('input[name="todate"]');

        if (fromDate) {
          // Set the minimum date for "To" date based on "From" date
          toDateField.min = fromDate;
        }
      }

      function validateDates() {
        const fromDate = document.querySelector('input[name="fromdate"]').value;
        const toDate = document.querySelector('input[name="todate"]').value;

        if (fromDate && toDate && new Date(toDate) < new Date(fromDate)) {
          alert("The 'To' date cannot be earlier than the 'From' date.");
          return false; // Prevent form submission
        }
        return true; // Allow form submission if dates are valid
      }
    </script>


  </head>

  <body>
    <!--Navbar-->
    <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
      <div class="container justify-content-end justify-content-sm-between">
        <a class="navbar-brand d-none d-sm-block " href="#">Online Leave Application</a>
        <ul class="nav justify-content-end align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="myhistory.php" style="color:white;">My Leave History</a>
          </li>
          <li class="nav-item">
            <button id="logout" onclick="window.location.href='logout.php';" class="btn btn-sm btn-danger px-3">Logout</button>

          </li>
        </ul>


      </div>
    </nav>

    <h1>Leave Application</h1>

    <div class="container  pb-4">
      <div class="alert alert-danger" id="err" role="alert" style="display: none;">
      </div>

      <form method="POST" onsubmit="return validateAndSubmit();">

        <div class="col">
          <div class="row row-cols-1">
            <div class="col mb-3">
              <label><b>Leave Catoregory :</b></label>
              <!-- Error message if type of absence isn't selected -->
              <span class="error"><?php echo "&nbsp;" . $absenceErr; ?></span><br />
              <div class="form-check">
                <input class="form-check-input" name="absence[]" type="radio" value="Sick" id="Sick" required>
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
            </div>
            <div class="col">
              <div class="d-flex flex-column justify-content-start">
                <div class=" justify-content-start">
                  <label class="col" for="dates"><b>From </b></label>
                  <input class="col form-control" type="date" name="fromdate" onchange="updateToDate()" required>
                </div>
                <div class=" justify-content-start">
                  <label class="col" for="dates"><b>To</b></label>
                  <input class="col form-control" type="date" name="todate" id="todate" required>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="row  mb-3">
          <label for="leaveDesc" class="form-label"><b>Reasons for your leave :</b></label>
          <!-- error message if reason of the leave is not given -->
          <span class="error"><?php echo "&nbsp;" . $reasonErr ?></span>
          <textarea class="form-control" name="reason" id="leaveDesc" rows="4" placeholder="Enter Here..." required></textarea>
        </div>

        <div class="mb-3">
          <label for="fileUpload" class="form-label"><b>Upload proof document regarding your leave (PDF only):</b></label>
          <input type="file" name="fileUpload" id="fileUpload" class="form-control" accept=".pdf" required>
          <span class="error"><?php echo "&nbsp;" . $fileErr; ?></span>
        </div>

        <div class="row  mb-3">
          <label for="adderss" class="form-label"><b> Current Address : </b></label>
          <input type="text" class="form-control" name="Address" id="Address" placeholder="Address during the leave" Required>
        </div>

        <!--Acting arrangement details-->
        <div class="row mb-3">
          <label for="actorDepartment" class="form-label"><b> Acting employee's Department : </b></label>
          <select name="ActorDepartment" onchange="fetchEmployeeIDs(this.value)" required class="form-select form-select">
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

        <div class="row mb-3">
          <label for="actorEmployeeID" class="form-label"><b> Acting employee's Employee ID : </b></label>
          <select class="form-control" id="ActorEmployeeID" name="ActorEmployeeID" onchange="fetchEmployeeName(this.value)" required>
            <option value="">Select Employee ID</option>
          </select>
        </div>

        <div class="row mb-3">
          <label for="Fullname" class="form-label"><b> Acting employee's Fullname : </b></label>
          <input type="text" class="form-control" name="Actorfullname" id="Actorfullname" placeholder="Actor's Fullname" readonly>
        </div>

        <div class="row">
          <input type="submit" name="submit" value="Submit Leave Request" class="btn btn-success btn-lg">
        </div>
      </form>
    </div>


    <!-- </div> -->

    <footer class="footer navbar navbar-expand-lg navbar-light bg-light" style="color:white;">
      <div>
        <!-- <p class="text-center">Online Leave Application</p> -->
        <p class="text-center">©2024 DEPARTMENT OF COMPUTER SCIENCE ALL RIGHTS RESERVED</p>
      </div>
    </footer>

  </html>

<?php
}
?>