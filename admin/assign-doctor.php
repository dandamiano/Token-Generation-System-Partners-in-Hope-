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
$doctor_id = $_POST['doctor_id'];

// Assign doctor
$query = "UPDATE appointments SET doctor_id = ? WHERE appointment_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'ii', $doctor_id, $appointment_id);

if (mysqli_stmt_execute($stmt)) {
    echo "Doctor assigned successfully!<br>";

    // Fetch detailed patient and doctor information (including appointment details)
    $query = "SELECT a.*, p.first_name AS patient_first_name, p.last_name AS patient_last_name, p.email AS patient_email, p.contact_number AS patient_phone,
              d.first_name AS doctor_first_name, d.last_name AS doctor_last_name, d.email AS doctor_email, d.specialization AS doctor_specialization,
              a.appointment_date, a.appointment_date, a.reason
              FROM appointments a
              JOIN patient p ON a.patient_id = p.patient_id
              JOIN doctor d ON a.doctor_id = d.doctor_id
              WHERE a.appointment_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $appointment_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $appointment = mysqli_fetch_assoc($result);

    // Send email notifications to patient and doctor
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
        $mail->Subject = 'Doctor Assigned to Your Appointment';
        $mail->Body    = "Dear " . $appointment['patient_first_name'] . " " . $appointment['patient_last_name'] . ",\n\nA doctor has been assigned to your appointment. The details of your appointment are as follows:\n\n
                          Appointment ID: $appointment_id\n
                          Appointment Date: " . $appointment['appointment_date'] . "\n
                          Appointment date: " . $appointment['appointment_date'] . "\n
                          Reason: " . $appointment['reason'] . "\n
                          Doctor: Dr. " . $appointment['doctor_first_name'] . " " . $appointment['doctor_last_name'] . "\n
                          Doctor Specialization: " . $appointment['doctor_specialization'] . "\n
                          Thank you for using our service.\n\n
                          If you need to reschedule or have any questions, please contact us.";
        $mail->send();

        // Doctor email
        $mail->clearAddresses();  // Clear previous recipient
        $mail->addAddress($appointment['doctor_email'], 'Doctor');
        $mail->Subject = 'New Appointment Assigned to You';
        $mail->Body    = "Dear Dr. " . $appointment['doctor_first_name'] . " " . $appointment['doctor_last_name'] . ",\n\nYou have been assigned to the following appointment:\n\n
                          Appointment ID: $appointment_id\n
                          Appointment Date: " . $appointment['appointment_date'] . "\n
                          Appointment date: " . $appointment['appointment_date'] . "\n
                          Reason: " . $appointment['reason'] . "\n
                          Patient: " . $appointment['patient_first_name'] . " " . $appointment['patient_last_name'] . "\n
                          Patient Phone: " . $appointment['patient_phone'] . "\n\n
                          Please review the details and prepare for the appointment.\n
                          Thank you for your service to the patients.";
        $mail->send();

        echo "<script>alert('Doctor assigned and emails sent successfully!');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Mailer Error: {$mail->ErrorInfo}');</script>";
    }
} else {
    echo "Error assigning doctor: " . mysqli_error($connection) . "<br>";
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
