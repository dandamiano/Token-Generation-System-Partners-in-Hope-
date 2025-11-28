<?php
// Start the session
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    // Redirect to login page if the doctor is not logged in
    header("Location: login.php");
    exit;
}

// Continue with the rest of the code
$doctor_id = $_SESSION['doctor_id'];  // Now $_SESSION['doctor_id'] should be available
?><?php
// Database connection
$connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Assuming doctor ID is stored in the session (e.g., $_SESSION['doctor_id'])
$doctor_id = $_SESSION['doctor_id'];  // Ensure the session contains the doctor's ID

// Query to fetch appointments for the logged-in doctor with patient, doctor, and token information
$query = "
    SELECT 
        a.appointment_id, 
        p.first_name AS patient_first_name, 
        p.last_name AS patient_last_name,
        p.email AS patient_email, 
        p.contact_number AS patient_contact, 
        p.date_of_birth AS patient_dob,
        d.first_name AS doctor_first_name, 
        d.last_name AS doctor_last_name, 
        d.specialization AS doctor_specialization,
        a.appointment_date, a.reason, a.appointmentType,
        a.status,
        a.doctor_id, 
        t.token_number, 
        t.status AS token_status  -- Fetch token status from the tokens table
    FROM appointments a
    JOIN patient p ON a.patient_id = p.patient_id
    LEFT JOIN doctor d ON a.doctor_id = d.doctor_id
    LEFT JOIN tokens t ON a.appointment_id = t.appointment_id
    WHERE a.doctor_id = '$doctor_id';  -- Filter appointments for the logged-in doctor
";

$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reason = $row['reason'];   
        $appointmentType = $row['appointmentType'];
        $appointment_id = $row['appointment_id'];
        $token_number = $row['token_number'];  // Fetch token number from the result
        $token_status = $row['token_status']; // Fetch token status
        $patient_name = $row['patient_first_name'] . " " . $row['patient_last_name'];
        $doctor_name = $row['doctor_first_name'] . " " . $row['doctor_last_name'];
        $appointment_date = $row['appointment_date'];
        $status = $row['status'];

        // Output the table row
        echo "<tr>
            <td>" . htmlspecialchars($token_number) . "</td>  <!-- Token number displayed here -->
            <td>" . htmlspecialchars($patient_name) . "</td>
            <td>" . htmlspecialchars($appointment_date) . "</td>
            <td>" . htmlspecialchars($reason) . "</td>
            <td>" . htmlspecialchars($appointmentType) . "</td>
            <td>
                <select class='form-control' onchange='handleStatusChange($appointment_id, this.value)'>
                    <option value='scheduled' " . ($status == 'scheduled' ? 'selected' : '') . ">Scheduled</option>
                    <option value='rescheduled' " . ($status == 'rescheduled' ? 'selected' : '') . ">Rescheduled</option>
                    <option value='completed' " . ($status == 'completed' ? 'selected' : '') . ">Completed</option>
                </select>
            </td>
            <td>
                <select class='form-control' onchange='updateTokenStatus($appointment_id, this.value)'>  <!-- Token status dropdown -->
                    <option value='waiting' " . ($token_status == 'waiting' ? 'selected' : '') . ">Wait</option>
                    <option value='called' " . ($token_status == 'called' ? 'selected' : '') . ">Call</option>
                    <option value='serving' " . ($token_status == 'serving' ? 'selected' : '') . ">Serve</option>
                    <option value='completed' " . ($token_status == 'completed' ? 'selected' : '') . ">Done</option>
                </select>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No appointments found.</td></tr>";
}

mysqli_close($connection);
?>
