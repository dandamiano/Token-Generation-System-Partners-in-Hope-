<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Adjust this path if necessary

// Handle updating status and date/time when rescheduled
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['appointment_id'], $_POST['status'], $_POST['new_date_time'])) {
        $appointmentId = $_POST['appointment_id'];
        $status = $_POST['status']; // This can be "rescheduled" or any other status
        $newDateTime = $_POST['new_date_time']; // New date and time for the appointment

        // Database connection
        $connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");
        
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // If the status is 'rescheduled', update both the status and appointment date/time
        if ($status === 'rescheduled') {
            // Query to update the appointment status and the new date/time
            $query = "UPDATE appointments SET status = ?, appointment_date = ? WHERE appointment_id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ssi', $status, $newDateTime, $appointmentId);
            $stmt->execute();

            // Get patient and doctor details to send emails
            $emailQuery = "SELECT p.email AS patient_email, d.email AS doctor_email, p.first_name AS patient_first_name, p.last_name AS patient_last_name, d.first_name AS doctor_first_name, d.last_name AS doctor_last_name FROM appointments a
                            JOIN patient p ON a.patient_id = p.patient_id
                            LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
                            WHERE a.appointment_id = ?";
            $emailStmt = $connection->prepare($emailQuery);
            $emailStmt->bind_param('i', $appointmentId);
            $emailStmt->execute();
            $result = $emailStmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $patientEmail = $row['patient_email'];
                $doctorEmail = $row['doctor_email'];
                $patientName = $row['patient_first_name'] . " " . $row['patient_last_name'];
                $doctorName = $row['doctor_first_name'] . " " . $row['doctor_last_name'];

                // Send email to the patient
                $mailToPatient = new PHPMailer(true);
                try {
                    // Server settings
                    $mailToPatient->isSMTP();
                    $mailToPatient->Host = 'smtp.gmail.com';  // SMTP server
                    $mailToPatient->SMTPAuth = true;
                    $mailToPatient->Username = 'kuseli13@gmail.com';  // Your email address
                    $mailToPatient->Password = 'gstq sluo jpsb lkrh';  // Your email password
                    $mailToPatient->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mailToPatient->Port = 587;

                    // Sender info
                    $mailToPatient->setFrom('kuseli13@gmail.com', 'Parteners In Hope');
                    
                    // Add patient email
                    $mailToPatient->addAddress($patientEmail);

                    // Email subject and body for the patient
                    $mailToPatient->isHTML(true);
                    $mailToPatient->Subject = 'Your Appointment has been Rescheduled';
                    $mailToPatient->Body = "Dear $patientName,<br><br>
                                            Your appointment has been rescheduled.<br><br>
                                            Appointment Details:<br>
                                            Patient Name: $patientName<br>
                                            Doctor Name: $doctorName<br>
                                            New Appointment Date and Time: $newDateTime<br><br>
                                            Please make sure to attend the appointment at the new date and time.<br><br>
                                            Best regards,<br>Parteners In Hope";

                    // Send the email to the patient
                    $mailToPatient->send();
                    echo 'Appointment successfully rescheduled and email sent to the patient.<br>';
                } catch (Exception $e) {
                    echo "Error sending email to patient: {$mailToPatient->ErrorInfo}<br>";
                }

                // Send email to the doctor
                $mailToDoctor = new PHPMailer(true);
                try {
                    // Server settings for doctor email
                    $mailToDoctor->isSMTP();
                    $mailToDoctor->Host = 'smtp.gmail.com';  // SMTP server
                    $mailToDoctor->SMTPAuth = true;
                    $mailToDoctor->Username = 'kuseli13@gmail.com';  // Your email address
                    $mailToDoctor->Password = 'gstq sluo jpsb lkrh';  // Your email password
                    $mailToDoctor->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mailToDoctor->Port = 587;

                    // Sender info
                    $mailToDoctor->setFrom('hip@gmail.com', 'Parteners In Hope');
                    
                    // Add doctor email
                    $mailToDoctor->addAddress($doctorEmail);

                    // Email subject and body for the doctor
                    $mailToDoctor->isHTML(true);
                    $mailToDoctor->Subject = 'Appointment Rescheduled Notification';
                    $mailToDoctor->Body = "Dear Dr. $doctorName,<br><br>
                                            The following appointment has been rescheduled:<br><br>
                                            Patient Name: $patientName<br>
                                            New Appointment Date and Time: $newDateTime<br><br>
                                            Please be sure to attend to the patient at the new time.<br><br>
                                            Best regards,<br>Parteners In Hope";

                    // Send the email to the doctor
                    $mailToDoctor->send();
                    echo 'Appointment successfully rescheduled and email sent to the doctor and patient.<br>';
                } catch (Exception $e) {
                    echo "Error sending email to doctor: {$mailToDoctor->ErrorInfo}<br>";
                }
            } else {
                echo 'Unable to fetch email details.';
            }
        } else {
            // For any other status update
            $query = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param('si', $status, $appointmentId);
            $stmt->execute();

            // Respond with a success message
            echo 'Appointment status updated to ' . $status;
        }

        // Close the database connection
        mysqli_close($connection);
    } else {
        echo 'Missing data.';
    }
}
?>
