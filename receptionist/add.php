<?php
// Database connection
$db = new PDO('mysql:host=localhost;dbname=parteners_in_hope;charset=utf8mb4', 'root', '');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $contact_number = $_POST['contact_number'];
    $status = $_POST['status'];
    $profile_picture = ''; // Default empty string for profile picture
    
    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $upload_dir = 'uploads/profile_pictures/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_path = $upload_dir . $file_name;

        // Move uploaded file to the desired directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
            $profile_picture = $file_path; // Store the file path
        }
    }

    // Prepare and execute the SQL query to insert data
    $stmt = $db->prepare("INSERT INTO `admin` (first_name, last_name, email, password, contact_number, status, profile_picture, created_at, updated_at) 
                          VALUES (:first_name, :last_name, :email, :password, :contact_number, :status, :profile_picture, NOW(), NOW())");

    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':contact_number', $contact_number);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':profile_picture', $profile_picture);

    if ($stmt->execute()) {
        echo "Admin added successfully!";
    } else {
        echo "Error adding admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Admin</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control-file">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add Admin</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
