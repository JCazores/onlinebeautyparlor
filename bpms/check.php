<?php
include('includes/dbconnection.php'); // Database connection

// Check if the date is provided
if (isset($_GET['date'])) {
    $date = $_GET['date'];

    // Query the database to get the number of appointments for the given date
    $query = "SELECT COUNT(*) AS bookedAppointments 
              FROM tblappointment 
              WHERE appointment_date = ? AND Status = 1";  // Assuming '1' means accepted appointments

    // Prepare and execute the query
    if ($stmt = mysqli_prepare($con, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $bookedAppointments);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Return the number of booked appointments as JSON
        echo json_encode(['bookedAppointments' => $bookedAppointments]);
    } else {
        // Return error message if the query fails
        echo json_encode(['error' => 'Query failed']);
    }
} else {
    // Return an error message if no date is provided
    echo json_encode(['error' => 'Date parameter missing']);
}

?>
