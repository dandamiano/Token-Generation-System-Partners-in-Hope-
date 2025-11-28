<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure to include the Composer autoloader

// Database connection
$connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$appointment_id = $_POST['appointment_id'];
$status = $_POST['status'];

// Get the current status of the appointment
$current_status_query = "SELECT status FROM appointments WHERE appointment_id = $appointment_id";
$current_status_result = mysqli_query($connection, $current_status_query);
$current_status = mysqli_fetch_assoc($current_status_result)['status'];

// Only proceed with updating the status if it's different from the current status
if ($current_status != $status) {
    // Get appointment details including doctor, patient, and receptionist emails
    $query = "SELECT a.*, p.first_name AS patient_first_name, p.last_name AS patient_last_name, 
              p.email AS patient_email, d.first_name AS doctor_first_name, d.last_name AS doctor_last_name, 
              d.email AS doctor_email, r.first_name AS receptionist_first_name, r.last_name AS receptionist_last_name, 
              r.email AS receptionist_email
              FROM appointments a
              JOIN patient p ON a.patient_id = p.patient_id
              JOIN doctor d ON a.doctor_id = d.doctor_id
              JOIN receptionist r ON r.receptionist_id = r.receptionist_id
              WHERE a.appointment_id = $appointment_id";
    $result = mysqli_query($connection, $query);
    $appointment = mysqli_fetch_assoc($result);

    // Update status in the appointments table
    $update_query = "UPDATE appointments SET status = '$status' WHERE appointment_id = $appointment_id";
    if (mysqli_query($connection, $update_query)) {
        // Update status in the tokens table to "Complete" only if the appointment status is "Complete"
        if ($status == 'Complete') {
            $update_tokens_query = "UPDATE tokens SET status = 'Complete' WHERE appointment_id = $appointment_id";
            if (!mysqli_query($connection, $update_tokens_query)) {
                echo "<script>alert('Error updating tokens table: " . mysqli_error($connection) . "');</script>";
            }
        }

        // Send email notifications to doctor, patient, and receptionist
        try {
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'kuseli13@gmail.com'; // Your email
            $mail->Password   = 'gstq sluo jpsb lkrh'; // Replace with environment variable or config file
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Patient email
            $mail->setFrom('kuseli13@gmail.com', 'Parteners In Hope');
            $mail->addAddress($appointment['patient_email'], 'Patient');
            $mail->Subject = 'Appointment Status Update';
            $mail->Body    = "Dear {$appointment['patient_first_name']} {$appointment['patient_last_name']},\n\nYour appointment with Dr. {$appointment['doctor_first_name']} {$appointment['doctor_last_name']} on {$appointment['appointment_date']} has been updated to: $status.\n\nThank you for using our service.";
            $mail->send();
            
            // Doctor email
            $mail->clearAddresses();  // Clear previous recipient
            $mail->addAddress($appointment['doctor_email'], 'Doctor');
            $mail->Subject = 'Appointment Status Update';
            $mail->Body    = "Dear Dr. {$appointment['doctor_first_name']} {$appointment['doctor_last_name']},\n\nThe status of your appointment with {$appointment['patient_first_name']} {$appointment['patient_last_name']} scheduled for {$appointment['appointment_date']} has been updated to: $status.\n\nThank you.";
            $mail->send();

            // Receptionist email
            $mail->clearAddresses();  // Clear previous recipient
            $mail->addAddress($appointment['receptionist_email'], 'Receptionist');
            $mail->Subject = 'Appointment Status Update';
            $mail->Body    = "Dear {$appointment['receptionist_first_name']} {$appointment['receptionist_last_name']},\n\nThe status of the appointment with patient {$appointment['patient_first_name']} {$appointment['patient_last_name']} scheduled for {$appointment['appointment_date']} has been updated to: $status.\n\nThank you.";
            $mail->send();

            echo "<script>alert('Status updated and emails sent successfully!');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Mailer Error: {$mail->ErrorInfo}');</script>";
        }

        // Insert notifications into admin_notifications table with full appointment details
        // Notification for Admin
        $admin_message = "The appointment with {$appointment['patient_first_name']} {$appointment['patient_last_name']} scheduled for {$appointment['appointment_date']} has been updated to: $status.";

        // Insert notification with 'unread' status
        $admin_notification_query = "INSERT INTO admin_notifications (admin_id, title, message, status, created_at) 
                                     VALUES (1, 'Appointment Status Update', '$admin_message', 'unread', NOW())";  // Set 'unread' status directly

        if (!mysqli_query($connection, $admin_notification_query)) {
            echo "<script>alert('Error inserting admin notification: " . mysqli_error($connection) . "');</script>";
        }

        echo "<script>alert('Status updated and notifications sent!');</script>";
    } else {
        echo "<script>alert('Error updating status: " . mysqli_error($connection) . "');</script>";
    }
} else {
    echo "<script>alert('The status is already set to $status. No update necessary.');</script>";
}

mysqli_close($connection);
?>
