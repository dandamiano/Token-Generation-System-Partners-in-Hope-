<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parteners_in_hope";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session
session_start();

// Check if the user is logged in as a patient
if (!isset($_SESSION['patient_id'])) {
    header("Location: index.php");
    exit();
}

// Get the patient_id from the session
$patient_id = $_SESSION['patient_id'];

// Query to fetch patient details
$query_patient = "SELECT * FROM patient WHERE patient_id = '$patient_id'";
$result_patient = mysqli_query($conn, $query_patient);
$patient = mysqli_fetch_assoc($result_patient);

// Handle the profile update
if (isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];

    // Update query
    $update_query = "UPDATE patient SET 
                        first_name = '$first_name',
                        last_name = '$last_name',
                        email = '$email',
                        contact_number = '$contact_number',
                        address = '$address',
                        gender = '$gender'
                    WHERE patient_id = '$patient_id'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />
  
    <title>Partners in Hope </title>
    <!-- Favicon icon -->
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Font Awesome for checkmark icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

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

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.html">
                    <span class="brand-title text-white">Partners in Hope</span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
      <?php
      include('header.php');
      include('side.php');
      ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">My Profile</h4>
                                <ul class="nav nav-tabs" id="patientTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab">
                                            Profile
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="update-profile-tab" data-toggle="tab" href="#update-profile" role="tab">
                                            Update Profile
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="patientTabContent">
                                    <!-- Profile Tab -->
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <h4 class="mt-3">Personal Information</h4>
                                        <table class="table">
                                            <tr>
                                                <th>First Name:</th>
                                                <td><?php echo htmlspecialchars($patient['first_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Last Name:</th>
                                                <td><?php echo htmlspecialchars($patient['last_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td><?php echo htmlspecialchars($patient['email']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Contact Number:</th>
                                                <td><?php echo htmlspecialchars($patient['contact_number']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth:</th>
                                                <td><?php echo htmlspecialchars($patient['date_of_birth']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Address:</th>
                                                <td><?php echo htmlspecialchars($patient['address']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Registration Date:</th>
                                                <td><?php echo htmlspecialchars($patient['registration_date']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td><?php echo htmlspecialchars($patient['status']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Gender:</th>
                                                <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Profile Picture:</th>
                                                <td><img src="<?php echo htmlspecialchars($patient['profile_picture']); ?>" alt="Profile Picture" width="100"></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- Update Profile Tab -->
                                    <div class="tab-pane fade" id="update-profile" role="tabpanel" aria-labelledby="update-profile-tab">
                                        <h4 class="mt-3">Update Your Information</h4>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="first_name">First Name:</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="last_name">Last Name:</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email:</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_number">Contact Number:</label>
                                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($patient['contact_number']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address:</label>
                                                <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($patient['address']); ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="gender">Gender:</label>
                                                <select class="form-control" id="gender" name="gender" required>
                                                    <option value="Male" <?php if($patient['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                                    <option value="Female" <?php if($patient['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
 
    <script src="js/custom.min.js"></script>
    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <!-- Chartjs -->
    <script src="./plugins/chart.js/Chart.bundle.min.js"></script>
    <!-- Circle progress -->
    <script src="./plugins/circle-progress/circle-progress.min.js"></script>
    <!-- Datamap -->
    <script src="./plugins/d3v3/index.js"></script>
    <script src="./plugins/topojson/topojson.min.js"></script>
    <script src="./plugins/datamaps/datamaps.world.min.js"></script>
    <!-- Morrisjs -->
    <script src="./plugins/raphael/raphael.min.js"></script>
    <script src="./plugins/morris/morris.min.js"></script>
    <!-- Pignose Calender -->
    <script src="./plugins/moment/moment.min.js"></script>
    <script src="./plugins/pg-calendar/js/pignose.calendar.min.js"></script>
    <!-- ChartistJS -->
    <script src="./plugins/chartist/js/chartist.min.js"></script>
    <script src="./plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>

    <script src="./js/dashboard/dashboard-1.js"></script>

    <!-- Scripts -->
    <script src="js/custom.min.js"></script>
    <script src="js/common.min.js"></script>
</body>
</html>
