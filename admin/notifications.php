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
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in admin's ID from the session
$admin_id = $_SESSION['admin_id'];

// Query to fetch unread notifications only for the logged-in admin
$query_unread = "
    SELECT * FROM admin_notifications 
    WHERE admin_id = '$admin_id' AND status = 'unread'
    ORDER BY created_at DESC
";

// Query to fetch read notifications only for the logged-in admin
$query_read = "
    SELECT * FROM admin_notifications 
    WHERE admin_id = '$admin_id' AND status = 'read'
    ORDER BY created_at DESC
";

// Execute the queries
$result_unread = mysqli_query($conn, $query_unread);
$result_read = mysqli_query($conn, $query_read);

// Count the number of unread and read notifications
$unread_count = mysqli_num_rows($result_unread);
$read_count = mysqli_num_rows($result_read);

// Mark a notification as read when clicked
if (isset($_GET['mark_as_read'])) {
    $notification_id = $_GET['mark_as_read'];

    // Update notification status to 'read'
    $update_query = "UPDATE admin_notifications SET status = 'read' WHERE notification_id = '$notification_id' AND admin_id = '$admin_id'";

    if (mysqli_query($conn, $update_query)) {
        // Redirect to refresh the page after marking as read
        header("Location: notifications.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Partners in Hope</title>
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

    <!-- Main wrapper start -->
    <div id="main-wrapper">
        <!-- Nav header start -->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.html">
                    <span class="brand-title text-white">Partners in Hope</span>
                </a>
            </div>
        </div>
        <!-- Nav header end -->

        <?php include('header.php'); include('side.php'); ?>

        <!-- Content body start -->
        <!-- Main content area -->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                              
                                <!-- Notifications Section -->
                                <h4 class="mt-3">Admin Notifications</h4>

                                <!-- Notifications Table -->
                       <!-- Main content area -->
          <!-- Notifications Tabs -->
          <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="unread-tab" data-toggle="tab" href="#unread">
                                            Unread
                                            <span class="badge badge-danger ml-2"><?php echo $unread_count; ?></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="read-tab" data-toggle="tab" href="#read">
                                            Read
                                            <span class="badge badge-success ml-2"><?php echo $read_count; ?></span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- Unread Notifications -->
                                    <div class="tab-pane active" id="unread">
                                        <h4 class="mt-3">Unread Notifications</h4>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Message</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Check if there are unread notifications for the admin
                                                if ($unread_count > 0) {
                                                    // Display each unread notification
                                                    while ($row = mysqli_fetch_assoc($result_unread)) {
                                                        echo "<tr>
                                                                <td>" . htmlspecialchars($row['title']) . "</td>
                                                                <td>" . htmlspecialchars($row['message']) . "</td>
                                                                <td>" . htmlspecialchars(date("M d, Y h:s A", strtotime($row['created_at']))) . "</td>
                                                                <td><a href='?mark_as_read=" . $row['notification_id'] . "' class='btn btn-primary'>Mark as Read</a></td>
                                                            </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>No unread notifications</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Read Notifications -->
                                    <div class="tab-pane" id="read">
                                        <h4 class="mt-3">Read Notifications</h4>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Message</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Check if there are read notifications for the admin
                                                if ($read_count > 0) {
                                                    // Display each read notification
                                                    while ($row = mysqli_fetch_assoc($result_read)) {
                                                        echo "<tr>
                                                                <td>" . htmlspecialchars($row['title']) . "</td>
                                                                <td>" . htmlspecialchars($row['message']) . "</td>
                                                                <td>" . htmlspecialchars(date("M d, Y h:s A", strtotime($row['created_at']))) . "</td>
                                                            </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='3'>No read notifications</td></tr>";
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
