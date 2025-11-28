<!--**********************************
    Sidebar start
***********************************-->
<div class="nk-sidebar">           
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">Dashboard</li>
            <li>
                <a href="dash.php" aria-expanded="false">
                    <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard Home</span>
                </a>
            </li>

            <li class="nav-label">Appointments</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-calendar-alt menu-icon"></i><span class="nav-text">Manage Appointments</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="schedule-appointment.php">Schedule Appointment</a></li>
                    <li><a href="view-appointments.php">My Appointments</a></li>
                    <li><a href="view-queue.php">View Queue Position</a></li> <!-- New View Queue link -->
                    <li><a href="appointment-history.php">Appointment History</a></li>
                </ul>
            </li>
        
<li class="nav-label">  Notifications & Updates 
  
</li>

            <li>
                <a href="notifications.php" aria-expanded="false">
                    <i class="icon-bell menu-icon"></i><span class="nav-text">My Notifications   <?php
// Fetch unread notifications count
$query_unread_count = "
    SELECT COUNT(*) AS unread_count
    FROM notifications
    WHERE patient_id = '$patient_id' AND status = 'unread'
";
$result_unread_count = mysqli_query($conn, $query_unread_count);
$row_unread_count = mysqli_fetch_assoc($result_unread_count);
$unread_count = $row_unread_count['unread_count'];
?>  <?php if ($unread_count > 0): ?>
    <span class='badge badge-danger text-white'><?php echo $unread_count; ?></span>
 <?php endif; ?></span>
                </a>
            </li>

            <li class="nav-label">Personal Information</li>
            <li>
                <a href="profile.php" aria-expanded="false">
                    <i class="icon-user menu-icon"></i><span class="nav-text">My Profile</span>
                </a>
            </li>

            <li class="nav-label">Help & Support</li>
            <li>
                <a href="help.php" aria-expanded="false">
                    <i class="icon-help menu-icon"></i><span class="nav-text">Help</span>
                </a>
            </li>

            <li class="nav-label">Logout</li>
            <li>
                <a href="logout.php" aria-expanded="false">
                    <i class="icon-logout menu-icon"></i><span class="nav-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
