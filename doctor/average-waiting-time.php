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
            <div class="col-lg-12">
                <div class="card">
                <?php
// Database connection
include_once 'db_connection.php'; // Assuming db_connection.php includes $conn

// Query to fetch appointment history and calculate the average waiting time
$query = "SELECT *, TIMESTAMPDIFF(MINUTE, created_at, appointment_date) AS waiting_time 
          FROM appointments";
$result_appointments = mysqli_query($conn, $query);

// Calculate the average waiting time
$query_avg_waiting_time = "SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, appointment_date)) AS avg_waiting_time 
                           FROM appointments 
                           WHERE status = 'completed'";
$result_avg = mysqli_query($conn, $query_avg_waiting_time);
$row_avg = mysqli_fetch_assoc($result_avg);
$avg_waiting_time = round($row_avg['avg_waiting_time'], 2); // Round to 2 decimal places

?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Appointment History</h4>
        <p>Average Waiting Time: <?php echo $avg_waiting_time; ?> minutes</p>
    </div>
    
    <div class="card-body">
        <!-- Display appointments in a table format -->
        <?php displayAppointments($result_appointments); ?>
    </div>
</div>

<?php
// Function to display appointments based on the query results
function displayAppointments($result) {
    echo '<table class="table table-striped table-bordered">';
    echo '<thead><tr>
            <th>Appointment ID</th>
            <th>Patient ID</th>
            <th>Doctor ID</th>
            <th>Status</th>
            <th>Reason</th>
            <th>Appointment Type</th>
            <th>Appointment Date</th>
            <th>Created At</th>
            <th>Waiting Time (minutes)</th>
          </tr></thead><tbody>';
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['appointment_id']) . "</td>
                    <td>" . htmlspecialchars($row['patient_id']) . "</td>
                    <td>" . htmlspecialchars($row['doctor_id']) . "</td>
                    <td>" . htmlspecialchars(ucfirst($row['status'])) . "</td>
                    <td>" . htmlspecialchars($row['reason']) . "</td>
                    <td>" . htmlspecialchars($row['appointmentType']) . "</td>
                    <td>" . htmlspecialchars(date("M d, Y h:s A", strtotime($row['appointment_date']))) . "</td>
                    <td>" . htmlspecialchars(date("M d, Y h:s A", strtotime($row['created_at']))) . "</td>
                    <td>" . htmlspecialchars($row['waiting_time'] ?? 'N/A') . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No appointments found</td></tr>";
    }
    echo '</tbody></table>';
}
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
