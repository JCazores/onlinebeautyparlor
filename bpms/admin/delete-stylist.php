<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_GET['deleteid'])) {
        $deleteid = $_GET['deleteid'];

        // SQL query to delete the stylist record from the database
        $query = mysqli_query($con, "DELETE FROM tblstylist WHERE ID = '$deleteid'");

        // Check if the query executed successfully
        if ($query) {
            echo "<script>alert('Stylist deleted successfully.');</script>";
            echo "<script>window.location.href = 'manage-stylist.php';</script>"; // Redirect to manage services page
        } else {
            echo "<script>alert('Error: Unable to delete stylist.');</script>";
        }
    }
}
?>
