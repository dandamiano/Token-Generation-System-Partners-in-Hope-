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

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in admin's ID from the session
$admin_id = $_SESSION['admin_id'];?>

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

        <div class="container-fluid mt-3">

  
        <div class="row">
    <?php
    // PDO connection to the database
    $db = new PDO('mysql:host=localhost;dbname=parteners_in_hope;charset=utf8mb4', 'root', '');

    // Fetch counts for each category
    $pendingSchedulesStmt = $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'");
    $pendingSchedulesCount = $pendingSchedulesStmt->fetchColumn();

    $approvedSchedulesStmt = $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'approved'");
    $approvedSchedulesCount = $approvedSchedulesStmt->fetchColumn();

    $doctorsStmt = $db->query("SELECT COUNT(*) FROM doctor");
    $doctorsCount = $doctorsStmt->fetchColumn();

    $activeDoctorsStmt = $db->query("SELECT COUNT(*) FROM doctor WHERE status = 'active'");
    $activeDoctorsCount = $activeDoctorsStmt->fetchColumn();

    $patientsStmt = $db->query("SELECT COUNT(*) FROM patient");
    $patientsCount = $patientsStmt->fetchColumn();

    $schedulesStmt = $db->query("SELECT COUNT(*) FROM appointments");
    $schedulesCount = $schedulesStmt->fetchColumn();

    $tokensStmt = $db->query("SELECT COUNT(*) FROM tokens WHERE status = 'waiting'");
    $pendingTokensCount = $tokensStmt->fetchColumn();

    $pendingPatientsStmt = $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending' AND patient_id IS NOT NULL");
    $pendingPatientsCount = $pendingPatientsStmt->fetchColumn();

    $reportsStmt = $db->query("SELECT COUNT(*) FROM reports");
    $reportsCount = $reportsStmt->fetchColumn();
    ?>

    <!-- Pending Schedules -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-1">
            <div class="card-body">
                <h3 class="card-title text-white">Pending Schedules</h3>
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
                <h3 class="card-title text-white">Approved Schedules</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $approvedSchedulesCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-check"></i></span>
            </div>
        </div>
    </div>

    <!-- Doctors -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-2">
            <div class="card-body">
                <h3 class="card-title text-white">Doctors</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $doctorsCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
            </div>
        </div>
    </div>

    <!-- Active Doctors -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-3">
            <div class="card-body">
                <h3 class="card-title text-white">Active Doctors</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $activeDoctorsCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-user-md"></i></span>
            </div>
        </div>
    </div>

    <!-- Patients -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-4">
            <div class="card-body">
                <h3 class="card-title text-white">Patients</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $patientsCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
            </div>
        </div>
    </div>

    <!-- Schedules -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-5">
            <div class="card-body">
                <h3 class="card-title text-white">Schedules</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $schedulesCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-heart"></i></span>
            </div>
        </div>
    </div>

    <!-- Pending Tokens -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-6">
            <div class="card-body">
                <h3 class="card-title text-white">Pending Tokens</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $pendingTokensCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-clock"></i></span>
            </div>
        </div>
    </div>

    <!-- Pending Patients -->
    <div class="col-lg-3 col-sm-6">
        <div class="card gradient-7">
            <div class="card-body">
                <h3 class="card-title text-white">Pending Appointments</h3>
                <div class="d-inline-block">
                    <h2 class="text-white"><?php echo $pendingPatientsCount; ?></h2>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa fa-hourglass-half"></i></span>
            </div>
        </div>
    </div>

    <!-- Reports -->
   
</div>

        <!--**********************************
            Content body end
        ***********************************-->
        
        
     
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
