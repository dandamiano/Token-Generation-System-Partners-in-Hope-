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

// Ensure the admin is logged in
if (!isset($_SESSION['receptionist_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in admin's ID from the session
$receptionist_id = $_SESSION['receptionist_id'];?><?php 
 

// Include database connection
include('db-conn.php');
  
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
                   

                    <span class="brand-title text-white">
                    Partners in Hope 

                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
       
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

      <?php
      
      include('header.php');
      
      include('side.php');
      
      ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

      
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Manage Doctors</h4>
                    <div class="table-responsive">
    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addDoctorModal">
        Add New Doctor
    </button>
    <?php
    if (isset($_SESSION['message'])) {
        $msg_type = $_SESSION['msg_type'] == 'success' ? 'alert-success' : 'alert-danger';
        echo "<div class='alert $msg_type alert-dismissible fade show' role='alert'>
                " . $_SESSION['message'] . "
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
    }
    ?>
    <table id="doctorTable" class="table table-striped">
        <thead>
            <tr>
                <th>Doctor ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Specialization</th>
                <th>Available Days</th>
                <th>Status</th>
                <th>Profile Picture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $db->query("SELECT * FROM doctor");
            while ($doctor = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                echo "<tr>
                    <td>{$doctor['doctor_id']}</td>
                    <td>{$doctor['first_name']}</td>
                    <td>{$doctor['last_name']}</td>
                    <td>{$doctor['email']}</td>
                    <td>{$doctor['gender']}</td>
                    <td>{$doctor['specialization']}</td>
                    <td>{$doctor['available_days']}</td>
                    <td>{$doctor['status']}</td>
                    <td><img src='uploads/{$doctor['profile_picture']}' class='img img-rounded' alt='Profile Picture' width='50' height='50'></td>
                    <td>
                        <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editDoctorModal' 
                                onclick='populateEditModal({$doctor['doctor_id']}, \"{$doctor['first_name']}\", \"{$doctor['last_name']}\", \"{$doctor['email']}\", \"{$doctor['gender']}\", \"{$doctor['specialization']}\", \"{$doctor['available_days']}\", \"{$doctor['status']}\", \"{$doctor['profile_picture']}\")'>Edit</button>
                  <a class='btn btn-danger btn-sm' href='delete-doctor.php?doctor_id={$doctor['doctor_id']}'>Delete</a>
       </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Add Doctor Modal Form -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1" role="dialog" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorModalLabel">Add New Doctor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="add-doctor.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="tel" class="form-control" name="contact_number" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="specialization">Specialization</label>
                                <input type="text" class="form-control" name="specialization" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="available_days">Available Days</label>
                                <input type="text" class="form-control" name="available_days" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Doctor -->
    <div class="modal fade" id="editDoctorModal" tabindex="-1" role="dialog" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDoctorModalLabel">Edit Doctor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editDoctorForm" method="POST" action="edit-doctor.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_doctor_id" name="doctor_id">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_first_name">First Name</label>
                                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_last_name">Last Name</label>
                                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_gender">Gender</label>
                                <select class="form-control" id="edit_gender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_specialization">Specialization</label>
                                <input type="text" class="form-control" id="edit_specialization" name="specialization" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_available_days">Available Days</label>
                                <input type="text" class="form-control" id="edit_available_days" name="available_days" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_profile_picture">Profile Picture</label>
                            <input type="file" class="form-control" id="edit_profile_picture" name="profile_picture">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Deleting Doctor -->
    <!-- Modal -->
<div class="modal fade" id="deleteDoctorModal" tabindex="-1" role="dialog" aria-labelledby="deleteDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDoctorModalLabel">Delete Doctor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the doctor: <strong id="doctorName"></strong>?
            </div>
            <div class="modal-footer">
                <form method="GET" action="delete-doctor.php">
                    <input type="hidden" name="doctor_id" id="doctorId">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle passing the doctor_id to the modal -->
<script>
    $('#deleteDoctorModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var doctorId = button.data('id'); // Extract doctor ID from data-id attribute
        var doctorName = button.data('name'); // Extract doctor name from data-name attribute

        // Update the modal's content
        var modal = $(this);
        modal.find('#doctorName').text(doctorName); // Set the doctor's name in the modal
        modal.find('#doctorId').val(doctorId); // Set the doctor_id in the hidden input
    });
</script>

    </div>
</div>

<script>
function populateEditModal(id, first_name, last_name, email, gender, specialization, available_days, status, profile_picture) {
    document.getElementById('edit_doctor_id').value = id;
    document.getElementById('edit_first_name').value = first_name;
    document.getElementById('edit_last_name').value = last_name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_gender').value = gender;
    document.getElementById('edit_specialization').value = specialization;
    document.getElementById('edit_available_days').value = available_days;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_profile_picture').value = profile_picture;
}


</script>


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

</body>

</html>
