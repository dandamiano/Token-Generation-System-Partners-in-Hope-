<?php
session_start();  // Ensure session is started

// Check if the patient is logged in
if (!isset($_SESSION['patient_id']) || empty($_SESSION['patient_id'])) {
    // Redirect to login page if patient_id is not set
    header("Location: login.php");
    exit();
}

// Database connection
try {
    $db = new PDO('mysql:host=localhost;dbname=parteners_in_hope;charset=utf8mb4', 'root', '');
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the appointment ID from the URL
if (isset($_GET['appointment_id'])) {
    $appointmentId = $_GET['appointment_id'];

    // Fetch the appointment details from the database
    $stmt = $db->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
    $stmt->execute([$appointmentId]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the appointment exists
    if ($appointment) {
        // Fetch the doctor's name (optional, if doctor info is stored in the database)
        $doctor_id = $appointment['doctor_id'];
        if ($doctor_id) {
            $doctorStmt = $db->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
            $doctorStmt->execute([$doctor_id]);
            $doctor = $doctorStmt->fetch(PDO::FETCH_ASSOC);
            $doctor_name = $doctor ? $doctor['first_name'] . ' ' . $doctor['last_name'] : 'Not Assigned';
        } else {
            $doctor_name = 'Not Assigned';
        }
    } else {
        // Redirect if appointment not found
        echo "<div class='alert alert-danger' role='alert'>
                <i class='fas fa-exclamation-circle'></i> Appointment not found.
              </div>";
        exit();
    }
} else {
    // Redirect if appointment ID is missing
    echo "<div class='alert alert-danger' role='alert'>
            <i class='fas fa-exclamation-circle'></i> No appointment ID provided.
          </div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />
  
    <title>Partners in Hope </title>
    <!-- Favicon icon -->
    <!-- Pignose Calender -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- FontAwesome Icons -->
 
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4"><i class="fas fa-check-circle text-success"></i> Your Appointment has been Scheduled</h2>
        
        <!-- Appointment Details Section -->
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <div class="card gradient-2 mb-4">
                    <div class="card-body">
                        <h3 class="card-title text-white">Appointment Details</h3>
                        <p><strong><i class="fas fa-id-badge"></i> Appointment ID:</strong> <?php echo $appointment['appointment_id']; ?></p>
                        <p><strong><i class="fas fa-calendar-alt"></i> Appointment Date:</strong> <?php echo $appointment['appointment_date']; ?></p>
                        <p><strong><i class="fas fa-clock"></i> Appointment Type:</strong> <?php echo $appointment['appointmentType']; ?></p>
                        <p><strong><i class="fas fa-stethoscope"></i> Reason:</strong> <?php echo $appointment['reason']; ?></p>
                        <p><strong><i class="fas fa-user-md"></i> Doctor:</strong> <?php echo $doctor_name; ?></p>
                    </div>
                </div>
            </div>

            <!-- Token Information Section -->
            <div class="col-lg-6 col-sm-12">
                <div class="card gradient-3 mb-4">
                    <div class="card-body">
                        <h3 class="card-title text-white">Your Token Number</h3>
                        <?php
                        // Fetch the token for this appointment
                        $tokenStmt = $db->prepare("SELECT * FROM tokens WHERE patient_id = ? AND status = 'waiting' ORDER BY assigned_at DESC LIMIT 1");
                        $tokenStmt->execute([$_SESSION['patient_id']]);
                        $token = $tokenStmt->fetch(PDO::FETCH_ASSOC);

                        if ($token) {
                            echo "<p><strong><i class='fas fa-ticket-alt'></i> Token Number:</strong> " . $token['token_number'] . "</p>";
                        } else {
                            echo "<div class='alert alert-warning' role='alert'>
                                    <i class='fas fa-exclamation-triangle'></i> No token generated yet.
                                  </div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="view-appointments.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap and FontAwesome JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
