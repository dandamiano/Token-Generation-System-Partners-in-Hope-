<?php 
// Start the session
session_start();

// Include database connection
include('db-conn.php');

// Check if the user is already logged in
if (!isset($_SESSION['receptionist_id'])) {
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
                    <div class="card-header">
                        <h4 class="card-title">Appointment History</h4>
                    </div>

                    <?php
                    // Database connection
                    $connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");

                    if (!$connection) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Query to fetch appointments with patient, doctor, and token information
                    $query = "
                        SELECT 
                            a.appointment_id, 
                            p.first_name AS patient_first_name, 
                            p.last_name AS patient_last_name,
                            p.email AS patient_email, 
                            p.contact_number AS patient_contact, 
                            p.date_of_birth AS patient_dob,
                            d.first_name AS doctor_first_name, 
                            d.last_name AS doctor_last_name, 
                            d.specialization AS doctor_specialization,
                            a.appointment_date, a.reason, a.appointmentType,
                            a.status,
                            a.doctor_id, 
                            t.token_number
                        FROM appointments a
                        JOIN patient p ON a.patient_id = p.patient_id
                        LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
                        LEFT JOIN tokens t ON a.appointment_id = t.appointment_id;
                    ";

                    $result = mysqli_query($connection, $query);
                    $appointments = [
                        'scheduled' => [],
                        'rescheduled' => [],
                        'completed' => [],
                        'cancelled' => []
                    ];

                    // Organize appointments by status
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $status = $row['status'];
                            $appointments[$status][] = $row;  // Group appointments by their status
                        }
                    }
                    mysqli_close($connection);
                    ?>

                    <!-- Tabs navigation -->
                    <ul class="nav nav-tabs" id="appointmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="scheduled-tab" data-bs-toggle="tab" href="#scheduled" role="tab" aria-controls="scheduled" aria-selected="true">
                                Scheduled
                                <span class="badge bg-primary"><?php echo count($appointments['scheduled']); ?></span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="rescheduled-tab" data-bs-toggle="tab" href="#rescheduled" role="tab" aria-controls="rescheduled" aria-selected="false">
                                Rescheduled
                                <span class="badge bg-warning"><?php echo count($appointments['rescheduled']); ?></span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">
                                Completed
                                <span class="badge bg-success"><?php echo count($appointments['completed']); ?></span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">
                                Cancelled
                                <span class="badge bg-danger"><?php echo count($appointments['cancelled']); ?></span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="appointmentTabsContent">
                        <!-- Scheduled Appointments -->
                        <div class="tab-pane fade show active" id="scheduled" role="tabpanel" aria-labelledby="scheduled-tab">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Token Number</th>
                                        <th>Patient Name</th>
                                        <th>Doctor</th>
                                        <th>Appointment Date</th>
                                        <th>Reason</th>
                                        <th>Appointment Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($appointments['scheduled'] as $appointment) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($appointment['token_number']) . "</td>
                                                <td>" . htmlspecialchars($appointment['patient_first_name'] . " " . $appointment['patient_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['doctor_first_name'] . " " . $appointment['doctor_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointment_date']) . "</td>
                                                <td>" . htmlspecialchars($appointment['reason']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointmentType']) . "</td>
                                                <td>" . htmlspecialchars($appointment['status']) . "</td>
                                                <td>Actions here</td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Rescheduled Appointments -->
                        <div class="tab-pane fade" id="rescheduled" role="tabpanel" aria-labelledby="rescheduled-tab">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Token Number</th>
                                        <th>Patient Name</th>
                                        <th>Doctor</th>
                                        <th>Appointment Date</th>
                                        <th>Reason</th>
                                        <th>Appointment Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($appointments['rescheduled'] as $appointment) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($appointment['token_number']) . "</td>
                                                <td>" . htmlspecialchars($appointment['patient_first_name'] . " " . $appointment['patient_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['doctor_first_name'] . " " . $appointment['doctor_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointment_date']) . "</td>
                                                <td>" . htmlspecialchars($appointment['reason']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointmentType']) . "</td>
                                                <td>" . htmlspecialchars($appointment['status']) . "</td>
                                                <td>Actions here</td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Completed Appointments -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Token Number</th>
                                        <th>Patient Name</th>
                                        <th>Doctor</th>
                                        <th>Appointment Date</th>
                                        <th>Reason</th>
                                        <th>Appointment Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($appointments['completed'] as $appointment) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($appointment['token_number']) . "</td>
                                                <td>" . htmlspecialchars($appointment['patient_first_name'] . " " . $appointment['patient_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['doctor_first_name'] . " " . $appointment['doctor_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointment_date']) . "</td>
                                                <td>" . htmlspecialchars($appointment['reason']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointmentType']) . "</td>
                                                <td>" . htmlspecialchars($appointment['status']) . "</td>
                                                <td>Actions here</td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Cancelled Appointments -->
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Token Number</th>
                                        <th>Patient Name</th>
                                        <th>Doctor</th>
                                        <th>Appointment Date</th>
                                        <th>Reason</th>
                                        <th>Appointment Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($appointments['cancelled'] as $appointment) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($appointment['token_number']) . "</td>
                                                <td>" . htmlspecialchars($appointment['patient_first_name'] . " " . $appointment['patient_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['doctor_first_name'] . " " . $appointment['doctor_last_name']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointment_date']) . "</td>
                                                <td>" . htmlspecialchars($appointment['reason']) . "</td>
                                                <td>" . htmlspecialchars($appointment['appointmentType']) . "</td>
                                                <td>" . htmlspecialchars($appointment['status']) . "</td>
                                                <td>Actions here</td>
                                            </tr>";
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
<!-- Content body end -->

       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content body end -->
    </div>
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
