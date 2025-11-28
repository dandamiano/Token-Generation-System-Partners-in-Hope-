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
$patient_id = $_SESSION['patient_id'];?>

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
        <?php 
if (isset($_SESSION['patient_id'])) {
    // Fetch patient's first name from the database
    $patient_id = $_SESSION['patient_id'];
    // Example: database query to fetch the first name
    // Replace with actual database code
    $query = "SELECT first_name FROM patient WHERE patient_id = '$patient_id'";
    $result = mysqli_query($conn, $query);
    $patient = mysqli_fetch_assoc($result);
    $first_name = $patient['first_name'];
} else {
    $first_name = 'Guest'; // Default if no patient is logged in
}
?>
        <div class="container-fluid mt-3">
 <!-- Bootstrap Alert with Welcome Message, Animated Icon, and Logged-in Patient's First Name -->
 <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-smile-wink mr-2 animated-icon"></i> <!-- Animated FontAwesome welcome icon -->
                <strong>Welcome, <?php echo htmlspecialchars($first_name); ?>!</strong> We're glad to have you here. Let us assist you with anything you need.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <!-- Main content end -->

     
    <!--********************************** Main wrapper end ***********************************-->

    <!-- Include the necessary Bootstrap and FontAwesome JS -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <?php

$patient_id = $_SESSION['patient_id']; // Assume patient_id is stored in session

// PDO connection to the database
$db = new PDO('mysql:host=localhost;dbname=parteners_in_hope;charset=utf8mb4', 'root', '');

// Fetch counts for each category specific to the patient
$pendingSchedulesStmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE status = 'not scheduled' AND patient_id = :patient_id");
$pendingSchedulesStmt->execute([':patient_id' => $patient_id]);
$pendingSchedulesCount = $pendingSchedulesStmt->fetchColumn();

$approvedSchedulesStmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE status = 'completed' AND patient_id = :patient_id");
$approvedSchedulesStmt->execute([':patient_id' => $patient_id]);
$approvedSchedulesCount = $approvedSchedulesStmt->fetchColumn();

$tokensStmt = $db->prepare("SELECT COUNT(*) FROM tokens WHERE status = 'waiting' AND patient_id = :patient_id");
$tokensStmt->execute([':patient_id' => $patient_id]);
$pendingTokensCount = $tokensStmt->fetchColumn();

$pendingPatientsStmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE status = 'scheduled' AND patient_id = :patient_id");
$pendingPatientsStmt->execute([':patient_id' => $patient_id]);
$pendingPatientsCount = $pendingPatientsStmt->fetchColumn();
?>

<div class="row">
    <!-- Pending Schedules -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-1">
            <div class="card-body">
                <h3 class="card-title text-white">My Pending Appointments</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $pendingSchedulesCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>

    <!-- Approved Schedules -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-2">
            <div class="card-body">
                <h3 class="card-title text-white">My Completed Appointments</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $approvedSchedulesCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-check"></i></span>
            </div>
        </div>
    </div>

    <!-- Pending Tokens -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-6">
            <div class="card-body">
                <h3 class="card-title text-white">My Pending Appointment Tokens</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $pendingTokensCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-file"></i></span>
            </div>
        </div>
    </div>

    <!-- Pending Patients -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-7">
            <div class="card-body">
                <h3 class="card-title text-white">My Scheduled Appointments</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $pendingPatientsCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-hourglass-half"></i></span>
            </div>
        </div>
    </div>

  
</div>

               
        
     
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
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

</body>

</html>
