<?php
include('includes/dbconnection.php');
session_start();

if (isset($_POST['cancel'])) {
    $appointmentId = $_POST['appointment_id'];

    // Update the appointment status to 'Cancelled'
    $query = mysqli_query($con, "UPDATE tblappointment SET Status='Cancelled' WHERE ID='$appointmentId'");

    if ($query) {
        echo "<script>alert('Appointment cancelled successfully.');</script>";
        echo "<script>window.location.href='your_appointments.php';</script>";
    } else {
        echo "<script>alert('Unable to cancel the appointment. Please try again.');</script>";
        echo "<script>window.location.href='your_appointments.php';</script>";
    }
}
?>
