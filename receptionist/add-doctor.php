
<?php
session_start();
require_once 'db_connection.php'; // Ensure your database connection is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];
    $specialization = $_POST['specialization'];
    $available_days = $_POST['available_days'];
    $status = $_POST['status'];
    $gender = $_POST['gender'];

    // Handle file upload for profile picture
    $profile_picture = $_FILES['profile_picture']['name'];
    $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_path = 'uploads/' . $profile_picture;
    move_uploaded_file($profile_picture_tmp, $profile_picture_path);

    // Check if the email already exists
    $stmt = $db->prepare("SELECT COUNT(*) FROM doctor WHERE email = ?");
    $stmt->execute([$email]);
    $email_exists = $stmt->fetchColumn();

    if ($email_exists > 0) {
        $_SESSION['message'] = "Email already exists. Please use a different email address.";
        $_SESSION['msg_type'] = 'danger';
        header("Location: add-doctor.php");
        exit();
    }

    // Hash the password before inserting
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new doctor into the database
    $stmt = $db->prepare("INSERT INTO doctor (first_name, last_name, email, contact_number, password, specialization, available_days, status, gender, profile_picture) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $email, $contact_number, $hashed_password, $specialization, $available_days, $status, $gender, $profile_picture]);

    $_SESSION['message'] = "Doctor added successfully!";
    $_SESSION['msg_type'] = 'success';
    header("Location: manage-doctors.php");
}
?>
