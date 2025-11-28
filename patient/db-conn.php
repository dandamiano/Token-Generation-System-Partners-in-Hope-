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
?>
