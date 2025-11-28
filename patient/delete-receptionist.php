<?php
// Start session and check if the user is logged in
session_start();

// Include the database connection file
require_once 'db_connection.php';

// Check if the receptionist_id is set
if (isset($_GET['receptionist_id'])) {
    // Get the doctor ID from the query string
    $receptionist_id = $_GET['receptionist_id'];

    try {
        // Prepare a delete statement to remove the doctor from the database
        $stmt = $db->prepare("DELETE FROM receptionist WHERE receptionist_id = :receptionist_id");
        $stmt->bindParam(':receptionist_id', $receptionist_id, PDO::PARAM_INT);
        
        // Execute the query
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['message'] = "Receptionist deleted successfully!";
            $_SESSION['msg_type'] = 'success';
        } else {
            // Set failure message
            $_SESSION['message'] = "Error: Could not delete Receptionist.";
            $_SESSION['msg_type'] = 'danger';
        }
    } catch (PDOException $e) {
        // If an error occurs, set the error message
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = 'danger';
    }
    
    // Redirect to the doctor management page
    header('Location: manage-receptionists.php');
    exit();
} else {
    // If no receptionist_id is set, redirect with an error message
    $_SESSION['message'] = "Error: No Receptionist selected.";
    $_SESSION['msg_type'] = 'danger';
    header('Location: manage-receptionists.php');
    exit();
}
?>

