<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    $username = mysqli_real_escape_string($con, $username);

    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT ID, Password FROM tbladmin WHERE UserName=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['Password'])) {
        $_SESSION['bpmsaid'] = $admin['ID'];
        header('location:admin/dashboard.php');
        exit();
    }

    // Check regular user
    $stmt = mysqli_prepare($con, "SELECT ID, Password FROM tblusers WHERE UserName=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['userid'] = $user['ID'];
        header('location:index.php');
        exit();
    }

    $msg = "Invalid username or password.";
}

if (isset($_POST['submit'])) {
    include('includes/dbconnection.php');

    if (!isset($_SESSION['userid'])) {
        die("User is not logged in. Please log in again.");
    }

    $userid = $_SESSION['userid']; // Get the logged-in user's ID

    $name = $_POST['name'];
    $email = $_POST['email'];
    $services = $_POST['services'];
    $stylist = $_POST['stylist'];
    $adate = $_POST['adate'];
    $atime = $_POST['atime'];
    $phone = $_POST['phone'];
    $aptnumber = mt_rand(100000000, 999999999);

    // Prepared statement for inserting appointments with UserID
    $stmt = mysqli_prepare($con, "INSERT INTO tblappointment (UserID, AptNumber, Name, Email, PhoneNumber, AptDate, AptTime, Services, Stylist) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iisssssss", $userid, $aptnumber, $name, $email, $phone, $adate, $atime, $services, $stylist);
    
    if (mysqli_stmt_execute($stmt)) {
        $ret = mysqli_prepare($con, "SELECT AptNumber FROM tblappointment WHERE Email=? AND PhoneNumber=?");
        mysqli_stmt_bind_param($ret, "ss", $email, $phone);
        mysqli_stmt_execute($ret);
        $result = mysqli_stmt_get_result($ret);
        $row = mysqli_fetch_assoc($result);

        $_SESSION['aptno'] = $row['AptNumber'];
        echo "<script>window.location.href='thank-you.php'</script>";
    } else {
        $msg = "Something Went Wrong. Please try again.";
    }
}

$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
$cancellation_limit_reached = false;
$cancellation_reset_date = '';
$days_until_reset = 0;

// Check if user has reached cancellation limit
if ($userid) {
    // Current date and one week ago date for comparison
    $current_date = date('Y-m-d H:i:s');
    $one_week_ago = date('Y-m-d H:i:s', strtotime('-1 week'));
    
    // Clean up old cancellations (older than one week) first
    $cleanup_query = mysqli_query($con, "DELETE FROM tblcancellations 
                                       WHERE UserID = '$userid' 
                                       AND CancellationDate < '$one_week_ago'");
    
    // Check current cancellation count after cleanup
    $cancellation_check = mysqli_query($con, "SELECT COUNT(*) as cancel_count, 
                                             MIN(CancellationDate) as oldest_cancellation 
                                             FROM tblcancellations 
                                             WHERE UserID = '$userid'");
    $cancellation_data = mysqli_fetch_assoc($cancellation_check);
    $cancellation_count = $cancellation_data['cancel_count'];
    $oldest_cancellation = $cancellation_data['oldest_cancellation'];
    
    // Calculate days until reset if there are cancellations
    if ($oldest_cancellation) {
        $cancellation_reset_date = date('Y-m-d', strtotime($oldest_cancellation . ' +1 week'));
        $days_until_reset = max(0, ceil((strtotime($cancellation_reset_date) - time()) / (60 * 60 * 24)));
        $cancellation_limit_reached = ($cancellation_count >= 5);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>RFBS || Home Page</title>
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
            justify-content: flex-start;
            align-items: center;
            padding: 40px;
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
    top: 50%;
    transform: translateY(-50%);
}

.input-container .fa-lock {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
}

.input-container .fa-eye {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

        .input-container input[type="submit"] {
            padding: 12px 30px;
            background-color: rgb(139, 44, 88);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            position: relative;
        }
        .input-container input[type="submit"]:hover {
            background-color: #e83e8c;
        }
        .input-container input[type="submit"] + .icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
        }
        #togglePassword {
            right: 10px;
        }
        .cancellation-warning {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .cancellation-warning p {
            margin-bottom: 5px;
        }
        .reset-date {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include_once('includes/header.php'); ?>

    <section id="home-section" class="hero" style="background-image: url(images/mainbg.png);" data-stellar-background-ratio="0.5">
        <div class="home-slider owl-carousel">
            <div class="slider-item js-fullheight">
                <div class="overlay"></div>
                <div class="container-fluid p-0">
                    <div class="row d-md-flex no-gutters slider-text align-items-end justify-content-end" data-scrollax-parent="true">
                        <img class="one-third align-self-end order-md-last img-fluid" src="images/bg_11.png" alt="">
                        <div class="one-forth d-flex align-items-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
                            <div class="text mt-5">
                                <span class="subheading" style="color: white;">Beauty Salon</span>
                                <h1 class="mb-4" style="color: white;">Get Pretty Look</h1>
                                <p class="mb-4" style="color: white;">We pride ourselves on our high quality work and attention to detail. The products we use are of top quality branded products.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-item js-fullheight">
	      	<div class="overlay"></div>
	        <div class="container-fluid p-0">
	          <div class="row d-flex no-gutters slider-text align-items-center justify-content-end" data-scrollax-parent="true">
	          	<img class="one-third align-self-end order-md-last img-fluid" src="images/bg_22.png" alt="">
		          <div class="one-forth d-flex align-items-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
		          	<div class="text mt-5">
		          		<span class="subheading" style="color: white;">Natural Beauty</span>
			            <h1 class="mb-4" style="color: white;">Beauty Salon</h1>
			            <p class="mb-4" style="color: white;">Our parlor offers extensive facilities equipped with advanced technology and delivers top-quality services. We provide exceptional treatments that promise a unique and unforgettable experience.</p>
			            
			           
		            </div>
		          </div>
	        	</div>
	        </div>
	      </div>
        </div>

    </section>

    <br>
    
        <section class="ftco-section ftco-no-pt ftco-booking">
    	<div class="container-fluid px-0">
    		<div class="row no-gutters d-md-flex justify-content-end">
    			<div class="one-forth d-flex align-items-end">
    				<div class="text">
    					<div class="overlay"></div>
    					<div class="appointment-wrap">
    						<span class="subheading">Reservation</span>
								<h3 class="mb-2">Make an Appointment</h3>
                                
                                <?php if (isset($_SESSION['userid']) && $cancellation_limit_reached): ?>
                                <div class="cancellation-warning">
                                    <h4><i class="fas fa-exclamation-triangle"></i> Booking Restricted</h4>
                                    <p>You have reached the maximum limit of 5 cancellations within a week.</p>
                                    <?php if ($days_until_reset > 0): ?>
                                        <p>Your booking privileges will be restored on <span class="reset-date"><?php echo date('F j, Y', strtotime($cancellation_reset_date)); ?></span> (in <?php echo $days_until_reset; ?> days).</p>
                                    <?php else: ?>
                                        <p>Please check your profile for details.</p>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
		    				<form action="#" method="post" class="appointment-form">
			            <div class="row">
			              <div class="col-sm-12">
			                <div class="form-group">
					              <input type="text" class="form-control" id="name" placeholder="Name" name="name" required="true">
					            </div>
			              </div>
			              <div class="col-sm-12">
			                <div class="form-group">
					              <input type="email" class="form-control" id="appointment_email" placeholder="Email" name="email" required="true">
					            </div>
			              </div>
				            <div class="col-sm-12">
			                <div class="form-group">
					              <div class="select-wrap">
		                      <div class="icon"><span class="ion-ios-arrow-down"></span></div>
		                      <select name="services" id="services" required="true" class="form-control">
		                      	<option value="">Select Services</option>
		                      	<?php $query=mysqli_query($con,"select * from tblservices");
              while($row=mysqli_fetch_array($query))
              {
              ?>
		                       <option value="<?php echo $row['ServiceName'];?>"><?php echo $row['ServiceName'];?></option>
		                       <?php } ?> 
		                      </select>
		                    </div>
					            </div>
			              </div>
						  <div class="col-sm-12">
    <div class="form-group">
        <div class="date-picker-container">
            <label for="adate">Choose a date:</label>
            <input type="date" id="adate" name="adate" min="" required>
            <!-- Legend for booking status -->
            <p id="booking-status" style="color: green; font-size: 14px;">Max 10 appointments per day</p>
        </div>
    </div>
</div>

<script>
  // Set the minimum date to today's date
const today = new Date();
const dd = String(today.getDate()).padStart(2, '0');
const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
const yyyy = today.getFullYear();

const formattedDate = `${yyyy}-${mm}-${dd}`;
document.getElementById('adate').setAttribute('min', formattedDate);

// Update booking status and disable/enable the submit button based on the selected date
document.getElementById('adate').addEventListener('change', function() {
    const selectedDate = this.value;

    // Fetch the booked appointments data from the PHP backend
    fetch(`check_appointments.php?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            const maxAppointments = 10;
            const bookedAppointments = data.bookedAppointments;
            const availableSlots = data.availableSlots;

            // Update booking status message
            if (data.isFullyBooked) {
                document.getElementById('booking-status').style.color = 'red';
                document.getElementById('booking-status').textContent = 'This day is fully booked.';
                document.getElementById('submitBtn').disabled = true; // Disable submit button if fully booked
            } else {
                document.getElementById('booking-status').style.color = 'green';
                document.getElementById('booking-status').textContent = `Max 10 appointments per day. ${availableSlots} spots available.`;
                document.getElementById('submitBtn').disabled = false; // Enable submit button if there are available spots
            }
        })
        .catch(error => {
            console.error('Error fetching appointment data:', error);
        });
		document.getElementById('adate').addEventListener('change', function () {
    const selectedDate = this.value;

    fetch(`check_stylist_availability.php?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            const stylistSelect = document.getElementById('stylist');
            stylistSelect.innerHTML = ''; // Clear existing options

            // Populate new options
            let defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select Stylist';
            stylistSelect.appendChild(defaultOption);

            data.forEach(stylist => {
                let option = document.createElement('option');
                option.value = stylist.name;
                option.textContent = `${stylist.name} (${stylist.appointments} / 3 appointments)`;
                option.disabled = stylist.appointments >= 3; // Disable if max reached
                stylistSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching stylist data:', error));
});

});


</script>

<div class="col-sm-12">
    <div class="form-group">
        <div class="select-wrap">
            <div class="icon"><span class="ion-ios-arrow-down"></span></div>
            <select name="stylist" id="stylist" required="true" class="form-control">
                <option value="">Select Stylist</option>
                <?php 
                // Query to find the most chosen stylist
                $mostChosenQuery = mysqli_query($con, 
                    "SELECT Stylist, COUNT(*) AS totalAppointments 
                    FROM tblappointment 
                    GROUP BY Stylist 
                    ORDER BY totalAppointments DESC 
                    LIMIT 1");
                
                $mostChosenStylist = mysqli_fetch_assoc($mostChosenQuery);
                $mostChosenStylistName = $mostChosenStylist['Stylist'] ?? '';
                
                // Query all stylists
                $query = mysqli_query($con, "SELECT * FROM tblstylist");
                while ($row = mysqli_fetch_array($query)) {
                    $stylistName = $row['StylistName'];

                    // Count appointments for the stylist on the selected date
                    $selectedDate = isset($_POST['adate']) ? $_POST['adate'] : date('Y-m-d');
                    $appointmentQuery = mysqli_query($con, 
                        "SELECT COUNT(*) AS appointmentCount 
                        FROM tblappointment 
                        WHERE Stylist = '$stylistName' AND AptDate = '$selectedDate'");
                    $appointmentData = mysqli_fetch_assoc($appointmentQuery);
                    $appointmentCount = $appointmentData['appointmentCount'] ?? 0;

                    // Disable stylist if they reached the max appointment limit (3)
                    $disabled = ($appointmentCount >= 3) ? 'disabled' : '';

                    // Check if the stylist is the most chosen one
                    $selected = ($stylistName === $mostChosenStylistName) ? 'selected' : '';
                ?>
                
                <option value="<?php echo $stylistName; ?>" 
                    data-appointments="<?php echo $appointmentCount; ?>" 
                    <?php echo $disabled; ?> 
                    <?php echo $selected; ?>
                    title="STYLIST INFORMATION&#10;&#10;Day Off: <?php echo $row['Day Off']; ?> &#10;&#10;Age: <?php echo $row['Age']; ?> &#10;&#10;Year of Experience: <?php echo $row['Year of Experience']; ?>">
                    <?php echo $stylistName; ?> 
                    (<?php echo $appointmentCount; ?> / 3 appointments)
                </option>
                
                <?php } ?> 
            </select>
        </div>
    </div>
</div>


<div class="col-sm-12">
    <div class="form-group">
        <select class="form-control" name="atime" id="atime" required onchange="validateTime()">
            <option value="">Select Time</option>
            <?php
            for ($hour = 9; $hour <= 20; $hour++) {
                foreach ([0, 30] as $minute) { // Only 00 and 30 minutes
                    if ($hour == 20 && $minute > 0) break; // Stop at 20:00
                    
                    $time24 = sprintf("%02d:%02d", $hour, $minute); // 24-hour format
                    $time12 = date("h:i A", strtotime($time24));   // Convert to 12-hour format with AM/PM
                    echo "<option value='$time24'>$time12</option>";
                }
            }
            ?>
        </select>
    </div>
</div>

<script>
    const timeSelect = document.getElementById('atime');
    const stylistSelect = document.getElementById('stylist');
    const dateInput = document.getElementById('adate');

    function updateUnavailableTimes() {
        const stylist = stylistSelect.value;
        const date = dateInput.value;

        if (stylist && date) {
            // Fetch unavailable times for the selected stylist and date
            fetch(`fetch_unavailable_times.php?stylist=${stylist}&date=${date}`)
                .then(response => response.json())
                .then(unavailableTimes => {
                    // Reset all options to enabled
                    Array.from(timeSelect.options).forEach(option => option.disabled = false);

                    // Disable options matching unavailable times
                    unavailableTimes.forEach(time => {
                        const option = Array.from(timeSelect.options).find(opt => opt.value === time);
                        if (option) {
                            option.disabled = true;
                        }
                    });
                })
                .catch(error => console.error('Error fetching unavailable times:', error));
        }
    }

    // Attach event listeners to update unavailable times dynamically
    stylistSelect.addEventListener('change', updateUnavailableTimes);
    dateInput.addEventListener('change', updateUnavailableTimes);
</script>



			              <div class="col-sm-12">
			                <div class="form-group">
			                  <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" required="true" maxlength="10" pattern="[0-9]+">
			                </div>
			              </div>
				          </div>
				          <?php if (isset($_SESSION['userid'])): ?>
    <!-- User is logged in -->
    <div class="form-group" style="background-color: #ff5757; padding: 10px; border-radius: 5px;">
        <input type="submit" name="submit" value="Make an Appointment" class="btn btn-primary w-100" id="submitBtn">
    </div>
<?php else: ?>
    <!-- User is not logged in -->
    <div class="form-group" style="background-color: #ff5757; padding: 10px; border-radius: 5px;">
        <button class="btn btn-primary w-100" onclick="redirectToLogin()">
            Please log in to make an appointment
        </button>
    </div>

    <script>
        function redirectToLogin() {
            window.location.href = 'login.php';
        }
    </script>
<?php endif; ?>


			          </form>
                      <?php endif; ?>
		          </div>
						</div>
    			</div>
					<div class="one-third">
						<div class="img" style="background-image: url(images/bg-1.jpg); background-size: cover;">
						</div>
					</div>
    		</div>
    	</div>
    </section>

    <!--<div class="login-image">
        <img src="images/login-image.png" alt="Login Image" />
    </div>-->

    <?php include_once('includes/footer.php'); ?>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordField = document.getElementById("password");

        togglePassword.addEventListener("click", function () {
            // Toggle the type attribute
            const type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            // Toggle the icon
            this.classList.toggle("fa-eye-slash");
        });
    </script>
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>
    <script>
function validateTime() {
    var timeInput = document.getElementById('atime');
    var time = timeInput.value;
    var minTime = "09:00";
    var maxTime = "20:00";
    
    if (time < minTime || time > maxTime) {
        alert("Please select a time between 9:00 AM and 8:00 PM.");
        timeInput.value = ''; // Clear the invalid time input
    }
}
</script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/jquery.timepicker.min.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>