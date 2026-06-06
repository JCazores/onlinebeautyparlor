<?php
include('includes/dbconnection.php');

if (isset($_GET['stylist']) && isset($_GET['date'])) {
    $stylist = $_GET['stylist'];
    $date = $_GET['date'];

    $query = mysqli_query($con, "SELECT AptTime FROM tblappointment WHERE Stylist = '$stylist' AND AptDate = '$date'");
    $unavailableTimes = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $unavailableTimes[] = $row['AptTime'];
    }

    echo json_encode($unavailableTimes);
}
?>
