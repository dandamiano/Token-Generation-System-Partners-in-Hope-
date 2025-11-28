<?php
session_start(); // Start the session

// Check if the patient is already logged in
if (isset($_SESSION['patient_id'])) {
    header("Location: dash.php"); // Redirect to the dashboard if logged in
    exit();
}

// Include your database connection here
$db = new PDO('mysql:host=localhost;dbname=parteners_in_hope;charset=utf8mb4', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the patient details from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_number = $_POST['contact_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
    } else {
        $profile_picture = null; // Set to null if no picture uploaded
    }

    // Check if the email already exists in the database
    $stmt = $db->prepare("SELECT * FROM patient WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = "The email address is already registered. Please use a different email.";
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute the query to insert the new patient into the database
        $stmt = $db->prepare("INSERT INTO patient (first_name, last_name, email, password, contact_number, date_of_birth, address, gender, profile_picture, registration_date) VALUES (:first_name, :last_name, :email, :password, :contact_number, :date_of_birth, :address, :gender, :profile_picture, NOW())");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':profile_picture', $profile_picture);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            $_SESSION['registration_success'] = "Registration successful! Welcome, $first_name.";
            $_SESSION['patient_id'] = $db->lastInsertId(); // Store patient ID in session
            header("Location:regtrue.php"); // Redirect to success page
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <!-- Registration Form -->
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="card shadow">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="logo.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
                    <h3 class="mt-2">Patient Registration</h3>
                </div>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select name="gender" class="form-control" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                    </div>
                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                    <p class="text-center mt-3">Already have an account? <a href="index.php" class="text-primary">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
