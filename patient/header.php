<div class="header bg-primary">    
            <div class="header-content bg-primary clearfix">
                
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon text-white"><i class="icon-menu"></i></span>
                    </div>
                </div>
                 
                <div class="header-right">
                    <ul class="clearfix">
                       
                    <?php
// Fetch unread notifications count
$query_unread_count = "
    SELECT COUNT(*) AS unread_count
    FROM notifications
    WHERE patient_id = '$patient_id' AND status = 'unread'
";
$result_unread_count = mysqli_query($conn, $query_unread_count);
$row_unread_count = mysqli_fetch_assoc($result_unread_count);
$unread_count = $row_unread_count['unread_count'];

// Fetch notifications
$query_notifications = "
    SELECT * FROM notifications
    WHERE patient_id = '$patient_id'
    ORDER BY created_at DESC
    LIMIT 5"; // Limit to the latest 5 notifications
$result_notifications = mysqli_query($conn, $query_notifications);
?>
<li class="icons dropdown">
    <a href="javascript:void(0)" data-toggle="dropdown">
        <i class="mdi mdi-bell-outline"></i>
        <?php if ($unread_count > 0): ?>
            <span class="badge badge-pill gradient-2"><?php echo $unread_count; ?></span>
        <?php endif; ?>
    </a>
    <div class="drop-down animated fadeIn dropdown-menu dropdown-notfication">
        <div class="dropdown-content-heading d-flex justify-content-between">
            <span class=""><?php echo $unread_count; ?> New Notifications</span>  
            <a href="javascript:void()" class="d-inline-block">
                <?php if ($unread_count > 0): ?>
                    <span class="badge badge-pill gradient-2"><?php echo $unread_count; ?></span>
                <?php endif; ?>
            </a>
        </div>
        <div class="dropdown-content-body">
            <ul>
                <?php while ($notification = mysqli_fetch_assoc($result_notifications)): ?>
                    <li>
                        <a href="javascript:void()">
                            <span class="mr-3 avatar-icon bg-warning">
                                <i class="icon-bell"></i>
                            </span>
                            <div class="notification-content">
                                <h6 class="notification-heading"><?php echo $notification['title']; ?></h6>
                                <span class="notification-text"><?php echo $notification['message']; ?></span>
                            </div>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</li>

                         
                        <li class="icons dropdown">
                        <?php
// Assuming you have a patient_id session or variable
// Fetch the patient's profile picture from the database
$query_patient = "
    SELECT profile_picture 
    FROM patient
    WHERE patient_id = '$patient_id'
";
$result_patient = mysqli_query($conn, $query_patient);
$row_patient = mysqli_fetch_assoc($result_patient);
$profile_picture = $row_patient['profile_picture']; // The profile picture URL or filename

// Default image if the patient doesn't have a profile picture
if (empty($profile_picture)) {
    $profile_picture = 'images/user/default.png'; // Specify a default image path
}
?>

<div class="user-img c-pointer position-relative" data-toggle="dropdown">
    <span class="activity active"></span>
    <img src="<?php echo $profile_picture; ?>" height="40" width="40" alt="Patient Profile Picture">
</div>

                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="profile.php"><i class="icon-user"></i> <span>Profile</span></a>
                                        </li>
                                        
                                        <hr class="my-2">
                                        <li><a href="loguot.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>