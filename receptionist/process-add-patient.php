<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $gender = $_POST['gender'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "../patient/uploads/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        $profile_picture = null;
    }

    // Insert patient into the database
    $sql = "INSERT INTO patient (first_name, last_name, email, contact_number, password, date_of_birth, address, registration_date, status, gender, profile_picture) 
            VALUES (:first_name, :last_name, :email, :contact_number, :password, :date_of_birth, :address, NOW(), :status, :gender, :profile_picture)";
    
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contact_number', $contact_number);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':profile_picture', $profile_picture);

    // Execute and check if successful
    if ($stmt->execute()) {
        $_SESSION['message'] = "Patient added successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to add patient.";
        $_SESSION['msg_type'] = "danger";
    }

    // Redirect to the patient management page
    header("Location: manage-patients.php");
    exit();
} else {
    // If not a POST request, redirect to the form
    header("Location: add-patient.php");
    exit();
}
?>
