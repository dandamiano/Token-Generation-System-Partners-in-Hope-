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

// Query to fetch notifications for the logged-in patient
$query_notifications = "
    SELECT notification_id, title, message, status, created_at
    FROM notifications
    WHERE patient_id = '$patient_id'
    ORDER BY created_at DESC
";

$result_notifications = mysqli_query($conn, $query_notifications);

// Function to mark notification as read
if (isset($_GET['mark_read_id'])) {
    $notification_id = $_GET['mark_read_id'];
    $update_query = "UPDATE notifications SET status = 'read' WHERE notification_id = '$notification_id' AND patient_id = '$patient_id'";
    mysqli_query($conn, $update_query);
    header("Location: notifications.php"); // Reload the page after update
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
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Font Awesome for checkmark icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                    <span class="brand-title text-white">Partners in Hope</span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
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
                                <h4 class="card-title">My Notifications</h4>
                                <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="unread-notifications-tab" data-toggle="tab" href="#unread-notifications" role="tab">
                                            Unread Notifications <span class="badge badge-danger"><?php echo getNotificationCount($conn, $patient_id, 'unread'); ?></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="read-notifications-tab" data-toggle="tab" href="#read-notifications" role="tab">
                                            Read Notifications <span class="badge badge-success"><?php echo getNotificationCount($conn, $patient_id, 'read'); ?></span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="notificationTabContent">
                                    <!-- Unread Notifications Tab -->
                                    <div class="tab-pane fade show active" id="unread-notifications" role="tabpanel" aria-labelledby="unread-notifications-tab">
                                        <h4 class="mt-3">Unread Notifications</h4>
                                        <?php displayNotifications($result_notifications, 'unread'); ?>
                                    </div>

                                    <!-- Read Notifications Tab -->
                                    <div class="tab-pane fade" id="read-notifications" role="tabpanel" aria-labelledby="read-notifications-tab">
                                        <h4 class="mt-3">Read Notifications</h4>
                                        <?php displayNotifications($result_notifications, 'read'); ?>
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
    <script src="js/custom.min.js"></script>
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

<?php
// Function to get notification count by status (read/unread)
function getNotificationCount($conn, $patient_id, $status) {
    $query = "SELECT COUNT(*) AS count FROM notifications WHERE patient_id = '$patient_id' AND status = '$status'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

// Function to display notifications based on status filter
function displayNotifications($result, $statusFilter = null) {
    echo '<table class="table table-striped table-bordered">';
    echo '<thead><tr>
            <th>Title</th>
            <th>Message</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
          </tr></thead><tbody>';
    
    if (mysqli_num_rows($result) > 0) {
        mysqli_data_seek($result, 0); // Reset pointer
        while ($row = mysqli_fetch_assoc($result)) {
            if ($statusFilter === null || strtolower($row['status']) === $statusFilter) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['title']) . "</td>
                        <td>" . htmlspecialchars($row['message']) . "</td>
                        <td>" . htmlspecialchars(ucfirst($row['status'])) . "</td>
                        <td>" . htmlspecialchars(date("M d, Y h:i A", strtotime($row['created_at']))) . "</td>
                        <td>";
                if ($row['status'] == 'unread') {
                    echo '<a href="?mark_read_id=' . $row['notification_id'] . '" class="text-success">
                            <i class="fas fa-check-circle"></i> Mark as Read
                          </a>';
                }
                echo "</td></tr>";
            }
        }
    } else {
        echo "<tr><td colspan='5'>No notifications found</td></tr>";
    }
    echo '</tbody></table>';
}

?>
