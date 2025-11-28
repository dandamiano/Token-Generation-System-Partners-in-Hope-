<?php
// Start the session
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    // Redirect to login page if the doctor is not logged in
    header("Location: login.php");
    exit;
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the doctor ID from the session
$doctor_id = $_SESSION['doctor_id'];  // Now $_SESSION['doctor_id'] is set

// Query to count appointments by status, filtered by the logged-in doctor's ID
$query = "
    SELECT 
        COUNT(*) AS scheduled_count 
    FROM appointments 
    WHERE status = 'scheduled' AND doctor_id = '$doctor_id'
";
$result = mysqli_query($connection, $query);
$scheduled_count = mysqli_fetch_assoc($result)['scheduled_count'];

$query = "
    SELECT 
        COUNT(*) AS rescheduled_count 
    FROM appointments 
    WHERE status = 'rescheduled' AND doctor_id = '$doctor_id'
";
$result = mysqli_query($connection, $query);
$rescheduled_count = mysqli_fetch_assoc($result)['rescheduled_count'];

$query = "
    SELECT 
        COUNT(*) AS completed_count 
    FROM appointments 
    WHERE status = 'completed' AND doctor_id = '$doctor_id'
";
$result = mysqli_query($connection, $query);
$completed_count = mysqli_fetch_assoc($result)['completed_count'];

$query = "
    SELECT 
        COUNT(*) AS cancelled_count 
    FROM appointments 
    WHERE status = 'cancelled' AND doctor_id = '$doctor_id'
";
$result = mysqli_query($connection, $query);
$cancelled_count = mysqli_fetch_assoc($result)['cancelled_count'];

// Return the counts as JSON
echo json_encode([
    'scheduled' => $scheduled_count,
    'rescheduled' => $rescheduled_count,
    'completed' => $completed_count,
    'cancelled' => $cancelled_count
]);

// Close the database connection
mysqli_close($connection);
?>
