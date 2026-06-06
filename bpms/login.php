<?php
session_start();
include('includes/dbconnection.php');

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);

    // Check if user is admin
    $query_admin = mysqli_query($con, "SELECT ID FROM tbladmin WHERE UserName='$username' AND Password='$password'");
    $admin = mysqli_fetch_array($query_admin);

    if ($admin) {
        $_SESSION['bpmsaid'] = $admin['ID'];
        header('location:admin/dashboard.php');
        exit();
    }

    // Check if user is a regular user
    $query_user = mysqli_query($con, "SELECT ID FROM tblusers WHERE UserName='$username' AND Password='$password'");
    $user = mysqli_fetch_array($query_user);

    if ($user) {
        $_SESSION['userid'] = $user['ID'];
        header('location:index.php');
        exit();
    }
    $msg = "Invalid username or password.";
}
?>
    

<!DOCTYPE HTML>
<html>
<head>
<title>RFBS | Login Page</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-1.11.1.min.js"></script>
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
</style>
</head>
<body>
    <div class="main-content">
        <div id="page-wrapper">
            <div class="main-page login-page">
                <h3 class="title1">Sign In</h3>
                <div class="widget-shadow">
                    <div class="login-top">
                        <h4>Welcome back to Rosa Flora Beauty Salon</h4>
                    </div>
                    <div class="login-body">
                        <form method="post" action="">
                            <p style="font-size:16px; color:red; text-align:center;">
                                <?php if(isset($msg)) echo $msg; ?>
                            </p>
                            <input type="text" name="username" placeholder="Username" required>
                            <input type="password" name="password" placeholder="Password" required>
                            <input type="submit" name="login" value="Sign In">
                            <div class="forgot-grid">
                                <div class="forgot">
                                    <a href="index.php">Back to Home</a>
                                </div>
                                <div class="forgot">
                                    <a href="admin/forgot-password.php">Forgot password?</a>
                                </div>
                                <div class="forgot">
                                    <a href="register.php">Don't have an account? Register</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
