<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);
include('includes/dbconnection.php');
?>
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-light ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Rosa Flora Beauty Salon</a>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
<!--<li class="nav-item"><a href="appoint.php" class="nav-link">Book Appointment</a></li>-->
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="check_appointments.php" class="nav-link">My Appointment</a></li>

                <?php if (!isset($_SESSION['userid'])) { ?>
                    <!-- Show Login and Admin links only if the user is not logged in -->
                    <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                    <!--<li class="nav-item"><a href="admin/index.php" class="nav-link">Admin</a></li>-->
                <?php } else { ?>
                    <!-- Show a Logout button or User Dashboard link if the user is logged in -->
                    
                    <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
