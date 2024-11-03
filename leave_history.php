<?php
require_once("DBConnection.php");
session_start();
if(!isset($_SESSION["sess_user"])){
  header("Location: index.php");
}
else{
    // Fetch all employees from the database
    $employeeResult = mysqli_query($conn, "SELECT empID, fullname FROM users WHERE type = 'Employee'"); // Adjust table name and columns as needed
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
    <title>Admin Panel</title>

    <style>
        h1 { text-align: center; font-size: 2.5em; font-weight: bold; padding-top: 1em; }
        .mycontainer { width: 90%; margin: 1.5rem auto; min-height: 60vh; }
        .mycontainer table { margin: 1.5rem auto; }
    </style>

</head>

<body>
    <!-- <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Online Leave Application</a>
            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="list_emp.php" style="color:white;">View Employees <span class="badge badge-pill" style="background-color:#2196f3;"><?php include('count_emp.php');?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leave_history.php" style="color:white;">View Leave History</a>
                </li>
                <li class="nav-item">
                    <button id="logout" onclick="window.location.href='logout.php';">Logout</button> 
                </li>
            </ul>
        </div>
    </nav> -->

    <!--Navbar-->
  <!--Navbar-->
  <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container justify-content-end justify-content-md-between">
      <a class="navbar-brand d-none d-md-block " href="admin.php">Online Leave Application</a>
      <ul class="nav justify-content-end align-items-center">
            <li class="nav-item">
                <a class="nav-link" href="admin.php" style="color:white;">Home</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" href="list_emp.php" style="color:white;">Employees <span class="badge badge-pill" style="background-color:#2196f3;"><?php include('count_emp.php');?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leave_history.php" style="color:white;">Leave History</a>
                </li>
                <li class="nav-item">
                <button id="logout" onclick="window.location.href='logout.php';" class="btn btn-sm btn-danger px-3">Logout</button>
                </li>
            </ul>
    </div>
  </nav>

    <h1>Admin Panel - Employee Leave History</h1>

    <div class="mycontainer">
        <!-- Filter Form -->
        <form method="GET" action="" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="empID" class="form-control">
                        <option value="">Select Employee ID</option>
                        <?php
                        // Populate dropdown with employee IDs
                        if ($employeeResult && mysqli_num_rows($employeeResult) > 0) {
                            while ($employee = mysqli_fetch_assoc($employeeResult)) {
                                $selected = (isset($_GET['empID']) && $_GET['empID'] == $employee['empID']) ? 'selected' : '';
                                echo "<option value='{$employee['empID']}' $selected>{$employee['empID']} - {$employee['ename']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="accepted" <?php if(isset($_GET['status']) && $_GET['status'] == "accepted") echo 'accepted'; ?>>accepted</option>
                        <option value="Pending" <?php if(isset($_GET['status']) && $_GET['status'] == "Pending") echo 'selected'; ?>>Pending</option>
                        <option value="Rejected" <?php if(isset($_GET['status']) && $_GET['status'] == "Rejected") echo 'selected'; ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Employee ID</th>
                    <th>Leave Application</th>
                    <th>Days</th>
                    <th>From-Date</th>
                    <th>To-Date</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php
                        // Fetch filter values from GET request
                        $empID = isset($_GET['empID']) ? $_GET['empID'] : '';
                        $status = isset($_GET['status']) ? $_GET['status'] : '';

                        // Base SQL query
                        $sql = "SELECT * FROM leaves WHERE 1=1";

                        // Append conditions based on filter inputs
                        if (!empty($empID)) {
                            $sql .= " AND empID = '$empID'";
                        }
                        if (!empty($status)) {
                            $sql .= " AND status = '$status'";
                        }

                        $leaves = mysqli_query($conn, $sql);
                        if ($leaves) {
                            $numrow = mysqli_num_rows($leaves);
                            if ($numrow != 0) {
                                $cnt = 1;
                                while ($row1 = mysqli_fetch_array($leaves)) {
                                    $datetime1 = new DateTime($row1['fromdate']);
                                    $datetime2 = new DateTime($row1['todate']);
                                    $interval = $datetime1->diff($datetime2);
                                    echo "<tr>
                                            <td>$cnt</td>
                                            <td>{$row1['ename']}</td>
                                            <td>{$row1['empID']}</td>
                                            <td>{$row1['descr']}</td>
                                            <td>{$interval->format('%a Day/s')}</td>
                                            <td>{$datetime1->format('Y/m/d')}</td>
                                            <td>{$datetime2->format('Y/m/d')}</td>
                                            <td><b>{$row1['status']}</b>" . 
                                            ($row1['status'] === 'Rejected' && !empty($row1['rejection_reason']) ? "<br><small class='text-danger'>Reason: {$row1['rejection_reason']}</small>" : '') . 
                                            "</td>
                                          </tr>";
                                    $cnt++; 
                                }
                            } else {
                                echo "<tr class='text-center'><td colspan='8'>No leave history found with the selected criteria!</td></tr>";
                            }
                        } else {
                            echo "Query Error: " . mysqli_error($conn);
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer navbar navbar-expand-lg navbar-light bg-light" style="color:white;">
        <div>
            <!-- <p class="text-center">Online Leave Application</p> -->
            <p class="text-center">©2024 DEPARTMENT OF COMPUTER SCIENCE ALL RIGHTS RESERVED</p>
        </div>
    </footer>
</body>

</html>

<?php
}

ini_set('display_errors', true);
error_reporting(E_ALL);
?>