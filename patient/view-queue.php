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
$patient_id = $_SESSION['patient_id'];

// Fetch the patient's token and their associated appointment status
$query_token = "
    SELECT t.token_number, t.status AS token_status, a.status AS appointment_status 
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    WHERE t.patient_id = '$patient_id' AND a.status = 'scheduled'
";
$result_token = mysqli_query($conn, $query_token);

if (!$result_token || mysqli_num_rows($result_token) == 0) {
    echo "No active token found for your account, or your appointment is not scheduled.";
    exit();
}

$patient_token = mysqli_fetch_assoc($result_token)['token_number'];

// Fetch all tokens associated with scheduled appointments, excluding completed ones, ordered by token number
$query_queue = "
    SELECT t.token_number, t.status AS token_status, a.status AS appointment_status, t.patient_id
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    WHERE a.status = 'scheduled' AND t.status != 'completed'   -- Exclude completed tokens
    ORDER BY t.token_number ASC
";
$result_queue = mysqli_query($conn, $query_queue);

// Determine the position in line
$position = 1;
$found = false;
while ($row = mysqli_fetch_assoc($result_queue)) {
    if ($row['token_number'] == $patient_token) {
        $found = true;
        break;
    }
    $position++;
}

if (!$found) {
    echo "Your position in the queue could not be determined or you have no scheduled appointment .";
    exit();
}

// Reset the pointer to the beginning of the result set for displaying the queue
mysqli_data_seek($result_queue, 0);
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
                    <!-- This is your existing div -->
<div class="card-body" id="queue-div">
    <h4 class="card-title">Queue Position</h4>
    <p>Your token number: <strong><?= $patient_token ?></strong></p>
    <p>Your current position in the queue: <strong><?= $position ?></strong></p>

    <!-- Displaying the queue -->
    <h5>Current Queue</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Position</th>
                <th>Token Number</th>
                <th>Token Status</th>
                <th>Appointment Status</th>
            </tr>
        </thead>
        <tbody id="queue-tbody">
            <!-- The queue will be dynamically loaded here -->
        </tbody>
    </table>

    <p>Thank you for your patience. Please wait until your turn.</p>
</div>

<!-- jQuery (Ensure you have included jQuery) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // Function to load the queue via AJAX
    function loadQueue() {
        $.ajax({
            url: 'fetch_queue.php', // PHP file to fetch queue data
            method: 'GET',
            success: function(data) {
                // Update the content of the div with the returned data
                $('#queue-div').html(data);
            },
            error: function() {
                alert('Error fetching queue data.');
            }
        });
    }

    // Refresh the queue every 5 seconds (you can adjust the timing)
    setInterval(loadQueue, 1000); // Reload the queue every 5 seconds
</script>

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

</body>
</html>
