<?php
require_once("DBConnection.php");
session_start();

if(!isset($_SESSION["sess_user"])) {
    header("Location: index.php");
    exit();
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
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f4f4f4;
        }
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
        .table-responsive {
            margin: 1.5rem auto;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 1rem;
            text-align: center;
            color: #333;
        }
        .btn-danger {
            cursor: pointer;
        }
    </style>
</head>

<body>

<nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">Online Leave Application</a>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link" href="list_emp.php" style="color: #2196f3;">
                    View Employees <span class="badge badge-pill bg-primary"><?php include('count_emp.php');?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="leave_history.php" style="color: #2196f3;">View Leave History</a>
            </li>
            <li class="nav-item">
                <button id="logout" onclick="window.location.href='logout.php';" class="btn btn-outline-danger">Logout</button>
            </li>
        </ul>
    </div>
</nav>

<h1>Admin Panel - Registered Employees</h1>

<div class="mycontainer">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Fullname</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $employees = mysqli_query($conn, "SELECT * FROM users WHERE type = 'Employee'");
                if ($employees) {
                    $cnt = 1;
                    if (mysqli_num_rows($employees) > 0) {
                        while ($row = mysqli_fetch_assoc($employees)) {
                            echo "<tr>
                                    <td>{$cnt}</td>
                                    <td>{$row['empID']}</td>
                                    <td>{$row['fullname']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['gender']}</td>
                                    <td>{$row['phone']}</td>
                                    <td>
                                        <a href='delete_emp.php?id={$row['id']}' onclick='return confirmDelete();'>
                                            <button class='btn btn-danger btn-sm'>Delete</button>
                                        </a>
                                    </td>
                                  </tr>";
                            $cnt++;
                        }
                    } else {
                        echo "<tr class='text-center'><td colspan='7'>No employees found.</td></tr>";
                    }
                } else {
                    echo "Query Error: " . mysqli_error($conn);
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    <p>Online Leave Application</p>
    <p>©2024 DEPARTMENT OF COMPUTER SCIENCE ALL RIGHTS RESERVED</p>
</footer>

<!-- JavaScript for the confirmation prompt and success alert -->
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this user?");
}

// Show success alert if deletion was successful
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('delete_success')) {
        alert("Employee record deleted successfully.");
        // Remove the delete_success parameter from the URL after showing the alert
        history.replaceState({}, document.title, window.location.pathname);
    }
}
</script>

</body>
</html>

<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
?>
