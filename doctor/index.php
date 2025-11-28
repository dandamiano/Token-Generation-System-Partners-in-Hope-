<?php
session_start(); // Start the session

// Check if the admin is already logged in
if (isset($_SESSION['doctor_id'])) {
    header("Location: dash.php"); // Redirect to the dashboard if logged in
    exit();
}

// Include your database connection here
$db = new PDO('mysql:host=localhost;dbname=parteners_in_hope;charset=utf8mb4', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query to check user credentials
    $stmt = $db->prepare("SELECT * FROM doctor WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password (make sure to hash your passwords when storing)
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['doctor_id'] = $user['doctor_id']; // Store admin ID in session
        header("Location: dash.php"); // Redirect to dashboard
        exit();
    } else {
        $error = "Invalid email or password"; // Error message for invalid credentials
    }
}
?>
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Partners in Hope </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="h-100">
    
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                               <center>
                               <div class="logo text-center"><img  src="logo.png"> </div>
                               <style>
    .logo img {
        object-fit: cover; /* or use 'contain' or 'fill' as needed */
        width: 50%;       /* Ensure the image scales with the container */
        height: auto;      /* Maintain aspect ratio */
    }
</style>

                               </center>  
                               <a class="text-center mt-3" href="index.html"> <h4>Doctor Portal</h4></a>
                               <div class="container mt-5">
        <h2 class="text-center">Sign In</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form class="mt-4 mb-5" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
</body>
</html>
