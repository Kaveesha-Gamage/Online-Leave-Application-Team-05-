<?php
require_once("DBConnection.php");
session_start();
global $row;
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
        margin-top: 2em;
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


  </head>

  <body>
    <!--Navbar-->
    <!-- <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="leaveAplicationForm.php">Online Leave Application</a>
      <ul class="nav justify-content-end">
           
            <li class="nav-item">
                <a class="nav-link" href="myhistory.php" style="color:white;">My Leave History</a>
            </li>
            <li class="nav-item">
            <button id="logout" onclick="window.location.href='logout.php';">Logout</button>
            </li>
            </ul>

      
    </div>
  </nav> -->

    <!--Navbar-->
    <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
      <div class="container justify-content-end justify-content-sm-between">
        <a class="navbar-brand d-none d-sm-block " href="#">Online Leave Application</a>
        <ul class="nav justify-content-end align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="leaveAplicationForm.php" style="color:white;">Home</a>
          </li>
          <li class="nav-item">
            <button id="logout" onclick="window.location.href='logout.php';" class="btn btn-sm btn-danger px-3">Logout</button>

          </li>
        </ul>
      </div>
    </nav>


    <h1>My Leave History</h1> <br />

    <div class="container pb-4">

      <div class="table-responsive">

        <table class="table table-bordered table-hover table-striped">
          <thead class="align-middle">
            <th>#</th>
            <th>Leave Application</th>
            <th>From-Date</th>
            <th>To-Date</th>
            <th class="text-center">Status</th>
          </thead>
          <tbody>
            <!-- loading all leave applications of the user -->
            <?php
            $leaves = mysqli_query($conn, "SELECT * FROM leaves WHERE eid='" . $_SESSION['sess_eid'] . "'");
            if ($leaves) {
              $numrow = mysqli_num_rows($leaves);
              if ($numrow != 0) {
                $cnt = 1;
                while ($row1 = mysqli_fetch_array($leaves)) {
                  $statusText = $row1['status'];
                  if ($statusText === 'Accepted') {
                    $statusClass = 'text-success';
                  } elseif ($statusText === 'Pending') {
                    $statusClass = 'text-warning';
                  } elseif ($statusText === 'Rejected') {
                    $statusClass = 'text-danger';
                  }
                  echo "<tr class=\"align-middle\">
                              <td>$cnt</td>
                              <td>{$row1['descr']}</td>
                              <td>{$row1['fromdate']}</td>
                              <td>{$row1['todate']}</td>
                              <td class=\"text-center $statusClass\">
                                  <b>{$row1['status']}</b>
                                  " . ($row1['status'] === 'Rejected' && !empty($row1['rejection_reason']) ?
                    "<br><small class='text-danger fs-6 fw-light'>{$row1['rejection_reason']}</small>" : '') . "
                              </td>
                          </tr>";

                  $cnt++;
                }
              } else {
                echo "<tr class='text-center'><td colspan='12'><b>YOU DON'T HAVE ANY LEAVE HISTORY! PLEASE APPLY TO VIEW YOUR STATUS HERE!</b></td></tr>";
              }
            } else {
              echo "Query Error : " . "SELECT descr,status FROM leaves WHERE eid='" . $_SESSION['sess_eid'] . "'" . "<br>" . mysqli_error($conn);;
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