<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login']))
  {
    $adminuser=$_POST['username'];
    $password=md5($_POST['password']);
    $query=mysqli_query($con,"select ID from tbladmin where  UserName='$adminuser' && Password='$password' ");
    $ret=mysqli_fetch_array($query);
    if($ret>0){
      $_SESSION['bpmsaid']=$ret['ID'];
     header('location:dashboard.php');
    }
    else{
    $msg="Invalid Details.";
    }
  }
  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>RFBS | Login Page </title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<!--<link href="css/style.css" rel='stylesheet' type='text/css' />-->
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
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Body styling */
body {
    background-color: #f4f6f9;
    color: #333;
    font-size: 14px;
    padding-top: 50px; /* Space at the top */
}

/* Container for center alignment */
.widget-shadow {
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

/* Heading Styles */
h3.title1 {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #4a90e2;
    margin-bottom: 30px;
}

.login-top h4 {
    text-align: center;
    font-size: 16px;
    color: #4a90e2;
    margin-bottom: 20px;
}

/* Form input styling */
.login-body input[type="text"],
.login-body input[type="password"] {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.login-body input[type="text"]:focus,
.login-body input[type="password"]:focus {
    border-color: #4a90e2;
    outline: none;
    background-color: #fff;
}

/* Button Styling */
.login-body input[type="submit"] {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    background-color: #4a90e2;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.login-body input[type="submit"]:hover {
    background-color: #357ab7;
}

/* Error message styling */
p {
    text-align: center;
    color: red;
    font-size: 14px;
    margin-top: 10px;
}

/* Link Styling */
a {
    text-decoration: none;
    color: #4a90e2;
    font-size: 14px;
    display: block;
    text-align: center;
    margin-top: 10px;
}

a:hover {
    color: #357ab7;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .widget-shadow {
        padding: 20px;
    }

    h3.title1 {
        font-size: 22px;
    }

    .login-body input[type="text"],
    .login-body input[type="password"] {
        font-size: 14px;
    }

    .login-body input[type="submit"] {
        padding: 12px;
        font-size: 14px;
    }
}

/* Back to Home and Register Links */
.forgot-grid .forgot a {
    display: inline-block;
    margin: 10px auto;
    color: #4a90e2;
    font-size: 14px;
}

.forgot-grid .forgot a:hover {
    color: #357ab7;
}
</style>

</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page login-page ">
				<h3 class="title1">Sign In</h3>
				<div class="widget-shadow">
					<div class="login-top">
						<h4>Welcome back to Rosa Flora Beauty Salon AdminPanel ! </h4>
					</div>
					<div class="login-body">
						<form role="form" method="post" action="">
							<p style="font-size:16px; color:red" text-align="center"> <?php if($msg){
    echo $msg;
  }  ?> </p>
							<input type="text" class="user" name="username" placeholder="Username" required="true">
							<input type="password" name="password" class="lock" placeholder="Password" required="true">
							<input type="submit" name="login" value="Sign In">
							<div class="forgot-grid">
								
								<div class="forgot">
									<a href="../index.php">Back to Home</a>
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="forgot-grid">
								
								<div class="forgot">
									<a href="forgot-password.php">forgot password?</a>
								</div>
								<div class="clearfix"> </div>
							</div>
						</form>
					</div>
				</div>
				
				
			</div>
		</div>
		
	</div>
	<!-- Classie -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showRightPush' ),
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