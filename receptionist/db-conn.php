<?php
// Database configuration
$host = 'localhost'; // Your database host
$dbname = 'parteners_in_hope'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Create a new PDO instance
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
    exit; // Stop further execution if connection fails
}
// Ensure the admin is logged in
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in admin's ID from the session
$receptionist_id = $_SESSION['receptionist_id'];
?>

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