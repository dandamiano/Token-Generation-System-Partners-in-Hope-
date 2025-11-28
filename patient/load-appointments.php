<?php
// Database connection
$connection = mysqli_connect("localhost", "root", "", "parteners_in_hope");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch appointments with patient and doctor information
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
        a.appointment_date, 
        a.status,
        a.doctor_id  -- doctor_id is included here
    FROM appointments a
    JOIN patient p ON a.patient_id = p.patient_id
    LEFT JOIN doctor d ON a.doctor_id = d.doctor_id;
";

$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $appointment_id = $row['appointment_id'];
        $patient_name = $row['patient_first_name'] . " " . $row['patient_last_name'];
        $doctor_name = $row['doctor_first_name'] . " " . $row['doctor_last_name'];
        $appointment_date = $row['appointment_date'];
        $status = $row['status'];
        $doctor_id = $row['doctor_id'];

        // Output the table row
        echo "<tr>
                <td>" . htmlspecialchars($appointment_id) . "</td>
                <td>" . htmlspecialchars($patient_name) . "</td>
                <td>" . htmlspecialchars($doctor_name) . "</td>
                <td>" . htmlspecialchars($appointment_date) . "</td>
                <td>
                    <select class='form-control' onchange='handleStatusChange($appointment_id, this.value)'>
                        <option value='scheduled' " . ($status == 'scheduled' ? 'selected' : '') . ">Scheduled</option>
                        <option value='rescheduled' " . ($status == 'rescheduled' ? 'selected' : '') . ">Reschedule</option>
                        <option value='completed' " . ($status == 'completed' ? 'selected' : '') . ">Completed</option>
                        <option value='cancelled' " . ($status == 'cancelled' ? 'selected' : '') . ">Cancelled</option>
                    </select>
                </td>
                <td>
                    <select class='form-control' onchange='assignDoctor($appointment_id, this.value)'>
                        <option value=''>Select Doctor</option>";

        // Fetch doctors from the database to populate the dropdown
        $doctor_query = "SELECT doctor_id, first_name, last_name FROM doctor";
        $doctor_result = mysqli_query($connection, $doctor_query);

        while ($doctor = mysqli_fetch_assoc($doctor_result)) {
            $doctor_option_id = $doctor['doctor_id'];
            $doctor_full_name = $doctor['first_name'] . " " . $doctor['last_name'];
            $selected = ($doctor_id == $doctor_option_id) ? 'selected' : '';  // Compare the current doctor
            echo "<option value='$doctor_option_id' $selected>$doctor_full_name</option>";
        }

        echo "</select>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No appointments found.</td></tr>";
}

mysqli_close($connection);
?>
