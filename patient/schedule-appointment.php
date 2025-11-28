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

            <div class="container-fluid mt-3">
                <!-- Scheduling Form Start -->


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Schedule an Appointment</h4>
                <form action="proccess.php" method="POST">
                                      <!-- Date input for the appointment -->
                    <div class="form-group">
                        <label for="appointment_date">Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                    </div>

                    <!-- Time input for the appointment -->
                    <div class="form-group">
                        <label for="appointment_time">Time</label>
                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                    </div>

                    <!-- Dropdown for the appointment type -->
                    <div class="form-group">
                        <label for="appointment_type">Appointment Type</label>
                        <select class="form-control" id="appointment_type" name="appointment_type" required>
                            <option>General Consultation</option>
                            <option>Medical Check-up</option>
                            <option>Follow-up Visit</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <!-- Textarea for the reason of the appointment -->
                    <div class="form-group">
                        <label for="reason">Reason for Appointment</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </form>
            </div>
        </div>
    </div>
</div>

                <!-- Scheduling Form End -->

                <!-- Content Row Start -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Additional content here -->
                    </div>
                </div>
                <!-- Content Row End -->
            </div>

        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        
        <!--**********************************
            Footer end
        ***********************************-->
        
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="./plugins/common/common.min.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/settings.js"></script>
    <script src="./js/gleek.js"></script>
    <script src="./js/styleSwitcher.js"></script>

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
