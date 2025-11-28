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
                    <h4 class="card-title">Edit Patient</h4>
                   
                    <div class="table-responsive">
    <!-- Button to Add Receptionist -->
    <a href="manage-patients.php" class="btn btn-success mb-3">Back To Patients</a>
    <?php
// db_connection.php

// Database connection credentials
$servername = "localhost"; // or your database server address
$username = "root";        // your database username
$password = "";            // your database password (empty for XAMPP by default)
$dbname = "parteners_in_hope";  // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

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
    <?php
// Include database connection file
include 'db_connection.php';

// Get patient_id from URL or session (assuming patient_id is passed as a query parameter)
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    
    // Fetch patient data from the database
    $query = "SELECT * FROM patient WHERE patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "Patient not found.";
        exit;
    }
} else {
    echo "No patient ID provided.";
    exit;
}
?>
   <form method="POST" action="" enctype="multipart/form-data">
   <?php
// Include database connection file
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $gender = $_POST['gender'];

    // Check if a new password was provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        // Keep the old password if the field is blank
        $query = "SELECT password FROM patients WHERE patient_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $password = $row['password'];
    }

    // Profile picture upload handling
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $profile_picture = basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . $profile_picture;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        // Keep the old profile picture if no new file is uploaded
        $profile_picture = $patient['profile_picture'];
    }

    // Update patient data in the database
    $query = "UPDATE patient SET first_name = ?, last_name = ?, email = ?, contact_number = ?, date_of_birth = ?, address = ?, password = ?, status = ?, gender = ?, profile_picture = ? WHERE patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssi", $first_name, $last_name, $email, $contact_number, $date_of_birth, $address, $password, $status, $gender, $profile_picture, $patient_id);

    if ($stmt->execute()) {
        echo "Patient updated successfully.";
        // Redirect to a success page or patient list page
    } else {
        echo "Error updating patient: " . $conn->error;
    }
}
?>

        <input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']; ?>">

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input type="tel" class="form-control" name="contact_number" value="<?php echo htmlspecialchars($patient['contact_number']); ?>" required>
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" class="form-control" name="date_of_birth" value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($patient['address']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password">
            <small class="form-text text-muted">Leave blank to keep the current password.</small>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" required>
                <option value="active" <?php echo ($patient['status'] == 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo ($patient['status'] == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" name="gender" required>
                <option value="Male" <?php echo ($patient['gender'] == 'Male' ? 'selected' : ''); ?>>Male</option>
                <option value="Female" <?php echo ($patient['gender'] == 'Female' ? 'selected' : ''); ?>>Female</option>
                <option value="Other" <?php echo ($patient['gender'] == 'Other' ? 'selected' : ''); ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control" name="profile_picture">
            <?php if ($patient['profile_picture']): ?>
                <img src="uploads/<?php echo htmlspecialchars($patient['profile_picture']); ?>" class="img img-rounded mt-2" alt="Profile Picture" width="50" height="50">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Update Patient</button>
    </form>
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <!-- Chartjs -->
    <script src="./plugins/chart.js/Chart.bundle.min.js"></script>
    <!-- Circle progress -->
    <script src="./plugins/circle-progress/circle-progress.min.js"></script>
   
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
