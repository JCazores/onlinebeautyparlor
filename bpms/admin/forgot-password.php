<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $newpassword = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($con, $username);
    $email = mysqli_real_escape_string($con, $email);
    $newpassword = mysqli_real_escape_string($con, $newpassword);

    // Check if the username and email match
    $query = mysqli_query($con, "SELECT ID FROM tblusers WHERE UserName='$username' AND Email='$email'");
    $ret = mysqli_fetch_array($query);

    if ($ret) {
        if ($newpassword === $confirmpassword) {
            // Update password
            $hashed_password = md5($newpassword);
            $update_query = mysqli_query($con, "UPDATE tblusers SET Password='$hashed_password' WHERE ID='" . $ret['ID'] . "'");

            if ($update_query) {
                $msg = "Password successfully reset.";
            } else {
                $msg = "There was an issue resetting your password.";
            }
        } else {
            $msg = "Passwords do not match.";
        }
    } else {
        $msg = "No account found with that username and email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>RFBS || Forgot Password</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Work Sans', sans-serif;
            background-color: #f1f1f1;
            color: #333;
        }
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        .login-page {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-page .title1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .input-container {
            position: relative;
            margin-bottom: 15px;
        }
        /* Input field styles */
        .input-container input {
            width: 100%;
            padding-left: 30px; /* Adjusts padding for the left side where the icon appears */
            padding-right: 30px;
            padding-top: 12px;
            padding-bottom: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Placeholder styling */
        .input-container input::placeholder {
            color: #aaa;
            font-size: 16px;
            padding-left: 10px; /* Make sure the placeholder aligns with the icon */
        }

        /* When input is focused */
        .input-container input:focus {
            padding-left: 30px; /* Same as initial padding to ensure consistency */
        }

        /* Icon styling */
        .input-container .fa-user {
            position: absolute;
            left: 10px;
            top: 30%;
            transform: translateY(-50%);
        }

        .input-container .fa-lock {
            position: absolute;
            left: 10px;
            top: 30%;
            transform: translateY(-50%);
        }
        .input-container .fa-envelope {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .input-container .fa-eye {
            position: absolute;
            right: 10px;
            top: 30%;
            transform: translateY(-50%);
        }

        /* Submit Button with Icon */
.input-container input[type="submit"] {
    padding: 12px 30px;
    background-color: rgb(139, 44, 88);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    position: relative;
    width: 100%; /* Ensure button width matches input field */
}

.input-container input[type="submit"]:hover {
    background-color: #e83e8c;
}

.input-container input[type="submit"] + .icon {
    position: absolute;
    right: 10px;
    top: 60%;
    transform: translateY(-50%);
    color: white;
}


        /* Link styling */
        .forgot-grid .forgot a {
            text-decoration: none;
            color: #4a90e2;
            font-size: 14px;
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        .forgot-grid .forgot a:hover {
            color: #357ab7;
        }
        
    </style>
</head>
<body>

    <div class="main-content">
        <div id="page-wrapper">
            <div class="main-page login-page">
                <h3 class="title1">Reset Password</h3>
                <div class="widget-shadow">
                    <div class="login-top" style="text-align: center;">
                        <h4>Reset your password</h4>
                    </div>
                    <div class="login-body">
                        <form method="post" action="">
                            <p style="font-size:16px; color:red; text-align:center;">
                                <?php if(isset($msg)) echo $msg; ?>
                            </p>

                            <!-- Username Field with Icon -->
                            <div class="input-container">
                                <i class="fa fa-user icon"></i>
                                <input type="text" name="username" placeholder="Username" required>
                            </div>

                            <!-- Email Field with Icon -->
                            <div class="input-container">
                                <i class="fa fa-envelope icon"></i>
                                <input type="email" name="email" placeholder="Email Address" required>
                            </div>

                            <!-- New Password Field with Icon and Show Password Feature -->
                            <div class="input-container">
                                <i class="fa fa-lock icon"></i>
                                <input type="password" id="password" name="newpassword" placeholder="New Password" required>
                                <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                            </div>

                            <!-- Confirm Password Field -->
                            <!-- Confirm Password Field -->
<div class="input-container">
    <i class="fa fa-lock icon"></i>
    <input type="password" id="confirmPassword" name="confirmpassword" placeholder="Confirm Password" required>
    <i class="fa fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
</div>


                            <!-- Submit Button with Icon -->
                            <div class="input-container">
                                <input type="submit" name="submit" value="Reset Password">
                                <i class="fa fa-sign-in-alt icon"></i>
                            </div>

                            <div class="forgot-grid">
                                <div class="forgot">
                                    <a href="../index.php">Go back to login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById("togglePassword");
const passwordField = document.getElementById("password");

togglePassword.addEventListener("click", function () {
    const type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
    this.classList.toggle("fa-eye-slash");
});

const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
const confirmPasswordField = document.getElementById("confirmPassword");

toggleConfirmPassword.addEventListener("click", function () {
    const type = confirmPasswordField.type === "password" ? "text" : "password";
    confirmPasswordField.type = type;
    this.classList.toggle("fa-eye-slash");
});

    </script>
</body>
</html>
