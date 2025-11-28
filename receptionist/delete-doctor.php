<?php
// Start session and check if the user is logged in
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Check if the doctor_id is set
if (isset($_GET['doctor_id'])) {
    // Get the doctor ID from the query string
    $doctor_id = $_GET['doctor_id'];

    try {
        // Prepare a delete statement to remove the doctor from the database
        $stmt = $db->prepare("DELETE FROM doctor WHERE doctor_id = :doctor_id");
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        
        // Execute the query
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['message'] = "Doctor deleted successfully!";
            $_SESSION['msg_type'] = 'success';
        } else {
            // Set failure message
            $_SESSION['message'] = "Error: Could not delete doctor.";
            $_SESSION['msg_type'] = 'danger';
        }
    } catch (PDOException $e) {
        // If an error occurs, set the error message
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = 'danger';
    }
    
    // Redirect to the doctor management page
    header('Location: manage-doctors.php');
    exit();
} else {
    // If no doctor_id is set, redirect with an error message
    $_SESSION['message'] = "Error: No doctor selected.";
    $_SESSION['msg_type'] = 'danger';
    header('Location: manage-doctors.php');
    exit();
}
?>

