<?php
// Include database connection
include_once 'db_connection.php';

// Define the CSV filename
$filename = "system_report_" . date('Y-m-d') . ".csv";

// Set headers to force download the file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open PHP output stream as a file
$output = fopen('php://output', 'w');

// Column headings for the CSV
fputcsv($output, array('Report Metric', 'Value'));

// Query data (use the same queries as before)
$metrics = [
    ['Total Appointments', "SELECT COUNT(*) AS total_appointments FROM appointments"],
    ['Completed Appointments', "SELECT COUNT(*) AS completed_appointments FROM appointments WHERE status = 'completed'"],
    ['Average Waiting Time (minutes)', "SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, appointment_date)) AS average_waiting_time FROM appointments"],
    ['Total Patients', "SELECT COUNT(*) AS total_patients FROM patient"],
    ['Total Doctors', "SELECT COUNT(*) AS total_doctors FROM doctor"],
    ['Total Receptionists', "SELECT COUNT(*) AS total_receptionists FROM receptionist"],
    ['Patients Treated This Month', "SELECT COUNT(*) AS patients_treated FROM appointments WHERE status = 'completed' AND MONTH(appointment_date) = MONTH(CURDATE()) AND YEAR(appointment_date) = YEAR(CURDATE())"],
    ['Patients Treated This Week', "SELECT COUNT(*) AS patients_treated_week FROM appointments WHERE status = 'completed' AND WEEK(appointment_date, 1) = WEEK(CURDATE(), 1) AND YEAR(appointment_date) = YEAR(CURDATE())"],
    ['Tokens Generated This Week', "SELECT COUNT(*) AS tokens_this_week FROM tokens WHERE WEEK(assigned_at, 1) = WEEK(CURDATE(), 1) AND YEAR(assigned_at) = YEAR(CURDATE())"],
    ['Tokens Generated This Month', "SELECT COUNT(*) AS tokens_this_month FROM tokens WHERE MONTH(assigned_at) = MONTH(CURDATE()) AND YEAR(assigned_at) = YEAR(CURDATE())"],
    ['Tokens Generated This Year', "SELECT COUNT(*) AS tokens_this_year FROM tokens WHERE YEAR(assigned_at) = YEAR(CURDATE())"]
];

// Fetch each metric and write to CSV
foreach ($metrics as $metric) {
    $query = $metric[1];
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    $value = $data[key($data)]; // Get the value from the result

    // Write each row to CSV
    fputcsv($output, array($metric[0], $value));
}

// Close the output stream
fclose($output);

// Close database connection
mysqli_close($conn);
?>
