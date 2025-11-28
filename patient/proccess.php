<?php
session_start();  // Start the session to access session variables

// Check if the patient is logged in (patient_id exists in session)
if (!isset($_SESSION['patient_id']) || empty($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

try {
    $db = new PDO('mysql:host=localhost;dbname=parteners_in_hope;charset=utf8mb4', 'root', '');
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];
    $appointmentType = $_POST['appointment_type'];
    $reason = $_POST['reason'];

    $appointmentDateTime = "$appointmentDate $appointmentTime";

    if (checkExistingAppointment($patient_id, $appointmentDateTime, $db)) {
        echo "<script>
                alert('You already have an appointment scheduled at this time. Please choose a different time.');
                window.location.href = 'schedule-appointment.php';
              </script>";
        exit();
    }

    // Set the appointment status as 'not scheduled' by default
    $stmt = $db->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, status, created_at, updated_at, reason, appointmentType) 
                          VALUES (?, ?, ?, 'not scheduled', NOW(), NOW(), ?, ?)");
    $stmt->execute([$patient_id, null, $appointmentDateTime, $reason, $appointmentType]);

    $appointmentId = $db->lastInsertId();

    // Insert into tokens table with appointment_id
    $tokenNumber = generateTokenNumber();
    $tokenStmt = $db->prepare("INSERT INTO tokens (appointment_id, patient_id, doctor_id, token_number, assigned_at, status) 
                               VALUES (?, ?, ?, ?, NOW(), 'waiting')");
    $tokenStmt->execute([$appointmentId, $patient_id, null, $tokenNumber]);

    $patient_email = getPatientEmail($patient_id, $db);
    $subject = "Appointment Confirmation";
    $body = "Dear Patient, \n\nYour appointment has been successfully scheduled. Here are the details:\nAppointment Date: $appointmentDate\nAppointment Time: $appointmentTime\nReason: $reason\n\nThank you.";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kuseli13@gmail.com';
        $mail->Password = 'gstq sluo jpsb lkrh';
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('pih@example.com', 'Partners in Hope');
        $mail->addAddress($patient_email);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Send system notification to the patient
    sendSystemNotification($patient_id, "Appointment Confirmation", "Your appointment is successfully booked for $appointmentDateTime.", $db);

    // Fetch the patient's name for the admin notification
    $patientName = getPatientName($patient_id, $db);

    // Send admin notification
    sendAdminNotification($db, $patientName, $tokenNumber, $reason);

    header("Location: appointment_confirmation.php?appointment_id=$appointmentId");
    exit();
}

function generateTokenNumber() {
    return rand(1000, 9999);
}

function checkExistingAppointment($patient_id, $appointmentDateTime, $db) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ? AND appointment_date = ?");
    $stmt->execute([$patient_id, $appointmentDateTime]);
    return $stmt->fetchColumn() > 0;
}

function getPatientEmail($patient_id, $db) {
    $stmt = $db->prepare("SELECT email FROM patient WHERE patient_id = ?");
    $stmt->execute([$patient_id]);
    return $stmt->fetchColumn();
}

function getPatientName($patient_id, $db) {
    $stmt = $db->prepare("SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM patient WHERE patient_id = ?");
    $stmt->execute([$patient_id]);
    return $stmt->fetchColumn();
}

function sendSystemNotification($patient_id, $title, $message, $db) {
    $stmt = $db->prepare("INSERT INTO notifications (patient_id, title, message, status, created_at) VALUES (?, ?, ?, 'unread', NOW())");
    $stmt->execute([$patient_id, $title, $message]);
}

function sendAdminNotification($db, $patientName, $tokenNumber, $reason) {
    // Admin ID assumed to be 1 for this example
    $admin_id = 1;
    $title = "New Appointment Scheduled";
    $message = "A new appointment has been booked.\nPatient: $patientName\nToken: $tokenNumber\nReason: $reason";
    
    $stmt = $db->prepare("INSERT INTO admin_notifications (admin_id, title, message, status, created_at) VALUES (?, ?, ?, 'unread', NOW())");
    $stmt->execute([$admin_id, $title, $message]);
}
?>
