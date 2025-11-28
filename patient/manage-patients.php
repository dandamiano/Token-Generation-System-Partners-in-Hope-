<?php 
// Start the session
session_start();

// Include database connection
include('db-conn.php');

// Check if the user is already logged in
if (!isset($_SESSION['patient_id'])) {
    // Redirect to the dashboard if already logged in
    header("Location: index.php");
    exit();
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
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
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
    <h4 class="card-title">Manage Patients</h4>
                   
    <div class="table-responsive">
        <!-- Button to Add Patient (if needed) -->
        <a href="add-patient.php" class="btn btn-success mb-3">Add New Patient</a>

        <!-- Message display -->
        <?php
        if (isset($_SESSION['message'])) {
            $msg_type = $_SESSION['msg_type'] == 'success' ? 'alert-success' : 'alert-danger';
            echo "<div class='alert $msg_type alert-dismissible fade show' role='alert'>
                    " . $_SESSION['message'] . "
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>";
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        }
        ?>

        <!-- Table to display patient data -->
        <table id="patientTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Profile Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all patients from the database
                $stmt = $db->query("SELECT * FROM patient");
                while ($patient = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$patient['patient_id']}</td>
                        <td>{$patient['first_name']}</td>
                        <td>{$patient['last_name']}</td>
                        <td>{$patient['email']}</td>
                        <td>{$patient['contact_number']}</td>
                        <td>{$patient['date_of_birth']}</td>
                        <td>{$patient['address']}</td>
                        <td>{$patient['status']}</td>
                        <td><img src='uploads/{$patient['profile_picture']}' class='img img-rounded' alt='Profile Picture' width='50' height='50'></td>
                        <td>
                            <!-- Edit button -->
                            <a class='btn btn-warning btn-sm' href='edit-patient.php?patient_id={$patient['patient_id']}'>Edit</a>
                            <!-- Delete button -->
                            <a class='btn btn-danger btn-sm' href='delete-patient.php?patient_id={$patient['patient_id']}'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


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
