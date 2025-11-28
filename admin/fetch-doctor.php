<?php

require 'db_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the doctor_id from the POST request
    $doctor_id = $_POST['doctor_id'];

    try {
        // Prepare SQL query to fetch doctor data
        $stmt = $db->prepare("SELECT * FROM doctor WHERE doctor_id = :doctor_id");
        $stmt->bindParam(':doctor_id', $doctor_id);
        $stmt->execute();

        // Fetch the doctor's data
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the data as JSON
        echo json_encode($doctor);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
