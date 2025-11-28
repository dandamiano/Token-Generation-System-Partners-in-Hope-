<?php
// Start session and check if the user is logged in
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Check if the patient_id is set
if (isset($_GET['patient_id'])) {
    // Get the doctor ID from the query string
    $patient_id = $_GET['patient_id'];

    try {
        // Prepare a delete statement to remove the doctor from the database
        $stmt = $db->prepare("DELETE FROM patient WHERE patient_id = :patient_id");
        $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
        
        // Execute the query
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['message'] = "Patient deleted successfully!";
            $_SESSION['msg_type'] = 'success';
        } else {
            // Set failure message
            $_SESSION['message'] = "Error: Could not delete Patient.";
            $_SESSION['msg_type'] = 'danger';
        }
    } catch (PDOException $e) {
        // If an error occurs, set the error message
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = 'danger';
    }
    
    // Redirect to the doctor management page
    header('Location: manage-patients.php');
    exit();
} else {
    // If no patient_id is set, redirect with an error message
    $_SESSION['message'] = "Error: No Patient selected.";
    $_SESSION['msg_type'] = 'danger';
    header('Location: manage-patients.php');
    exit();
}
?>

