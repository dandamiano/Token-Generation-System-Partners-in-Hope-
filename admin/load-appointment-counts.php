<?php
// Database connection
$connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to count appointments by status
$query = "
    SELECT 
        COUNT(*) AS scheduled_count FROM appointments WHERE status = 'scheduled'
";
$result = mysqli_query($connection, $query);
$scheduled_count = mysqli_fetch_assoc($result)['scheduled_count'];

$query = "
    SELECT 
        COUNT(*) AS rescheduled_count FROM appointments WHERE status = 'rescheduled'
";
$result = mysqli_query($connection, $query);
$rescheduled_count = mysqli_fetch_assoc($result)['rescheduled_count'];

$query = "
    SELECT 
        COUNT(*) AS completed_count FROM appointments WHERE status = 'completed'
";
$result = mysqli_query($connection, $query);
$completed_count = mysqli_fetch_assoc($result)['completed_count'];

$query = "
    SELECT 
        COUNT(*) AS cancelled_count FROM appointments WHERE status = 'cancelled'
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
