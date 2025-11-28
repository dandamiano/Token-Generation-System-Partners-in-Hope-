<?php 
// Start the session
session_start();

// Include database connection
include('db-conn.php');

// Check if the user is already logged in
if (!isset($_SESSION['receptionist_id'])) {
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
                    <h4 class="card-title">Edit Receptionists</h4>
                   
                    <div class="table-responsive">
    <!-- Button to Add Receptionist -->
    <a href="manage-receptionists.php" class="btn btn-success mb-3">Back To Receptionist</a>

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
    
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <!-- Chartjs -->
    <script src="./plugins/chart.js/Chart.bundle.min.js"></script>
    <!-- Circle progress -->
    <script src="./plugins/circle-progress/circle-progress.min.js"></script>
 
 
    <?php
// Include database connection
include('db_connection.php');

// Check if the receptionist ID is passed in the URL
if (isset($_GET['receptionist_id'])) {
    $receptionist_id = $_GET['receptionist_id'];

    // Fetch the receptionist's details from the database
    $stmt = $db->prepare("SELECT * FROM receptionist WHERE receptionist_id = :id");
    $stmt->bindParam(':id', $receptionist_id, PDO::PARAM_INT);
    $stmt->execute();
    $receptionist = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the receptionist does not exist, redirect to the manage receptionist page
    if (!$receptionist) {
        header('Location: manage-receptionists.php');
        exit();
    }
} else {
    // If no receptionist_id is provided, redirect to the manage receptionist page
    header('Location: manage-receptionists.php');
    exit();
}
?>

    <form method="POST" action="processEdit.php" enctype="multipart/form-data">
        <input type="hidden" name="receptionist_id" value="<?php echo $receptionist['receptionist_id']; ?>">

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($receptionist['first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($receptionist['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($receptionist['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input type="tel" class="form-control" name="contact_number" value="<?php echo htmlspecialchars($receptionist['contact_number']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" required>
                <option value="active" <?php echo ($receptionist['status'] == 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo ($receptionist['status'] == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" name="gender" required>
                <option value="Male" <?php echo ($receptionist['gender'] == 'Male' ? 'selected' : ''); ?>>Male</option>
                <option value="Female" <?php echo ($receptionist['gender'] == 'Female' ? 'selected' : ''); ?>>Female</option>
                <option value="Other" <?php echo ($receptionist['gender'] == 'Other' ? 'selected' : ''); ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control" name="profile_picture">
            <?php if ($receptionist['profile_picture']): ?>
                <img src="uploads/<?php echo htmlspecialchars($receptionist['profile_picture']); ?>" class="img img-rounded mt-2" alt="Profile Picture" width="50" height="50">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Update Receptionist</button>
    </form>
</div>

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
