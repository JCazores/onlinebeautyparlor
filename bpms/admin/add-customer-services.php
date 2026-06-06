<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_POST['submit'])) {
    $uid = intval($_GET['addid']);
    $invoiceid = mt_rand(100000000, 999999999);
    
    if (!empty($_POST['sids'])) {
        $sid = $_POST['sids'];
        $success = true;
        
        // Add current date as PostingDate
        $postingDate = date('Y-m-d');

        foreach ($sid as $svid) {
            $svid = intval($svid); // Prevent SQL Injection
            $query = "INSERT INTO tblinvoice (Userid, ServiceId, BillingId, PostingDate) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iiis", $uid, $svid, $invoiceid, $postingDate);
                $ret = mysqli_stmt_execute($stmt);

                if (!$ret) {
                    $success = false;
                }
                mysqli_stmt_close($stmt);
            } else {
                $success = false;
            }
        }

        if ($success) {
            echo '<script>alert("Invoice created successfully. Invoice number is ' . $invoiceid . '"); window.location.href="invoices.php";</script>';
        } else {
            echo '<script>alert("Error: Could not save invoice! ' . mysqli_error($con) . '");</script>';
        }
    } else {
        echo '<script>alert("Please select at least one service.");</script>';
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>RFBS || Assign Services</title>

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
<style>
	/* Custom Checkbox Styling */
input[type="checkbox"] {
    appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid black;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease-in-out;
}

/* Checkbox Checked State */
input[type="checkbox"]:checked {
    background-color: white;
}

/* Checkbox Checkmark */
input[type="checkbox"]::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 6px; /* Slightly reduced for better proportion */
    height: 12px;
    border: solid green;
    border-width: 0 2px 2px 0;
    transform: translate(-50%, -50%) rotate(45deg);
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

/* Show Checkmark on Click */
input[type="checkbox"]:checked::after {
    opacity: 1;
}


</style>
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
					<h3 class="title1">Assign Services</h3>
					
					
				
					<div class="table-responsive bs-example widget-shadow">
						<h4>Assign Services:</h4>
<form method="post">
						<table class="table table-bordered"> <thead> <tr> <th>#</th> <th>Service Name</th> <th>Service Price</th> <th>Action</th> </tr> </thead> <tbody>
<?php
$ret=mysqli_query($con,"select *from  tblservices");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>

 <tr> 
<th scope="row"><?php echo $cnt;?></th> 
<td><?php  echo $row['ServiceName'];?></td> 
<td><?php  echo $row['Cost'];?></td> 
<td><input type="checkbox" name="sids[]" value="<?php  echo $row['ID'];?>" ></td> 
</tr>   
<?php 
$cnt=$cnt+1;
}?>
<tr>
<td colspan="4" align="center">
<button type="submit" name="submit" class="btn btn-default">Submit</button>		
</td>

</tr>

</tbody> </table> 
</form>
					</div>
				</div>
			</div>
		</div>
		<!--footer-->
		 <?php include_once('includes/footer.php');?>
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
		<script>
			document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            this.classList.add("clicked");
            setTimeout(() => {
                this.classList.remove("clicked");
            }, 200);
        });
    });
});

		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
</body>
</html>