<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('includes/dbconnection.php');

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$userid = $_SESSION['userid'];

// Verify database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Force refresh the appointment status from database before displaying
// This ensures we have the most current status
$refresh_status = mysqli_query($con, "UPDATE tblappointment SET Status = Status WHERE UserID = '$userid'");

// Fetch User Details and Cancellation Count (modified to only count cancellations within the last week)
$current_date = date('Y-m-d H:i:s');
$one_week_ago = date('Y-m-d H:i:s', strtotime('-1 week'));

$user_query = mysqli_query($con, "SELECT u.*, COALESCE(c.cancellation_count, 0) as cancellations,
                                  COALESCE(c.oldest_cancellation, NULL) as oldest_cancellation  
                                  FROM tblusers u 
                                  LEFT JOIN 
                                  (SELECT UserID, 
                                         COUNT(*) as cancellation_count,
                                         MIN(CancellationDate) as oldest_cancellation
                                   FROM tblcancellations 
                                   WHERE UserID = '$userid' AND CancellationDate > '$one_week_ago') c 
                                  ON u.ID = c.UserID 
                                  WHERE u.ID = '$userid'");

if ($user_query) {
    $user_data = mysqli_fetch_assoc($user_query);
    $cancellation_count = $user_data['cancellations'];
    $oldest_cancellation = $user_data['oldest_cancellation'];
    
    // Calculate days until reset if there are cancellations
    $days_until_reset = 0;
    $reset_date = '';
    if ($oldest_cancellation) {
        $reset_date = date('Y-m-d', strtotime($oldest_cancellation . ' +1 week'));
        $days_until_reset = ceil((strtotime($reset_date) - time()) / (60 * 60 * 24));
        if ($days_until_reset < 0) $days_until_reset = 0;
    }
} else {
    die("Error fetching user data: " . mysqli_error($con));
}

// Check if user has reached cancellation limit
$cancellation_limit_reached = ($cancellation_count >= 5);

// Clean up old cancellations (older than one week)
$cleanup_old_cancellations = mysqli_query($con, "DELETE FROM tblcancellations 
                                                WHERE UserID = '$userid' 
                                                AND CancellationDate < '$one_week_ago'");

function updateData($con, $table, $data, $where) {
    $updates = [];
    foreach ($data as $key => $value) {
        $safe_value = isset($value) ? mysqli_real_escape_string($con, $value) : '';
        $updates[] = "$key = '$safe_value'";
    }
    $updates_str = implode(", ", $updates);
    $sql = "UPDATE $table SET $updates_str WHERE $where";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Update failed: " . mysqli_error($con));
    }

    return $result;
}

// Function to cancel appointments that have passed
function cancelPassedAppointments($con, $userid) {
    $currentDate = date('Y-m-d');
    $sql = "SELECT * FROM tblappointment WHERE UserID = '$userid' AND AptDate < '$currentDate'";

    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointmentId = $row['ID'];
            $deleteSql = "DELETE FROM tblappointment WHERE ID = '$appointmentId' AND UserID = '$userid'";
            if (mysqli_query($con, $deleteSql)) {
                echo "<div class='success'>Appointment ID $appointmentId automatically cancelled because it has passed.</div>";
            } else {
                echo "<div class='error'>Failed to cancel appointment ID $appointmentId. Error: " . mysqli_error($con) . "</div>";
            }
        }
    }
}

// Call the function to cancel passed appointments
cancelPassedAppointments($con, $userid);

// Handle Profile Update
$message = ''; // Initialize message

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $data = [
        "UserName" => $_POST['name'],
        "Email" => $_POST['email']
    ];
    $where = "ID = '$userid'";
    $update_success = updateData($con, "tblusers", $data, $where);
    if ($update_success) {
        $user_data = array_merge($user_data, $data);
        $message = '<div class="success">Profile updated successfully.</div>';
    } else {
        $message = '<div class="error">Failed to update profile.</div>';
    }
}

if (isset($_POST['update_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $appointment_date = $_POST['adate'];
    $appointment_time = $_POST['atime'];

    // First check if the appointment is accepted or rejected
    $status_check = mysqli_query($con, "SELECT Status FROM tblappointment WHERE ID = '$appointment_id' AND UserID = '$userid'");
    if ($status_check && mysqli_num_rows($status_check) > 0) {
        $status_data = mysqli_fetch_assoc($status_check);
        if ($status_data['Status'] == 'Accepted' || $status_data['Status'] == 'Rejected') {
            $message = '<div class="error">Cannot update appointment that has been ' . $status_data['Status'] . '.</div>';
        } else {
            // Get current date and time
            $current_date = date('Y-m-d');
            $current_hour = date('H'); // Current hour in 24-hour format

            // Block past dates
            if ($appointment_date < $current_date) {
                $message = '<div class="error">Cannot update appointment to a past date.</div>';
            } else {
                // Convert appointment time to 24-hour format for comparison
                $appointment_hour = date('H', strtotime($appointment_time));

                // Allow only updates between 9 AM - 8 PM
                if ($appointment_hour < 9 || $appointment_hour >= 20) {
                    $message = '<div class="error">Appointments can only be scheduled between 9 AM and 8 PM.</div>';
                } else {
                    // Check if the selected time is already booked
                    $check_query = mysqli_query($con, "SELECT * FROM tblappointment WHERE AptDate = '$appointment_date' AND AptTime = '$appointment_time' AND ID != '$appointment_id'");
                    
                    if (mysqli_num_rows($check_query) > 0) {
                        $message = '<div class="error">The selected time is already booked. Please choose a different time.</div>';
                    } else {
                        // Proceed with updating the appointment
                        $data = [
                            "Name" => $_POST['name'],
                            "Services" => $_POST['services'],
                            "Stylist" => $_POST['stylist'],
                            "AptDate" => $appointment_date,
                            "AptTime" => $appointment_time
                        ];
                        $where = "ID = '$appointment_id' AND UserID = '$userid'";
                        if (updateData($con, "tblappointment", $data, $where)) {
                            $message = '<div class="success">Appointment updated successfully.</div>';
                        } else {
                            $message = '<div class="error">Failed to update appointment.</div>';
                        }
                    }
                }
            }
        }
    } else {
        $message = '<div class="error">Appointment not found or access denied.</div>';
    }
}

// Handle Appointment Cancellation
if (isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    
    // First check if the appointment is accepted or rejected
    $status_check = mysqli_query($con, "SELECT Status FROM tblappointment WHERE ID = '$appointment_id' AND UserID = '$userid'");
    if ($status_check && mysqli_num_rows($status_check) > 0) {
        $status_data = mysqli_fetch_assoc($status_check);
        if ($status_data['Status'] == 'Accepted' || $status_data['Status'] == 'Rejected') {
            $message = '<div class="error">Cannot cancel appointment that has been ' . $status_data['Status'] . '.</div>';
        } else {
            if (mysqli_query($con, "DELETE FROM tblappointment WHERE ID = '$appointment_id' AND UserID = '$userid'")) {
                // Log the cancellation in the tblcancellations table
                $current_date = date('Y-m-d H:i:s');
                $log_cancellation = mysqli_query($con, "INSERT INTO tblcancellations (UserID, AppointmentID, CancellationDate) 
                                                   VALUES ('$userid', '$appointment_id', '$current_date')");
                
                if ($log_cancellation) {
                    // Re-fetch cancellation count to ensure it's up-to-date
                    $count_query = mysqli_query($con, "SELECT COUNT(*) as count FROM tblcancellations 
                                                  WHERE UserID = '$userid' AND CancellationDate > '$one_week_ago'");
                    $count_data = mysqli_fetch_assoc($count_query);
                    $cancellation_count = $count_data['count'];
                    
                    $message = '<div class="success">Appointment cancelled successfully.</div>';
                    
                    // Check if this cancellation reaches the limit
                    if ($cancellation_count >= 5) {
                        $message .= '<div class="warning">You have reached the maximum number of cancellations (5). You cannot book new appointments until your cancellation count is reset in ' . $days_until_reset . ' days.</div>';
                        $cancellation_limit_reached = true;
                    }
                } else {
                    $message = '<div class="error">Appointment cancelled but failed to log cancellation.</div>';
                }
            } else {
                $message = '<div class="error">Failed to cancel appointment.</div>';
            }
        }
    } else {
        $message = '<div class="error">Appointment not found or access denied.</div>';
    }
}

// FIX: Re-fetching all appointment data after any changes
$currentDate = date('Y-m-d');

// FIX: Get pending appointments (future appointments that aren't Accepted or Rejected)
$pending_appointment_query = mysqli_query($con, "SELECT * FROM tblappointment 
                                        WHERE UserID = '$userid' 
                                        AND AptDate >= '$currentDate' 
                                        AND (Status IS NULL OR Status = '' OR Status = 'Pending')");

// Check for SQL error
if (!$pending_appointment_query) {
    die("Error in pending appointment query: " . mysqli_error($con));
}

$pending_appointments = mysqli_fetch_all($pending_appointment_query, MYSQLI_ASSOC);

// FIX: Get processed appointments (Accepted or Rejected status only)
$processed_appointment_query = mysqli_query($con, "SELECT * FROM tblappointment 
                                                WHERE UserID = '$userid' 
                                                AND AptDate >= '$currentDate' 
                                                AND (Status = 'Accepted' OR Status = 'Rejected')");

if (!$processed_appointment_query) {
    die("Error in processed appointment query: " . mysqli_error($con));
}

$processed_appointments = mysqli_fetch_all($processed_appointment_query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    body {
        font-family: 'Work Sans', sans-serif;
        background-color: rgb(139, 44, 88);
        color: #333;
        margin: 0;
        padding: 0;
    }
    /* Update Button */
    .btn-update {
        background-color: #90EE90; /* Light Green */
        color: black;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }
    .btn-update:hover {
        background-color: #008000; /* Green */
        color: white;
    }

    /* Cancel Button */
    .btn-cancel {
        background-color: #FF7F7F; /* Light Red */
        color: black;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .btn-cancel:hover {
        background-color: #FF0000; /* Red */
        color: white;
    }
    .success {
        background-color: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    
    .warning {
        background-color: #fff3cd;
        color: #856404;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    
    .cancellation-count {
        background-color: #e2e3e5;
        color: #383d41;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .cancellation-count.limit-reached {
        background-color: #f8d7da;
        color: #721c24;
        font-weight: bold;
    }
    
    h2, p {
        color: white;
    }
    p {
        color: black;
    }
    
    .reset-countdown {
        background-color: #cce5ff;
        color: #004085;
        padding: 8px;
        border-radius: 5px;
        margin-top: 10px;
        font-size: 14px;
    }

    .readonly-appointment {
        background-color: #f8f9fa;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        border-left: 5px solid;
    }

    .readonly-appointment.accepted {
        border-left-color: #28a745;
    }

    .readonly-appointment.rejected {
        border-left-color: #dc3545;
    }

    .appointment-status {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 3px;
        display: inline-block;
        margin-bottom: 10px;
    }

    .status-accepted {
        background-color: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .appointment-tabs {
        margin-bottom: 20px;
    }

    .appointment-tab {
        display: inline-block;
        padding: 10px 15px;
        background-color: #e9ecef;
        cursor: pointer;
        border-radius: 5px 5px 0 0;
        margin-right: 5px;
    }

    .appointment-tab.active {
        background-color: #fff;
        font-weight: bold;
    }

    .appointment-content {
        display: none;
    }

    .appointment-content.active {
        display: block;
    }
</style>
<body>
    <?php include_once('includes/header.php'); ?>

    <section class="ftco-section profile-container">
        <div class="container">
            <section class="ftco-section profile-container">
                <div class="container">
                    <?php if (!empty($message)) { echo $message; } ?>

                    <div class="row">
                        <div class="col-md-6">
                            <h2>Your Profile</h2>
                            <form method="post">
                                <input type="text" class="form-control" name="name" value="<?php echo $user_data['UserName']; ?>" required>
                                <input type="email" class="form-control" name="email" value="<?php echo $user_data['Email']; ?>" required>
                                <button type="submit" name="update_profile" class="btn-update">Update Profile</button>
                            </form>
                            
                            <!-- Display cancellation count --><br>
                            <div class="cancellation-count <?php echo $cancellation_limit_reached ? 'limit-reached' : ''; ?>">
                                <h4>Appointment Cancellations: <?php echo $cancellation_count; ?>/5</h4>
                                <?php if ($cancellation_limit_reached): ?>
                                    <p>You have reached the maximum number of cancellations.</p>
                                    <?php if ($days_until_reset > 0): ?>
                                        <div class="reset-countdown">
                                            <i class="fas fa-clock"></i> Your cancellation count will automatically reset in 
                                            <strong><?php echo $days_until_reset; ?> day<?php echo $days_until_reset != 1 ? 's' : ''; ?></strong> 
                                            (on <?php echo date('F j, Y', strtotime($reset_date)); ?>).
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p>You have <?php echo 5 - $cancellation_count; ?> cancellations remaining before booking restrictions apply.</p>
                                    <?php if ($cancellation_count > 0 && $days_until_reset > 0): ?>
                                        <div class="reset-countdown">
                                            <i class="fas fa-clock"></i> Your oldest cancellation will expire in 
                                            <strong><?php echo $days_until_reset; ?> day<?php echo $days_until_reset != 1 ? 's' : ''; ?></strong> 
                                            (on <?php echo date('F j, Y', strtotime($reset_date)); ?>).
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h2>Your Appointments</h2>

                            <?php if ($cancellation_limit_reached): ?>
                                <div class="error">
                                    <strong>Booking Restricted</strong>
                                    <p>You have reached the maximum limit of 5 cancellations within a week. New bookings are temporarily restricted.</p>
                                    <?php if ($days_until_reset > 0): ?>
                                        <p>Your account will be automatically unrestricted on <?php echo date('F j, Y', strtotime($reset_date)); ?>.</p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Appointment tabs -->
                            <div class="appointment-tabs">
                                <div class="appointment-tab active" data-tab="pending">Pending Appointments</div>
                                <!--<div class="appointment-tab" data-tab="processed">Processed Appointments</div>-->
                            </div>

                            <!-- Pending Appointments Section -->
                            <div id="pending-appointments" class="appointment-content active">
                                <?php if (!empty($pending_appointments)): ?>
                                    <select id="appointmentSelector" class="form-control">
                                        <option value="">Select an Appointment</option>
                                        <?php foreach ($pending_appointments as $index => $appointment): ?>
                                            <option value="appointment_<?php echo $index; ?>">
                                                <?php echo "Service: " . $appointment['Services'] . " | Date: " . $appointment['AptDate']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php foreach ($pending_appointments as $index => $appointment): ?>
                                        <div class="appointment-details" id="appointment_<?php echo $index; ?>" style="display: none;">
                                            <form method="post">
                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['ID']; ?>">
                                                <input type="text" class="form-control" name="name" value="<?php echo $appointment['Name']; ?>" required>

                                                <select name="services" class="form-control" required>
                                                    <option value="">Select Services</option>
                                                    <?php
                                                    $query = mysqli_query($con, "SELECT * FROM tblservices");
                                                    while ($row = mysqli_fetch_array($query)) { ?>
                                                        <option value="<?php echo $row['ServiceName']; ?>" <?php if ($row['ServiceName'] == $appointment['Services']) echo 'selected'; ?>><?php echo $row['ServiceName']; ?></option>
                                                    <?php } ?>
                                                </select>

                                                <select name="stylist" class="form-control" required>
                                                    <option value="">Select Stylist</option>
                                                    <?php
                                                    $stylist_query = mysqli_query($con, "SELECT * FROM tblstylist");
                                                    while ($stylist_row = mysqli_fetch_array($stylist_query)) { ?>
                                                        <option value="<?php echo $stylist_row['StylistName']; ?>" <?php if ($stylist_row['StylistName'] == $appointment['Stylist']) echo 'selected'; ?>><?php echo $stylist_row['StylistName']; ?></option>
                                                    <?php } ?>
                                                </select>

                                                <input type="date" id="adate" class="form-control" name="adate" value="<?php echo $appointment['AptDate']; ?>" required>
                                                <input type="time" class="form-control" name="atime" value="<?php echo $appointment['AptTime']; ?>" required>

                                                <button type="submit" name="update_appointment" class="btn-update">Update</button>
                                                <button type="submit" name="cancel_appointment" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel?')">Cancel Appointment</button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No pending appointments found.</p>
                                <?php endif; ?>
                            </div>

                            <!-- Processed Appointments Section -->
                            <div id="processed-appointments" class="appointment-content">
                                <?php if (!empty($processed_appointments)): ?>
                                    <?php foreach ($processed_appointments as $ro_appointment): ?>
                                        <div class="readonly-appointment <?php echo strtolower($ro_appointment['Status']); ?>">
                                            <div class="appointment-status status-<?php echo strtolower($ro_appointment['Status']); ?>">
                                                <?php echo $ro_appointment['Status']; ?>
                                            </div>
                                            <p><strong>Name:</strong> <?php echo $ro_appointment['Name']; ?></p>
                                            <p><strong>Service:</strong> <?php echo $ro_appointment['Services']; ?></p>
                                            <p><strong>Stylist:</strong> <?php echo $ro_appointment['Stylist']; ?></p>
                                            <p><strong>Date:</strong> <?php echo $ro_appointment['AptDate']; ?></p>
                                            <p><strong>Time:</strong> <?php echo $ro_appointment['AptTime']; ?></p>
                                            
                                            <?php if ($ro_appointment['Status'] == 'Accepted'): ?>
                                                <p class="text-success"><i class="fas fa-check-circle"></i> This appointment has been accepted and cannot be modified.</p>
                                            <?php else: ?>
                                                <p class="text-danger"><i class="fas fa-times-circle"></i> This appointment has been rejected and cannot be modified.</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No processed appointments found.</p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!$cancellation_limit_reached): ?>
                                <a href="index.php" class="btn btn-primary mt-3">Book New Appointment</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Appointment selector
                    var appointmentSelector = document.getElementById("appointmentSelector");
                    if (appointmentSelector) {
                        appointmentSelector.addEventListener("change", function() {
                            let selectedValue = this.value;

                            document.querySelectorAll(".appointment-details").forEach(function(el) {
                                el.style.display = "none";
                            });

                            if (selectedValue) {
                                document.getElementById(selectedValue).style.display = "block";
                            }
                        });
                    }
                    
                    // Date validation
                    var dateInput = document.getElementById('adate');
                    if (dateInput) {
                        function disablePastDates() {
                            var today = new Date();
                            var year = today.getFullYear();
                            var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Add leading zero
                            var day = today.getDate().toString().padStart(2, '0'); // Add leading zero
                            var minDate = `${year}-${month}-${day}`; // YYYY-MM-DD format

                            dateInput.setAttribute("min", minDate);
                        }

                        disablePastDates(); // Set min date on page load

                        dateInput.addEventListener('change', function() {
                            var selectedDate = new Date(this.value);
                            var today = new Date();
                            today.setHours(0, 0, 0, 0); // Remove time part for accurate comparison

                            if (selectedDate < today) {
                                alert("You cannot select a past date.");
                                this.value = ""; // Reset selection
                            }
                        });
                    }
                    
                    // Tab switching
                    var tabs = document.querySelectorAll('.appointment-tab');
                    tabs.forEach(function(tab) {
                        tab.addEventListener('click', function() {
                            // Remove active class from all tabs
                            tabs.forEach(function(t) {
                                t.classList.remove('active');
                            });
                            
                            // Add active class to clicked tab
                            this.classList.add('active');
                            
                            // Hide all content
                            document.querySelectorAll('.appointment-content').forEach(function(content) {
                                content.classList.remove('active');
                            });
                            
                            // Show corresponding content
                            var tabName = this.getAttribute('data-tab');
                            document.getElementById(tabName + '-appointments').classList.add('active');
                        });
                    });
                });
            </script>
            <script src="js/jquery.min.js"></script>
            <script src="js/jquery-migrate-3.0.1.min.js"></script>
            <script src="js/popper.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/jquery.easing.1.3.js"></script>
            <script src="js/jquery.waypoints.min.js"></script>
            <script src="js/jquery.stellar.min.js"></script>
            <script src="js/owl.carousel.min.js"></script>
            <script src="js/jquery.magnific-popup.min.js"></script>
            <script src="js/aos.js"></script>
            <script src="js/jquery.animateNumber.min.js"></script>
            <script src="js/jquery.timepicker.min.js"></script>
            <script src="js/scrollax.min.js"></script>
            <script src="js/main.js"></script>
</body>
</html>