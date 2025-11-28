<div class="header bg-primary">    
            <div class="header-content bg-primary clearfix">
                
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
               
                <div class="header-right">
                <?php
// Query to get the unread notifications count for the admin
$query_unread_count = "SELECT COUNT(*) as unread_count FROM doctor_notifications WHERE status ='unread' AND doctor_id = '$doctor_id'";
$result_unread_count = mysqli_query($conn, $query_unread_count);
$row_unread_count = mysqli_fetch_assoc($result_unread_count);
$unread_count = $row_unread_count['unread_count'];

// Query to get the recent unread notifications for the dropdown
$query_unread_notifications = "SELECT * FROM doctor_notifications WHERE status = 'unread' AND doctor_id = '$doctor_id' LIMIT 5";
$result_unread_notifications = mysqli_query($conn, $query_unread_notifications);
?>

<!-- Notification Icon with Badge -->
<li class="icons dropdown">
    <a href="javascript:void(0)" data-toggle="dropdown">
        <i class="mdi mdi-bell-outline"></i>
        <span class="badge badge-pill gradient-2"><?php echo $unread_count; ?></span>
    </a>
    <div class="drop-down animated fadeIn dropdown-menu dropdown-notfication">
        <div class="dropdown-content-heading d-flex justify-content-between">
            <span class=""><?php echo $unread_count; ?> New Notifications</span>
            <a href="javascript:void()" class="d-inline-block">
                <span class="badge badge-pill gradient-2"><?php echo $unread_count; ?></span>
            </a>
        </div>
        <div class="dropdown-content-body">
            <ul>
                <?php
                // Check if there are unread notifications
                if (mysqli_num_rows($result_unread_notifications) > 0) {
                    while ($notification = mysqli_fetch_assoc($result_unread_notifications)) {
                        echo "<li>
                                <a href='javascript:void()'>
                                    <span class='mr-3 avatar-icon bg-success-lighten-2'><i class='icon-bell'></i></span>
                                    <div class='notification-content'>
                                        <h6 class='notification-heading'>" . htmlspecialchars($notification['title']) . "</h6>
                                        <span class='notification-text'>" . htmlspecialchars($notification['message']) . "</span>
                                    </div>
                                </a>
                            </li>";
                    }
                } else {
                    echo "<li><a href='javascript:void()'><span class='notification-text'>No new notifications</span></a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</li>

                      
                        
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                        <i class="fa fa-user text-white" style="font-size:2.3em"></i>
                            </div>
                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        
                                        <hr class="my-2">
                                        <li><a href="logout.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>