<?php
// Include database connection
include('db_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $receptionist_id = $_POST['receptionist_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    $gender = $_POST['gender'];
    $profile_picture = $_FILES['profile_picture']['name'];

    // Handle profile picture upload
    if ($profile_picture != '') {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        // If no new picture is uploaded, keep the existing one
        $stmt = $db->prepare("SELECT profile_picture FROM receptionist WHERE receptionist_id = :id");
        $stmt->bindParam(':id', $receptionist_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $profile_picture = $result['profile_picture'];
    }

    // If password is not empty, hash it
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // If password is empty, keep the current one
        $stmt = $db->prepare("SELECT password FROM receptionist WHERE receptionist_id = :id");
        $stmt->bindParam(':id', $receptionist_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $result['password'];
    }

    // Update receptionist details in the database
    $stmt = $db->prepare("UPDATE receptionist 
                          SET first_name = ?, last_name = ?, email = ?, contact_number = ?, password = ?, status = ?, gender = ?, profile_picture = ? 
                          WHERE receptionist_id = ?");
    $stmt->execute([$first_name, $last_name, $email, $contact_number, $hashed_password, $status, $gender, $profile_picture, $receptionist_id]);

    // Redirect to the manage receptionist page after update
    header('Location: manage-receptionists.php');
    exit();
}
