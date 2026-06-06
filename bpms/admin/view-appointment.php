<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  exit();
} 

// Include PHPMailer classes at the top
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

// Initialize variables
$msg = "";
$emailStatus = "";

// Handle form submission
if(isset($_POST['submit'])) {
    $cid = $_GET['viewid'];
    $remark = $_POST['remark'];
    $status = $_POST['status'];
    
    // First get the appointment details for email sending
    $ret = mysqli_query($con, "SELECT * FROM tblappointment WHERE ID='$cid'");
    $row = mysqli_fetch_array($ret);
    
    if($row) {
        // Update the appointment status and remark
        $query = mysqli_query($con, "UPDATE tblappointment SET Remark='$remark', Status='$status', RemarkDate=NOW() WHERE ID='$cid'");
        
        if($query) {
            $msg = "Status updated successfully.";
            
            // Get email details from the database
            $customerEmail = $row['Email'];
            $customerName = $row['Name'];
            $aptNumber = $row['AptNumber'];
            $aptDate = $row['AptDate'];
            $aptTime = $row['AptTime'];
            
            // Determine status text
            $statusText = ($status == "1") ? "Accepted" : "Rejected";
            
            // Try to send email
            $mail = new PHPMailer(true);
            
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'smartmedsystem@gmail.com'; // Your email
                $mail->Password = 'dxrc vypx qelu irfj'; // Your app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Recipients
                $mail->setFrom('smartmedsystem@gmail.com', 'Appointment System');
                $mail->addAddress($customerEmail, $customerName);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Status Update';
                
                // Email body
                $emailBody = "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                    <h2 style='color: #4CAF50;'>Appointment Status Update</h2>
                    <p>Dear <strong>{$customerName}</strong>,</p>
                    <p>Your appointment: <strong>#{$aptNumber}</strong></p>
                    <p>Date: <strong>{$aptDate}</strong> </p>
                    <p>Your Appointment Status: <strong>{$statusText}</strong></p>
                    <p><strong>Please arrive at the salon 20 minutes early.</strong></p>";
                
                if(!empty($remark)) {
                    $emailBody .= "<p><strong>Remarks:</strong> {$remark}</p>";
                }
                
                $emailBody .= "
                    <p>Thank you for using our service and having an appointment with us.</p>
                    <hr>
                    <p style='font-size: 12px; color: #777;'>This is an automated message, please do not reply.</p>
                </div>";
                
                $mail->Body = $emailBody;
                $mail->AltBody = strip_tags($emailBody);
                
                // Send the email
                $mail->send();
                $emailStatus = "Email sent successfully to $customerEmail";
                
                // JavaScript alert
                echo "<script>
                    window.onload = function() {
                        alert('Email sent successfully to $customerEmail');
                    }
                </script>";
                
            } catch (Exception $e) {
                $emailStatus = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $msg = "Error updating status: " . mysqli_error($con);
        }
    } else {
        $msg = "Appointment not found.";
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>RFBS || View Appointment</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- font CSS -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
 <!-- js-->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<!--webfonts-->
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!--//webfonts--> 
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
<!--//end-animate-->
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		<!--left-fixed -navigation-->
		 <?php include_once('includes/sidebar.php');?>
		<!--left-fixed -navigation-->
		<!-- header-starts -->
		 <?php include_once('includes/header.php');?>
		<!-- //header-ends -->
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
				<div class="tables">
					<h3 class="title1">View Appointment</h3>
					
					<div class="table-responsive bs-example widget-shadow">
						<?php if(!empty($msg)): ?>
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Status:</strong> <?php echo $msg; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($emailStatus)): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong><i class="fa fa-check-circle"></i> Success!</strong> <?php echo $emailStatus; ?>
                            </div>
                        <?php endif; ?>
						
						<h4>View Appointment:</h4>
						<?php
$cid=$_GET['viewid'];
$ret=mysqli_query($con,"select * from tblappointment where ID='$cid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {
?>
						<table class="table table-bordered">
							<tr>
    <th>Appointment Number</th>
    <td><?php echo $row['AptNumber'];?></td>
  </tr>
  <tr>
<th>Name</th>
    <td><?php echo $row['Name'];?></td>
  </tr>

<tr>
    <th>Email</th>
    <td><?php echo $row['Email'];?></td>
  </tr>
   <tr>
    <th>Mobile Number</th>
    <td><?php echo $row['PhoneNumber'];?></td>
  </tr>
   <tr>
    <th>Appointment Date</th>
    <td><?php echo $row['AptDate'];?></td>
  </tr>
 
<tr>
    <th>Appointment Time</th>
    <td><?php echo $row['AptTime'];?></td>
  </tr>
  
  <tr>
    <th>Services</th>
    <td><?php echo $row['Services'];?></td>
  </tr>
  <tr>
    <th>Stylist</th>
    <td><?php echo $row['Stylist'];?></td>
  </tr>
  <tr>
    <th>Apply Date</th>
    <td><?php echo $row['ApplyDate'];?></td>
  </tr>
  
<tr>
    <th>Status</th>
    <td> <?php  
if($row['Status']=="1")
{
  echo "Accepted";
}

if($row['Status']=="2")
{
  echo "Rejected";
}

     ;?></td>
  </tr>
						</table>
						<table class="table table-bordered">
							<?php if($row['Remark']==""){ ?>

                <form name="update_form" method="post">
                  <tr>
                    <th>Remark :</th>
                    <td>
                      <textarea name="remark" placeholder="Add your remarks here" rows="6" cols="14" class="form-control wd-450"></textarea>
                    </td>
                  </tr>
                  
                  <tr>
                    <th>Status :</th>
                    <td>
                      <select name="status" class="form-control wd-450" required="true">
                        <option value="1" selected>Accepted</option>
                        <option value="2">Rejected</option>
                      </select>
                    </td>
                  </tr>

                  <tr align="center">
                    <td colspan="2">
                      <button type="submit" name="submit" class="btn btn-success btn-lg">
                        <i class="fa fa-paper-plane"></i> Update & Send Email
                      </button>
                    </td>
                  </tr>
                </form>

<?php } else { ?>
						</table>
						<table class="table table-bordered">
							<tr>
    <th>Remark</th>
    <td><?php echo $row['Remark']; ?></td>
  </tr>

<tr>
<th>Remark date</th>
<td><?php echo $row['RemarkDate']; ?>  </td></tr>

						</table>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!--footer-->
		
        <!--//footer-->
	</div>
	<!-- Classie -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
</body>
</html>
<?php ?>