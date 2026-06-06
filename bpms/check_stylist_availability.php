<?php
include('includes/dbconnection.php');

$date = $_GET['date'] ?? date('Y-m-d'); // Get the selected date or default to today

$result = [];
$query = mysqli_query($con, "SELECT * FROM tblstylist");
while ($row = mysqli_fetch_array($query)) {
    $stylistName = $row['StylistName'];

    // Count appointments for the stylist on the given date
    $appointmentQuery = mysqli_query($con, 
        "SELECT COUNT(*) AS appointmentCount 
        FROM tblappointment 
        WHERE Stylist = '$stylistName' AND AptDate = '$date'");
    $appointmentData = mysqli_fetch_assoc($appointmentQuery);
    $appointmentCount = isset($appointmentData['appointmentCount']) ? $appointmentData['appointmentCount'] : 0;

    $result[] = [
        'name' => $stylistName,
        'appointments' => $appointmentCount
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
?>
