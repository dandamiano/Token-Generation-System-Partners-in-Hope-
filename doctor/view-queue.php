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

// Check if the user is logged in as a doctor
if (!isset($_SESSION['doctor_id'])) {
    header("Location: index.php");
    exit();
}

// Get the doctor_id from the session
$doctor_id = $_SESSION['doctor_id'];

// Fetch all tokens associated with scheduled appointments for the doctor, excluding completed ones, ordered by token number
$query_queue = "
    SELECT t.token_number, t.status AS token_status, a.status AS appointment_status, t.patient_id, 
           p.first_name AS patient_first_name, p.last_name AS patient_last_name
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    JOIN patient p ON t.patient_id = p.patient_id
    WHERE a.status = 'scheduled' AND t.status != 'completed' AND a.doctor_id = '$doctor_id'  -- Filter by doctor
    ORDER BY t.token_number ASC
";
$result_queue = mysqli_query($conn, $query_queue);

// Determine the position in line for each token
$queue = [];
$position = 1;
while ($row = mysqli_fetch_assoc($result_queue)) {
    $queue[] = [
        'position' => $position,
        'token_number' => $row['token_number'],
        'token_status' => $row['token_status'],
        'appointment_status' => $row['appointment_status'],
        'patient_first_name' => $row['patient_first_name'],
        'patient_last_name' => $row['patient_last_name']
    ];
    $position++;
}

// Assuming the currently called patient is the one at position 1 (first in line)
$current_patient_position = 1;
$next_patient_position = 2; // Next in line after the current patient
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Partners in Hope</title>
    <!-- Favicon icon -->
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

    <?php include('header.php'); ?>
    <?php include('side.php'); ?>

    <!-- Content body start -->
    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body" id="queue-div">
                            <h4 class="card-title">My Queue</h4>
                            <p>Total number of patients in the queue: <strong><?php echo count($queue); ?></strong></p>
        
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Token Number</th>
                                        <th>Patient Name</th>
                                        <th>Token Status</th>
                                        <th>Appointment Status</th>
                                    </tr>
                                </thead>
                                <tbody id="queue-tbody">
                                    <?php
                                    foreach ($queue as $index => $item) {
                                        $class = '';
                                        if ($item['position'] == $current_patient_position) {
                                            $class = 'bg-primary text-white'; // Current patient
                                        } elseif ($item['position'] == $next_patient_position) {
                                            $class = 'bg-warning'; // Next patient
                                        }

                                        echo "<tr class='$class'>
                                                <td>" . htmlspecialchars($item['position']) . "</td>
                                                <td>" . htmlspecialchars($item['token_number']) . "</td>
                                                <td>" . htmlspecialchars($item['patient_first_name'] . " " . $item['patient_last_name']) . "</td>
                                                <td>" . htmlspecialchars($item['token_status']) . "</td>
                                                <td>" . htmlspecialchars($item['appointment_status']) . "</td>
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
    <!-- Content body end -->
</div>
<!-- Main wrapper end -->

<!-- Scripts -->
<script src="./plugins/common/common.min.js"></script>
<script src="./js/custom.min.js"></script>
<script src="./js/settings.js"></script>
<script src="./js/gleek.js"></script>
<script src="./js/styleSwitcher.js"></script>

</body>
</html>
