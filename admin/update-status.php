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
$current_status_query = "SELECT status FROM appointments WHERE appointment_id = ?";
$current_status_stmt = mysqli_prepare($connection, $current_status_query);
mysqli_stmt_bind_param($current_status_stmt, 'i', $appointment_id);
mysqli_stmt_execute($current_status_stmt);
$current_status_result = mysqli_stmt_get_result($current_status_stmt);
$current_status = mysqli_fetch_assoc($current_status_result)['status'];


// Only proceed with updating the status if it's different from the current status
if ($current_status != $status) {
    // Get doctor and patient emails
    $query = "SELECT a.*, p.email AS patient_email, d.email AS doctor_email FROM appointments a
              JOIN patient p ON a.patient_id = p.patient_id
              JOIN doctor d ON a.doctor_id = d.doctor_id
              WHERE a.appointment_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $appointment_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $appointment = mysqli_fetch_assoc($result);

 
    // Update status
    $update_query = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
    $update_stmt = mysqli_prepare($connection, $update_query);
    mysqli_stmt_bind_param($update_stmt, 'si', $status, $appointment_id);

    if (mysqli_stmt_execute($update_stmt)) {
        // Send email notifications to doctor and patient
        try {
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'kuseli13@gmail.com'; // Your email
            $mail->Password   = 'gstq sluo jpsb lkrh'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Patient email
            $mail->setFrom('hope@gmail.com', 'Parteners In Hope');
            $mail->addAddress($appointment['patient_email'], 'Patient');
            $mail->Subject = 'Appointment Status Update';
            $mail->Body    = "Dear Patient,\n\nYour appointment status has been updated to: $status.\n\nThank you for using our service.";
            $mail->send();
            
            // Doctor email
            $mail->clearAddresses();  // Clear previous recipient
            $mail->addAddress($appointment['doctor_email'], 'Doctor');
            $mail->Subject = 'Appointment Status Update';
            $mail->Body    = "Dear Doctor,\n\nThe status of your appointment with patient ID $appointment_id has been updated to: $status.\n\nThank you.";
            $mail->send();

            echo "<script>alert('Status updated and emails sent successfully!');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Mailer Error: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('Error updating status: " . mysqli_error($connection) . "');</script>";
    }

    mysqli_stmt_close($update_stmt);
} else {
    echo "<script>alert('The status is already set to $status. No update necessary.');</script>";
}

mysqli_stmt_close($current_status_stmt);
mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
