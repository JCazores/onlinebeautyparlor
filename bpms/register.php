<?php
session_start();
include('includes/dbconnection.php');

// User Login Logic
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);

    // Query to check user credentials
    $query = mysqli_query($con, "SELECT ID FROM tblusers WHERE UserName='$username' AND Password='$password'");

    if (!$query) {
        die("Error executing query: " . mysqli_error($con));  // Output query error if it fails
    }

    $result = mysqli_fetch_array($query);
    if ($result) {
        $_SESSION['userid'] = $result['ID'];  // Store user ID in session
        header('location:dashboard.php');  // Redirect to the user dashboard
    } else {
        $msg = "Invalid Details.";  // Invalid login credentials message
    }
}

// User Registration Logic
if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $email = $_POST['email'];

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);
    $email = mysqli_real_escape_string($con, $email);

    // Check if username already exists
    $query = mysqli_query($con, "SELECT UserName FROM tblusers WHERE UserName='$username'");
    if (!$query) {
        die("Error executing check query: " . mysqli_error($con));  // Check for errors in the query
    }
    $result = mysqli_fetch_array($query);

    if ($result) {
        $msg = "Username already exists!";  // Message if username exists
    } else {
        // Insert new user into the tblusers database
        $query = "INSERT INTO tblusers (UserName, Password, Email) VALUES ('$username', '$password', '$email')";
        
        if (mysqli_query($con, $query)) {
            $msg = "Registration successful!";  // Success message
        } else {
            // Output any SQL error that occurred during registration
            $msg = "Error during registration: " . mysqli_error($con);  
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>RFBS | Registration Page</title>

<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
    padding-top: 50px;
}

/* Container for center alignment */
.container {
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

/* Heading Styles */
h3 {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #4a90e2;
    margin-bottom: 30px;
}

/* Form input styling */
.form-input {
    margin-bottom: 20px;
    position: relative;
}

.form-input input {
    width: 100%;
    padding: 15px 35px 15px 40px; /* Extra padding for the icon */
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

.form-input input:focus {
    border-color: #4a90e2;
    outline: none;
    background-color: #fff;
}

/* Icon styling inside the input */
.form-input i {
    position: absolute;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
    color: #4a90e2;
}

/* For password visibility icon */
.form-input .fa-eye,
.form-input .fa-eye-slash {
    right: 10px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #4a90e2;
    margin-left: 90%;
}

/* Button Styling */
.btn {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    background-color: #4a90e2;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
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

/* Button Group Styling */
.btn-group {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 20px;
    margin-bottom: 20px;
}

.btn-secondary {
    width: 48%;
    padding: 12px;
    background-color: #f1f1f1;
    color: #4a90e2;
    text-align: center;
    border-radius: 5px;
    border: 1px solid #ccc;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background-color: #e0e0e0;
    color: #357ab7;
    border-color: #357ab7;
}
</style>

</head>
<body>

<div class="container">
    <h3>Registration</h3>
    <form method="POST" action="">
        <div class="form-input">
            <i class="fa fa-user"></i>
            <input type="text" name="username" placeholder="Username" required="true" 
                   pattern="^(?=(.*[A-Z]){1,2})(?=.*[\W_]).{8,16}$" 
                   title="Username must be between 8 to 16 characters, contain 1 or 2 uppercase letters, and at least one special character">
        </div>

        <div class="form-input">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required="true" 
                   pattern="^(?=(.*[A-Z]){1,2})(?=.*\d)(?=.*[\W_]).{8,16}$" 
                   title="Password must be between 8 to 16 characters, contain 1 or 2 uppercase letters, at least one number, and one special character">
            <i class="fa fa-eye" id="toggle-password" onclick="togglePassword()" style="cursor: pointer;"></i>
        </div>

        <div class="form-input">
    <i class="fa fa-envelope"></i>
    <input type="email" name="email" placeholder="Email" required="false" 
           pattern="^[a-zA-Z0-9._-]+@gmail\.com$"
           title="Email must end with @gmail.com (case insensitive) and may contain special characters like dots, hyphens, and underscores in the local part">
</div>


        <div class="form-input">
            <input type="submit" name="register" class="btn" value="Register">
        </div>
    </form>

    <p style="color:red; text-align:center;">
        <?php if(isset($msg)) echo $msg; ?> 
    </p>

    <div class="form-input btn-group">

        <a href="index.php" class="btn btn-secondary">Back to Login</a>
    </div>
</div>

<!-- Password Visibility Toggle Script -->
<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var icon = document.getElementById("toggle-password");

        if (passwordField.type === "password") {
            passwordField.type = "text"; // Show the password
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password"; // Hide the password
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.js"> </script>

</body>
</html>
