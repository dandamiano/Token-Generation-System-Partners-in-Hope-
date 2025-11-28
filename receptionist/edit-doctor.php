<?php
session_start(); // Start the session

// Include database connection file
require 'db_connection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $doctor_id = $_POST['doctor_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $specialization = $_POST['specialization'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $status = $_POST['status'];

    // Handle profile picture upload if a new file is uploaded
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Get file details
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_size = $_FILES['profile_picture']['size'];
        $file_type = $_FILES['profile_picture']['type'];
        
        // Define upload directory and allowed file types (e.g., jpeg, png, jpg)
        $upload_dir = 'uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (in_array($file_type, $allowed_types)) {
            // Generate a unique file name to prevent overwriting
            $unique_file_name = uniqid('doctor_') . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
            $destination = $upload_dir . $unique_file_name;

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($file_tmp, $destination)) {
                $profile_picture = $unique_file_name; // Set the profile picture name to be saved in the database
            } else {
                $_SESSION['message'] = "Error uploading the profile picture.";
                $_SESSION['msg_type'] = "danger";
                header("Location: manage-users.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Invalid file type for profile picture.";
            $_SESSION['msg_type'] = "danger";
            header("Location: manage-users.php");
            exit();
        }
    }

    // Update doctor information in the database
    try {
        // SQL query to update doctor data (removed date_of_birth)
        $query = "UPDATE doctor SET first_name = :first_name, last_name = :last_name, specialization = :specialization, email = :email, gender = :gender, contact_number = :contact_number, status = :status";
        
        // Add profile picture to query if a new picture is uploaded
        if ($profile_picture) {
            $query .= ", profile_picture = :profile_picture";
        }
        
        // Add where condition for doctor ID
        $query .= " WHERE doctor_id = :doctor_id";

        // Prepare the SQL statement
        $stmt = $db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':specialization', $specialization);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':doctor_id', $doctor_id);

        // Bind the profile picture if uploaded
        if ($profile_picture) {
            $stmt->bindParam(':profile_picture', $profile_picture);
        }

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['message'] = "Doctor information updated successfully.";
            $_SESSION['msg_type'] = "success"; // To distinguish message types
        } else {
            $_SESSION['message'] = "Error updating doctor.";
            $_SESSION['msg_type'] = "danger"; // To distinguish message types
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger"; // To distinguish message types
    }

    // Redirect back to the manage doctors page
    header("Location: manage-doctors.php");
    exit();
} else {
    // If the form was not submitted correctly, redirect to the manage doctors page
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['msg_type'] = "danger";
    header("Location: manage-doctors.php");
    exit();
}
?>
