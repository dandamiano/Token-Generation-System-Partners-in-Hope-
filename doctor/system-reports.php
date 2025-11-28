<?php 
// Start the session
session_start();

// Include database connection
include('db-conn.php');

// Check if the user is already logged in
if (!isset($_SESSION['doctor_id'])) {
    // Redirect to the dashboard if not logged in
    header("Location: index.php");
    exit();
}

// Database connection settings
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "parteners_in_hope"; // Replace with your database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title>Partners in Hope </title>

    <!-- Favicon icon -->
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (with Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!--******************* Preloader start ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--******************* Preloader end ********************-->

    <!-- Main wrapper start -->
    <div id="main-wrapper">

        <!-- Nav header start -->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.html">
                    <span class="brand-title text-white">
                        Partners in Hope
                    </span>
                </a>
            </div>
        </div>
        <!-- Nav header end -->

        <!-- Header and Sidebar -->
        <?php
        include('header.php');
        include('side.php');
        ?>
<!-- Content body start -->
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
        <?php
// Include database connection file
include_once 'db_connection.php';



if (!isset($_SESSION['doctor_id'])) {
    header("Location: index.php");
    exit();
}
$doctor_id = $_SESSION['doctor_id'];

// Query to get total appointments for the doctor
$totalAppointmentsQuery = "
    SELECT COUNT(*) AS total_appointments 
    FROM appointments 
    WHERE doctor_id = '$doctor_id'";
$totalAppointmentsResult = mysqli_query($conn, $totalAppointmentsQuery);
$totalAppointments = mysqli_fetch_assoc($totalAppointmentsResult)['total_appointments'];

// Query to get completed appointments for the doctor
$completedAppointmentsQuery = "
    SELECT COUNT(*) AS completed_appointments 
    FROM appointments 
    WHERE status = 'completed' 
    AND doctor_id = '$doctor_id'";
$completedAppointmentsResult = mysqli_query($conn, $completedAppointmentsQuery);
$completedAppointments = mysqli_fetch_assoc($completedAppointmentsResult)['completed_appointments'];

// Query to calculate average waiting time for the doctor's appointments
$averageWaitingTimeQuery = "
    SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, appointment_date)) AS average_waiting_time 
    FROM appointments 
    WHERE doctor_id = '$doctor_id'";
$averageWaitingTimeResult = mysqli_query($conn, $averageWaitingTimeQuery);
$averageWaitingTime = mysqli_fetch_assoc($averageWaitingTimeResult)['average_waiting_time'];

 
// Query to get number of patients treated this month for the doctor
$patientsTreatedThisMonthQuery = "
    SELECT COUNT(*) AS patients_treated 
    FROM appointments 
    WHERE status = 'completed' 
    AND MONTH(appointment_date) = MONTH(CURDATE()) 
    AND YEAR(appointment_date) = YEAR(CURDATE())
    AND doctor_id = '$doctor_id'";
$patientsTreatedThisMonthResult = mysqli_query($conn, $patientsTreatedThisMonthQuery);
$patientsTreatedThisMonth = mysqli_fetch_assoc($patientsTreatedThisMonthResult)['patients_treated'];

// Query to get number of patients treated this week for the doctor
$patientsTreatedThisWeekQuery = "
    SELECT COUNT(*) AS patients_treated_week 
    FROM appointments 
    WHERE status = 'completed' 
    AND WEEK(appointment_date, 1) = WEEK(CURDATE(), 1) 
    AND YEAR(appointment_date) = YEAR(CURDATE())
    AND doctor_id = '$doctor_id'";
$patientsTreatedThisWeekResult = mysqli_query($conn, $patientsTreatedThisWeekQuery);
$patientsTreatedThisWeek = mysqli_fetch_assoc($patientsTreatedThisWeekResult)['patients_treated_week'];

// Query to get tokens generated this week for the doctor
$tokensThisWeekQuery = "
    SELECT COUNT(*) AS tokens_this_week 
    FROM tokens 
    WHERE WEEK(assigned_at, 1) = WEEK(CURDATE(), 1) 
    AND YEAR(assigned_at) = YEAR(CURDATE())
    AND doctor_id = '$doctor_id'";
$tokensThisWeekResult = mysqli_query($conn, $tokensThisWeekQuery);
$tokensThisWeek = mysqli_fetch_assoc($tokensThisWeekResult)['tokens_this_week'];

// Query to get tokens generated this month for the doctor
$tokensThisMonthQuery = "
    SELECT COUNT(*) AS tokens_this_month 
    FROM tokens 
    WHERE MONTH(assigned_at) = MONTH(CURDATE()) 
    AND YEAR(assigned_at) = YEAR(CURDATE())
    AND doctor_id = '$doctor_id'";
$tokensThisMonthResult = mysqli_query($conn, $tokensThisMonthQuery);
$tokensThisMonth = mysqli_fetch_assoc($tokensThisMonthResult)['tokens_this_month'];

// Query to get tokens generated this year for the doctor
$tokensThisYearQuery = "
    SELECT COUNT(*) AS tokens_this_year 
    FROM tokens 
    WHERE YEAR(assigned_at) = YEAR(CURDATE())
    AND doctor_id = '$doctor_id'";
$tokensThisYearResult = mysqli_query($conn, $tokensThisYearQuery);
$tokensThisYear = mysqli_fetch_assoc($tokensThisYearResult)['tokens_this_year'];
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">My Reports</h4>
    </div>
    
    <div class="card-body">
    <a href="export_report.php" class="btn btn-success  mt-3 mb-3">Export Report as CSV</a>
   
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Report Metric</th>
                    <th>Report</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Appointments</td>
                    <td><?php echo htmlspecialchars($totalAppointments); ?></td>
                </tr>
                <tr>
                    <td>Completed Appointments</td>
                    <td><?php echo htmlspecialchars($completedAppointments); ?></td>
                </tr>
                <tr>
                    <td>Average Waiting Time (minutes)</td>
                    <td><?php echo htmlspecialchars(round($averageWaitingTime, 2)); ?></td>
                </tr>
                 
                <tr>
                    <td>Patients Treated This Month</td>
                    <td><?php echo htmlspecialchars($patientsTreatedThisMonth); ?></td>
                </tr>
                <tr>
                    <td>Patients Treated This Week</td>
                    <td><?php echo htmlspecialchars($patientsTreatedThisWeek); ?></td>
                </tr>
                <tr>
                    <td>Tokens Generated This Week</td>
                    <td><?php echo htmlspecialchars($tokensThisWeek); ?></td>
                </tr>
                <tr>
                    <td>Tokens Generated This Month</td>
                    <td><?php echo htmlspecialchars($tokensThisMonth); ?></td>
                </tr>
                <tr>
                    <td>Tokens Generated This Year</td>
                    <td><?php echo htmlspecialchars($tokensThisYear); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<?php
// Close database connection
mysqli_close($conn);
?>


    <!-- Main wrapper end -->
        <!-- Remove jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 
<script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <!-- Chartjs -->
    <script src="./plugins/chart.js/Chart.bundle.min.js"></script>
    <!-- Circle progress -->
    <script src="./plugins/circle-progress/circle-progress.min.js"></script>
    <!-- Datamap -->
    <script src="./plugins/d3v3/index.js"></script>
    <script src="./plugins/topojson/topojson.min.js"></script>
    <script src="./plugins/datamaps/datamaps.world.min.js"></script>
    <!-- Morrisjs -->
    <script src="./plugins/raphael/raphael.min.js"></script>
    <script src="./plugins/morris/morris.min.js"></script>
    <!-- Pignose Calender -->
    <script src="./plugins/moment/moment.min.js"></script>
    <script src="./plugins/pg-calendar/js/pignose.calendar.min.js"></script>
    <!-- ChartistJS -->
    <script src="./plugins/chartist/js/chartist.min.js"></script>
    <script src="./plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>
    <script src="./js/dashboard/dashboard-1.js"></script>
    <script>
    // Initialize Bootstrap 5 tabs
    var tab = new bootstrap.Tab(document.querySelector('#appointmentTabs'));
</script>

</body>

</html>

</body>

</html>
