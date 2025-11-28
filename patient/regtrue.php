<?php
session_start();
if (isset($_SESSION['registration_success'])) {
    $success_message = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
    header("refresh:3;url=dash.php"); // Redirect to dash.php after 3 seconds
} else {
    header("Location: index.php"); // Redirect if accessed directly
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="alert alert-success text-center">
            <?php echo $success_message; ?><br>
            Redirecting to your dashboard...
        </div>
    </div>
</div>
</body>
</html>
