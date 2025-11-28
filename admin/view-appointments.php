<?php 
// Start the session
session_start();

// Include database connection
include('db-conn.php');

// Check if the user is already logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the dashboard if already logged in
    header("Location: index.php");
    exit();
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
    <!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet"> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    <h4 class="card-title">All Appointments</h4>
                    <div class="row">
    <div class="col-md-3">
        <div class="card gradient-1 mb-3">
            <div class="card-header"><i class="fas fa-clock"></i> Scheduled</div>
            <div class="card-body">
                <h5 class="card-title" id="scheduled-count">0</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white gradient-4 mb-3">
            <div class="card-header"><i class="fas fa-sync-alt"></i> Rescheduled</div>
            <div class="card-body">
                <h5 class="card-title" id="rescheduled-count">0</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white gradient-3 mb-3">
            <div class="card-header"><i class="fas fa-check-circle"></i> Completed</div>
            <div class="card-body">
                <h5 class="card-title" id="completed-count">0</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white gradient-2 mb-3">
            <div class="card-header"><i class="fas fa-times-circle"></i> Cancelled</div>
            <div class="card-body">
                <h5 class="card-title" id="cancelled-count">0</h5>
            </div>
        </div>
    </div>
</div>

                    <div class="table-responsive">
                        
                       
<table  id="appointments-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            
            <th>Token Number</th>
            <th>Patient Name</th>
            <th>Doctor</th>
            <th>Appointment Date</th>
            <th>Reason</th>
            <th>Appointment Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="appointments-table-body">
        <!-- Data will be loaded here by AJAX -->
    </tbody>
</table>
  <!-- Reschedule Modal -->
  <div class="modal" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rescheduleModalLabel">Reschedule Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="rescheduled-date">New Appointment Date:</label>
                    <input type="date" id="rescheduled-date" class="form-control">
                    <label for="rescheduled-time">New Appointment Time:</label>
                    <input type="time" id="rescheduled-time" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-reschedule">Save Reschedule</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // JavaScript for handling status changes and opening the reschedule modal
        function handleStatusChange(appointmentId, status) {
            if (status === 'rescheduled') {
                // Show the modal when "Rescheduled" is selected
                openRescheduleModal(appointmentId);
            } else {
                // Update status without rescheduling
                updateStatus(appointmentId, status);
            }
        }

        function openRescheduleModal(appointmentId) {
            $('#rescheduleModal').modal('show'); // Show the modal
            $('#save-reschedule').data('appointment-id', appointmentId); // Set the appointment ID on the save button
        }

        // Save reschedule data
        $('#save-reschedule').on('click', function() {
            var appointmentId = $(this).data('appointment-id');
            var newDate = $('#rescheduled-date').val();
            var newTime = $('#rescheduled-time').val();

            if (!newDate || !newTime) {
                alert('Please provide both date and time.');
                return;
            }

            // Combine the date and time into one string (e.g., 2024-11-09 14:30:00)
            var newDateTime = newDate + ' ' + newTime + ':00';

            $.ajax({
                url: 'update-status1.php', // PHP file to update status
                method: 'POST',
                data: {
                    appointment_id: appointmentId,
                    status: 'rescheduled', // Set the status to 'rescheduled'
                    new_date_time: newDateTime // Send the new date and time
                },
                success: function(response) {
                    alert(response); // Show success message
                    $('#rescheduleModal').modal('hide'); // Close the modal
                    loadAppointments();  // Reload appointments dynamically
                },
                error: function() {
                    alert('There was an error rescheduling the appointment.');
                }
            });
        });

        // Function to update appointment status
        function updateStatus(appointmentId, status) {
            $.ajax({
                url: 'update-status1.php',  // PHP file to update status
                method: 'POST',
                data: { appointment_id: appointmentId, status: status },
                success: function(response) {
                    alert(response); // Show success message
                    loadAppointments();  // Reload appointments dynamically
                }
            });
        }
    </script>
<script>
// Function to initialize DataTables
function initializeDataTable() {
    $('#appointments-table').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "lengthChange": false // Optional: You can allow the user to change the number of rows displayed.
    });
}

// Function to load appointments via AJAX
function loadAppointments() {
    $.ajax({
        url: 'load-appointments.php',  // PHP file to fetch appointments
        method: 'GET',
        success: function(data) {
            $('#appointments-table-body').html(data);
            initializeDataTable();  // Initialize DataTable after data is loaded
        }
    });
}

// Function to update appointment status
function updateStatus(appointmentId, status) {
    $.ajax({
        url: 'update-status.php',  // PHP file to update status
        method: 'POST',
        data: { appointment_id: appointmentId, status: status },
        success: function(response) {
            alert(response); // Show success message
            loadAppointments();  // Reload appointments dynamically
            loadAppointmentCounts(); // Reload status counts dynamically
        }
    });
}

// Function to assign doctor
function assignDoctor(appointmentId, doctorId) {
    $.ajax({
        url: 'assign-doctor.php',  // PHP file to assign doctor
        method: 'POST',
        data: { appointment_id: appointmentId, doctor_id: doctorId },
        success: function(response) {
            alert(response); // Show success message
            loadAppointments();  // Reload appointments dynamically
            loadAppointmentCounts(); // Reload status counts dynamically
        }
    });
}

// Function to load appointment status counts
function loadAppointmentCounts() {
    $.ajax({
        url: 'load-appointment-counts.php',  // PHP file to fetch status counts
        method: 'GET',
        success: function(data) {
            // Assuming the response is in the format { scheduled: 5, rescheduled: 10, completed: 3, cancelled: 1 }
            var counts = JSON.parse(data);
            $('#scheduled-count').text(counts.scheduled);
            $('#rescheduled-count').text(counts.rescheduled);
            $('#completed-count').text(counts.completed);
            $('#cancelled-count').text(counts.cancelled);
        }
    });
}

// Load appointments and status counts initially
$(document).ready(function() {
    loadAppointments();
    loadAppointmentCounts();  // Load status counts on page load
});

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

</body>

</html>
