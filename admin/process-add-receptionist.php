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

<?php
session_start();
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Handle profile picture upload
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        if (getimagesize($_FILES["profile_picture"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = basename($_FILES["profile_picture"]["name"]);
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                $_SESSION['msg_type'] = "danger";
                header("Location: add-receptionist.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Sorry, the file is not an image.";
            $_SESSION['msg_type'] = "danger";
            header("Location: add-receptionist.php");
            exit();
        }
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert receptionist data into the database
    $query = "INSERT INTO receptionist (first_name, last_name, email, contact_number, password, status, gender, profile_picture) 
              VALUES ('$first_name', '$last_name', '$email', '$contact_number', '$hashed_password', '$status', '$gender', '$profile_picture')";

    try {
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Receptionist added successfully!";
            $_SESSION['msg_type'] = "success";
        }
    } catch (mysqli_sql_exception $e) {
        // Check for duplicate entry error
        if ($e->getCode() == 1062) { // MySQL duplicate entry error code
            $_SESSION['message'] = "Error: The email address '$email' is already registered.";
            $_SESSION['msg_type'] = "danger";
        } else {
            $_SESSION['message'] = "Error: " . $e->getMessage();
            $_SESSION['msg_type'] = "danger";
        }
    }

    // Redirect to the add receptionist page with the message
    header("Location: add-receptionist.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>
