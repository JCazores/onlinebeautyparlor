<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
} else {
    
    // Process reset of user cancellation count if requested
    if(isset($_POST['reset_cancellations'])) {
        $userId = $_POST['user_id'];
        
        // Delete all cancellation records for this user
        $reset_query = mysqli_query($con, "DELETE FROM tblcancellations WHERE UserID = '$userId'");
        
        if($reset_query) {
            $msg = "Cancellation count reset successfully for user.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>RFBS || Manage Cancellations</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Stylesheets -->
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/custom.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>

    <!-- Scripts -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/wow.min.js"></script>
    <script> new WOW().init(); </script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/custom.js"></script>
    
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .warning-row {
            background-color: #fff3cd !important;
        }
        
        .btn-reset {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .btn-reset:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="main-page">
                <?php if(isset($msg)){ ?>
                <div class="success-message"><?php echo $msg; ?></div>
                <?php } ?>
                <?php if(isset($error)){ ?>
                <div class="error-message"><?php echo $error; ?></div>
                <?php } ?>
                
                <div class="tables">
                    <h3 class="title1">User Cancellation Summary</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Users with Cancellations:</h4>
                        <table class="table table-bordered table-striped table-hover"> 
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Cancellations Count</th>
                                    <th>Last Cancellation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Query to get users with cancellation counts and their details
                            $user_query = mysqli_query($con, "SELECT u.ID, u.UserName, u.Email, 
                                                            COUNT(c.ID) as cancellation_count,
                                                            MAX(c.CancellationDate) as last_cancellation
                                                      FROM tblusers u
                                                      JOIN tblcancellations c ON u.ID = c.UserID
                                                      GROUP BY u.ID, u.UserName, u.Email
                                                      ORDER BY cancellation_count DESC");
                            
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($user_query)) {
                                $warning_class = ($row['cancellation_count'] >= 5) ? 'warning-row' : '';
                            ?>
                                <tr class="<?php echo $warning_class; ?>">
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlentities($row['ID']); ?></td>
                                    <td><?php echo htmlentities($row['UserName']); ?></td>
                                    <td><?php echo htmlentities($row['Email']); ?></td>
                                    <td><?php echo htmlentities($row['cancellation_count']); ?></td>
                                    <td><?php echo htmlentities($row['last_cancellation']); ?></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="user_id" value="<?php echo $row['ID']; ?>">
                                            <button type="submit" name="reset_cancellations" class="btn-reset" onclick="return confirm('Are you sure you want to reset cancellation count for this user?');">Reset Count</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php 
                                $cnt++;
                            }
                            
                            if(mysqli_num_rows($user_query) == 0) {
                                echo "<tr><td colspan='7' style='text-align: center;'>No users with cancellations found.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tables">
                    <h3 class="title1">Individual Cancellations</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Cancellation Details:</h4>
                        <table class="table table-bordered table-striped table-hover"> 
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Appointment ID</th>
                                    <th>Cancellation Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Query the correct table for cancellation details
                            $ret = mysqli_query($con, "SELECT c.*, u.UserName 
                                                      FROM tblcancellations c
                                                      JOIN tblusers u ON c.UserID = u.ID
                                                      ORDER BY c.CancellationDate DESC");
                            
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) {
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlentities($row['UserID']); ?></td>
                                    <td><?php echo htmlentities($row['UserName']); ?></td>
                                    <td><?php echo htmlentities($row['AppointmentID']); ?></td>
                                    <td><?php echo htmlentities($row['CancellationDate']); ?></td>
                                </tr>
                            <?php 
                                $cnt++;
                            }
                            
                            if(mysqli_num_rows($ret) == 0) {
                                echo "<tr><td colspan='5' style='text-align: center;'>No cancellation records found.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;

        showLeftPush.onclick = function () {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };

        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
    </script>

    <!-- Bootstrap Scripts -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
<?php } ?>