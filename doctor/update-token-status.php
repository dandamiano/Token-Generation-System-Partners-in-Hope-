<?php
// Database connection
$connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get data from POST request
$appointment_id = $_POST['appointment_id'];
$token_status = $_POST['token_status'];

// Check if appointment_id and token_status are provided
if (empty($appointment_id) || empty($token_status)) {
    echo "Error: Missing appointment ID or token status.";
    exit;
}

// Prepare the SQL query using a prepared statement
$query = $connection->prepare("UPDATE tokens SET status = ? WHERE appointment_id = ?");
$query->bind_param("si", $token_status, $appointment_id); // "si" means string and integer

if ($query->execute()) {
    echo "Token status updated successfully.";
} else {
    echo "Error updating token status: " . $query->error;
}

// Close the connection
$query->close();
mysqli_close($connection);
?>
