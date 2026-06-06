<?php
include('includes/dbconnection.php');
session_start();
error_reporting(0);

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('Please log in to view your appointments.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// Get logged-in user ID
$userid = $_SESSION['userid'];

// Fetch appointments for the logged-in user
$stmt = $con->prepare("SELECT * FROM tblappointment WHERE ID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include_once('includes/header.php'); ?>

<section class="ftco-section">
    <div class="container">
        <h2>Your Appointments</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Appointment Number</th>
                    <th>Service</th>
                    <th>Stylist</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['AptNumber']); ?></td>
                            <td><?php echo htmlspecialchars($row['Services']); ?></td>
                            <td><?php echo htmlspecialchars($row['Stylist']); ?></td>
                            <td><?php echo htmlspecialchars($row['AptDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['AptTime']); ?></td>
                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                            <td>
                                <?php if ($row['Status'] !== 'Cancelled') { ?>
                                    <form method="post" action="cancel_appointment.php">
                                        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($row['ID']); ?>">
                                        <button type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                                    </form>
                                <?php } else {
                                    echo "Cancelled";
                                } ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7'>No appointments found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>

</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$con->close();
?>
