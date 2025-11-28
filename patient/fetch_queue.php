<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parteners_in_hope";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: index.php");
    exit();
}

// Get the patient_id from the session
$patient_id = $_SESSION['patient_id'];

// Fetch the patient's token and their associated appointment status
$query_token = "
    SELECT t.token_number, t.status AS token_status, a.status AS appointment_status 
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    WHERE t.patient_id = '$patient_id' AND a.status = 'scheduled'
";
$result_token = mysqli_query($conn, $query_token);

if (!$result_token || mysqli_num_rows($result_token) == 0) {
    echo "No active token found for your account, or your appointment is not scheduled.";
    exit();
}

$patient_token = mysqli_fetch_assoc($result_token)['token_number'];

// Fetch all tokens associated with scheduled appointments, excluding completed ones, ordered by token number
$query_queue = "
    SELECT t.token_number, t.status AS token_status, a.status AS appointment_status, t.patient_id
    FROM tokens t
    JOIN appointments a ON t.appointment_id = a.appointment_id
    WHERE a.status = 'scheduled' AND t.status != 'completed'   -- Exclude completed tokens
    ORDER BY t.token_number ASC
";
$result_queue = mysqli_query($conn, $query_queue);

// Determine the position in line
$position = 1;
$found = false;
while ($row = mysqli_fetch_assoc($result_queue)) {
    if ($row['token_number'] == $patient_token) {
        $found = true;
        break;
    }
    $position++;
}

if (!$found) {
    echo "Your position in the queue could not be determined or you have no scheduled appointment.";
    exit();
}

// Reset the pointer to the beginning of the result set for displaying the queue
mysqli_data_seek($result_queue, 0);

// Start building the HTML for the queue
$html_output = '
    <h4 class="card-title">Queue Position</h4>
    <p>Your token number: <strong>' . htmlspecialchars($patient_token) . '</strong></p>
    <p>Your current position in the queue: <strong>' . htmlspecialchars($position) . '</strong></p>

    <h5>Current Queue</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Position</th>
                <th>Token Number</th>
                <th>Token Status</th>
                <th>Appointment Status</th>
            </tr>
        </thead>
        <tbody>';

$queue_position = 1;
while ($row = mysqli_fetch_assoc($result_queue)) {
    $row_class = ($row['token_number'] == $patient_token) ? 'bg-info text-white' : ''; // Highlight the current patient's row
    $html_output .= '
        <tr class="' . $row_class . '">
            <td>' . $queue_position++ . '</td>
            <td>' . htmlspecialchars($row['token_number']) . '</td>
            <td>' . ucfirst(htmlspecialchars($row['token_status'])) . '</td>
            <td>' . ucfirst(htmlspecialchars($row['appointment_status'])) . '</td>
        </tr>';
}

$html_output .= '
        </tbody>
    </table>

    <p>Thank you for your patience. Please wait until your turn.</p>
';

// Close the database connection
mysqli_close($conn);

// Output the HTML content
echo $html_output;
?>
