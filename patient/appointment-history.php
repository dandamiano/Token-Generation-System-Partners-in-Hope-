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

// Check if the user is logged in as a patient
if (!isset($_SESSION['patient_id'])) {
    header("Location: index.php");
    exit();
}

// Get the patient_id from the session
$patient_id = $_SESSION['patient_id'];

// Count the number of appointments for each status
$status_counts = [
    'all' => 0,
    'completed' => 0,
    'canceled' => 0,
    'rescheduled' => 0,
    'scheduled' => 0,
];

$query_counts = "
    SELECT status, COUNT(*) as count
    FROM appointments
    WHERE patient_id = '$patient_id'
    GROUP BY status
";

$result_counts = mysqli_query($conn, $query_counts);

while ($row = mysqli_fetch_assoc($result_counts)) {
    $status = strtolower($row['status']);
    $status_counts[$status] = $row['count'];
}

// Total count for 'All Appointments' tab
$status_counts['all'] = array_sum($status_counts);

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

$result_appointments = mysqli_query($conn, $query_appointments);
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
                            <h4 class="card-title">Appointment History</h4>
                            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all-appointments-tab" data-toggle="tab" href="#all-appointments" role="tab">
                                        All Appointments <span class="badge badge-primary"><?php echo $status_counts['all']; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab">
                                        Completed <span class="badge badge-success"><?php echo $status_counts['completed']; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="canceled-tab" data-toggle="tab" href="#canceled" role="tab">
                                        Canceled <span class="badge badge-danger"><?php echo $status_counts['canceled']; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="rescheduled-tab" data-toggle="tab" href="#rescheduled" role="tab">
                                        Rescheduled <span class="badge badge-warning"><?php echo $status_counts['rescheduled']; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="scheduled-tab" data-toggle="tab" href="#scheduled" role="tab">
                                        Scheduled <span class="badge badge-info"><?php echo $status_counts['scheduled']; ?></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="reportTabContent">
                                <!-- All Appointments Tab -->
                                <div class="tab-pane fade show active" id="all-appointments" role="tabpanel" aria-labelledby="all-appointments-tab">
                                    <h4 class="mt-3">All Appointments</h4>
                                    <?php displayAppointments($result_appointments); ?>
                                </div>

                                <!-- Completed Appointments Tab -->
                                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                                    <h4 class="mt-3">Completed Appointments</h4>
                                    <?php displayAppointments($result_appointments, "completed"); ?>
                                </div>

                                <!-- Canceled Appointments Tab -->
                                <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                                    <h4 class="mt-3">Canceled Appointments</h4>
                                    <?php displayAppointments($result_appointments, "canceled"); ?>
                                </div>

                                <!-- Rescheduled Appointments Tab -->
                                <div class="tab-pane fade" id="rescheduled" role="tabpanel" aria-labelledby="rescheduled-tab">
                                    <h4 class="mt-3">Rescheduled Appointments</h4>
                                    <?php displayAppointments($result_appointments, "rescheduled"); ?>
                                </div>

                                <!-- Scheduled Appointments Tab -->
                                <div class="tab-pane fade" id="scheduled" role="tabpanel" aria-labelledby="scheduled-tab">
                                    <h4 class="mt-3">Scheduled Appointments</h4>
                                    <?php displayAppointments($result_appointments, "scheduled"); ?>
                                </div>
                            </div>
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

<?php
// Function to display appointments based on status filter
function displayAppointments($result, $statusFilter = null) {
    echo '<table class="table table-striped table-bordered">';
    echo '<thead><tr>
            <th>Appointment ID</th>
            <th>Doctor</th>
            <th>Status</th>
            <th>Reason</th>
            <th>Appointment Type</th>
            <th>Appointment Date</th>
            <th>Token Number</th>
          </tr></thead><tbody>';
    
    if (mysqli_num_rows($result) > 0) {
        mysqli_data_seek($result, 0); // Reset pointer
        while ($row = mysqli_fetch_assoc($result)) {
            if ($statusFilter === null || strtolower($row['appointment_status']) === $statusFilter) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['appointment_id']) . "</td>
                        <td>" . htmlspecialchars($row['doctor_first_name'] . ' ' . $row['doctor_last_name']) . "</td>
                        <td>" . htmlspecialchars(ucfirst($row['appointment_status'])) . "</td>
                        <td>" . htmlspecialchars($row['reason']) . "</td>
                        <td>" . htmlspecialchars($row['appointmentType']) . "</td>
                        <td>" . htmlspecialchars(date("M d, Y h:s A", strtotime($row['appointment_date']))) . "</td>
                        <td>" . htmlspecialchars($row['token_number'] ?? 'N/A') . "</td>
                      </tr>";
            }
        }
    } else {
        echo "<tr><td colspan='7'>No appointments found</td></tr>";
    }
    echo '</tbody></table>';
}
?>
