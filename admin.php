<?php
require_once("DBConnection.php");
session_start();
if (!isset($_SESSION["sess_user"])) {
    header("Location: index.php");
} else {
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
            h1 {
                text-align: center;
                font-size: 2.5em;
                font-weight: bold;
                padding-top: 1em;
            }

            .mycontainer {
                width: 90%;
                margin: 1.5rem auto;
                min-height: 60vh;
            }

            .mycontainer table {
                margin: 1.5rem auto;
            }
        </style>

        <script>
            function rejectLeave(eid, descr) {
                let reason = prompt("Please enter the reason for rejection:");
                if (reason) {
                    window.location.href = `updateStatusReject.php?eid=${eid}&descr=${encodeURIComponent(descr)}&reason=${encodeURIComponent(reason)}`;
                }
            }
        </script>

    </head>

    <body>
        <!-- <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
        
            <a class="navbar-brand" href="#">Online Leave Application</a>

            <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link" href="list_emp.php" style="color:white;">View Employees <span class="badge badge-pill" style="background-color:#2196f3;"><?php include('count_emp.php'); ?></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="leave_history.php" style="color:white;">View Leave History</a>
            </li>
            <li class="nav-item">
            <button id="logout" onclick="window.location.href='logout.php';">Logout</button> </div>
            </li>
            </ul>
            
    </nav> -->

        <!--Navbar-->
        <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
            <div class="container justify-content-center justify-content-md-between">
                <a class="navbar-brand d-none d-md-block " href="admin.php">Online Leave Application</a>
                <ul class="nav justify-content-end align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php" style="color:white;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list_emp.php" style="color:white;">Employees <span class="badge badge-pill" style="background-color:#2196f3;"><?php include('count_emp.php'); ?></span></a>
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



        <h1>Admin Panel</h1>

        <div class="mycontainer pb-4">

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="align-middle">
                        <th>#</th>
                        <th>Emp ID</th>
                        <th>Employee</th>
                        <th>Application</th>
                        <th>Dates</th>
                        <th>Leave</th>
                        <th class="text-center">Actions</th>
                        <!-- <th>Action</th> -->
                    </thead>
                    <tbody>
                        <!-- loading all leave applications from database -->
                        <?php
                        global $row;
                        $query = mysqli_query($conn, "SELECT * FROM leaves WHERE status='Pending'");

                        $numrow = mysqli_num_rows($query);
                        if ($query) {

                            if ($numrow != 0) {
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $datetime1 = new DateTime($row['fromdate']);
                                    $datetime2 = new DateTime($row['todate']);
                                    $interval = $datetime1->diff($datetime2);

                                    echo "<tr class=\"align-middle \">
                                                    <td>$cnt</td>
                                                    <td>{$row['empID']}</td>
                                                    <td>{$row['ename']}</td>
                                                    <td>{$row['descr']}</td>
                                                    <td>{$datetime1->format('Y/m/d')} <b>-</b> {$datetime2->format('Y/m/d')}</td>
                                                    <td>{$interval->format('%a Day/s')}</td>
                                                    <td class=\"text-center\">
                                                        <a href='updateStatusAccept.php?eid={$row['eid']}&descr=" . urlencode($row['descr']) . "'><button class=' btn-success btn-sm  fw-bolder px-4'>Accept</button></a>
                                                        <a href='javascript:void(0);' onclick='rejectLeave({$row['eid']}, \"" . addslashes($row['descr']) . "\");'><button class=' btn-danger btn-sm mt-1 mt-md-0  fw-bolder px-4'>Reject</button></a>
                                                    </td>
                                                </tr>";
                                    $cnt++;
                                }
                            }
                        } else {
                            echo "Query Error : " . "SELECT * FROM leaves WHERE status='Pending'" . "<br>" . mysqli_error($conn);
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