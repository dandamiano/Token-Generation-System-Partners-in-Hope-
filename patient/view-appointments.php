<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parteners_in_hope";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: index.php");
    exit();
}

// Get the patient_id from the session
$patient_id = $_SESSION['patient_id'];

// Query to fetch appointments, doctor details, and tokens for the logged-in patient
$query_appointments = "
    SELECT a.appointment_id, a.status AS appointment_status, a.reason, a.appointmentType, a.appointment_date,
           d.first_name AS doctor_first_name, d.last_name AS doctor_last_name, d.specialization,
           t.token_number
    FROM appointments a
    LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
    LEFT JOIN tokens t ON a.appointment_id = t.appointment_id
    WHERE a.patient_id = '$patient_id'
";

// Execute the query
$result_appointments = mysqli_query($conn, $query_appointments);

// Check if the query was successful
if (!$result_appointments) {
    die('Query failed: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />
  
    <title>Partners in Hope </title>
    <!-- Favicon icon -->
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.html">
                   

                    <span class="brand-title text-white">
                    Partners in Hope 

                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
       
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

      <?php
      
      include('header.php');
      
      include('side.php');
      
      ?>

        <!--**********************************
            Content body start
        ***********************************-->


    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">My Appointments</h4>
                            <table  id="appointments-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Appointment ID</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>Appointment Type</th>
                                        <th>Appointment Date</th>
                                        <th>Token Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result_appointments) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_appointments)) {
                                    ?>
                                    <tr>
                                        <td><?= $row['appointment_id'] ?></td>
                                        <td><?= $row['doctor_first_name'] . ' ' . $row['doctor_last_name'] ?></td>
                                        <td><?= ucfirst($row['appointment_status']) ?></td>
                                        <td><?= $row['reason'] ?></td>
                                        <td><?= $row['appointmentType'] ?></td>
                                        <td><?= htmlspecialchars(date("M d, Y h:s A", strtotime($row['appointment_date']))) ?></td>
                                        <td><?= $row['token_number'] ?? 'N/A' ?></td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No appointments found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="./plugins/common/common.min.js"></script>
<script src="./js/custom.min.js"></script>
<script src="./js/settings.js"></script>
<script src="./js/gleek.js"></script>
<script src="./js/styleSwitcher.js"></script>
<script src="./plugins/chart.js/Chart.bundle.min.js"></script>
<script src="./plugins/circle-progress/circle-progress.min.js"></script>
<script src="./plugins/d3v3/index.js"></script>
<script src="./plugins/topojson/topojson.min.js"></script>
<script src="./plugins/datamaps/datamaps.world.min.js"></script>
<script src="./plugins/raphael/raphael.min.js"></script>
<script src="./plugins/morris/morris.min.js"></script>
<script src="./plugins/moment/moment.min.js"></script>
<script src="./plugins/pg-calendar/js/pignose.calendar.min.js"></script>
<script src="./plugins/chartist/js/chartist.min.js"></script>
<script src="./plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>
<script src="./js/dashboard/dashboard-1.js"></script>

</body>
</html>
