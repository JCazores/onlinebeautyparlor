<?php 
include('includes/dbconnection.php');
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit']))
  {

    $name=$_POST['name'];
    $email=$_POST['email'];
    $services=$_POST['services'];
	$stylist=$_POST['stylist'];
    $adate=$_POST['adate'];
    $atime=$_POST['atime'];
    $phone=$_POST['phone'];
    $aptnumber = mt_rand(100000000, 999999999);
  
    $query=mysqli_query($con,"insert into tblappointment(AptNumber,Name,Email,PhoneNumber,AptDate,AptTime,Services,Stylist) value('$aptnumber','$name','$email','$phone','$adate','$atime','$services','$stylist')");
    if ($query) {
$ret=mysqli_query($con,"select AptNumber from tblappointment where Email='$email' and  PhoneNumber='$phone'");
$result=mysqli_fetch_array($ret);
$_SESSION['aptno']=$result['AptNumber'];
 echo "<script>window.location.href='thank-you.php'</script>";	
  }
  else
    {
      $msg="Something Went Wrong. Please try again";
    }

  
}
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);  // Always store hashed passwords
    
    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);

    // Execute query to check credentials in the tblusers table
    $query = mysqli_query($con, "SELECT ID, UserName FROM tblusers WHERE UserName='$username' AND Password='$password'");

    // Check if the query was successful
    if (!$query) {
        die("Error executing query: " . mysqli_error($con));  // Handle query errors
    }

    $ret = mysqli_fetch_array($query);
    if ($ret) {
        $_SESSION['userid'] = $ret['ID'];  // Store user ID in session
        
        // Redirect to user dashboard or homepage after successful login
        header('location:index.php');  // You can change this to the user home page or dashboard
    } else {
        $msg = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>RFBS||Home Page</title>
   
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
  </head>
  <style>
	#atime:before {
   content: 'Time:';
   margin-right: .6em;
   color: #9d9d9d;
}
option[title] {
    font-family: Arial, sans-serif;
	
    color: #333;
    background-color: #f9f9f9;
    padding: 5px;
    border-radius: 3px; /* Applies only visually to dropdown items */
}

option[title]:hover {
    color: #fff;
    background-color: #007BFF;
}
input {
    text-transform: none;
}


  </style>
  <body>
	  <?php include_once('includes/header.php');?>
    <!-- END nav -->

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
			            <p class="mb-4" style="color: white;">This parlour provides huge facilities with advanced technology equipments and best quality service. Here we offer best treatment that you might have never experienced before.</p>
			            
			           
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
    					
    					<div class="appointment-wrap">
    						<span class="subheading">Reservation</span>
								<h3 class="mb-2">Make an Appointment</h3>
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
				            <!--<div class="col-sm-12">
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
			              </div>-->
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

const formattedDate = ${yyyy}-${mm}-${dd};
document.getElementById('adate').setAttribute('min', formattedDate);

// Update booking status and disable/enable the submit button based on the selected date
document.getElementById('adate').addEventListener('change', function() {
    const selectedDate = this.value;

    // Fetch the booked appointments data from the PHP backend
    fetch(check_appointments.php?date=${selectedDate})
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
                document.getElementById('booking-status').textContent = Max 10 appointments per day. ${availableSlots} spots available.;
                document.getElementById('submitBtn').disabled = false; // Enable submit button if there are available spots
            }
        })
        .catch(error => {
            console.error('Error fetching appointment data:', error);
        });
		document.getElementById('adate').addEventListener('change', function () {
    const selectedDate = this.value;

    fetch(check_stylist_availability.php?date=${selectedDate})
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
                option.textContent = ${stylist.name} (${stylist.appointments} / 3 appointments);
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
        <select class="form-control" name="atime" id="atime" required>
            <option value="">Select Time</option>
            <?php
            for ($hour = 9; $hour <= 20; $hour++) {
                for ($minute = 0; $minute < 60; $minute++) {
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
            fetch(fetch_unavailable_times.php?stylist=${stylist}&date=${date})
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
                            Please log in to make an appointment
			              </div>
                                
				          </div>
				          <?php if (isset($_SESSION['userid'])): ?>
    <!-- User is logged in -->
    <div class="form-group" style="background-color: #ff5757;">
        <input type="submit" name="submit" value="Make an Appointment" class="btn btn-primary" id="submitBtn">
    </div>
<?php else: ?>
    <!-- User is not logged in -->
    <div class="form-group" style="background-color: #ff5757; padding: 0px; text-align: center;">
    <a href="login.php" style="display: block; text-decoration: none;">
        <button class="btn btn-primary" id="submitBtn" disabled style="width: 100%; background-color: transparent; border: none; cursor: pointer;">
            <span style="color: white; text-align: center;">Log in to continue.</span>
        </button>
    </a>
</div>

<?php endif; ?>

			          </form>
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


		
		<br>


   <?php include_once('includes/footer.php');?>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>
  <script>
function validateTime() {
    var timeInput = document.getElementById('atime');
    var time = timeInput.value;
    var minTime = "09:00";
    var maxTime = "20:00";
    
    if (time < minTime || time > maxTime) {
        alert("Please select a time between 9 AM and 8 PM.");
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
  <!--<script src="js/bootstrap-datepicker1.js"></script>-->
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
  

    
  </body>
</html>