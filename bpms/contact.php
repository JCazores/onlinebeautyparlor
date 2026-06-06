<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>RFBS-Contact us</title>
    
    <!-- Stylesheets (no change) -->
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
  <body>
    <?php include_once('includes/header.php');?>

    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg.png');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5">
            <h2 class="mb-0 bread" style="color: white;">Contact Us</h2>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php" style="color: white;">Home <i class="ion-ios-arrow-forward" style="color: white;"></i></a></span> <span style="color: white;">Contact <i class="ion-ios-arrow-forward" style="color: white;"></i></span></p>
          </div>
        </div>
      </div>
    </section>
    
    <section class="contact-section bg-light">
      <div class="container">
        <div class="row no-gutters d-flex contact-info">
          <?php
          $ret=mysqli_query($con,"select * from tblpage where PageType='contactus' ");
          $cnt=1;
          while ($row=mysqli_fetch_array($ret)) {
          ?>
          <div class="col-md-3 d-flex">
            <div class="align-self-stretch box p-4 py-md-5 text-center">
              <div class="icon d-flex align-items-center justify-content-center">
                <span class="icon-map-signs"></span>
              </div>
              <h3 class="mb-4">Address</h3>
              <p><?php  echo $row['PageDescription'];?></p>
            </div>
          </div>
          <div class="col-md-3 d-flex">
            <div class="align-self-stretch box p-4 py-md-5 text-center">
              <div class="icon d-flex align-items-center justify-content-center">
                <span class="icon-phone2"></span>
              </div>
              <h3 class="mb-4">Contact Number</h3>
              <p><a href="tel://1234567920">+ <?php  echo $row['MobileNumber'];?></a></p>
            </div>
          </div>
          <div class="col-md-3 d-flex">
            <div class="align-self-stretch box p-4 py-md-5 text-center">
              <div class="icon d-flex align-items-center justify-content-center">
                <span class="icon-paper-plane"></span>
              </div>
              <h3 class="mb-4">Email Address</h3>
              <p><a href="mailto:info@yoursite.com"><?php  echo $row['Email'];?></a></p>
            </div>
          </div>
          <div class="col-md-3 d-flex">
            <div class="align-self-stretch box p-4 py-md-5 text-center">
              <div class="icon d-flex align-items-center justify-content-center">
                <span class="icon-globe"></span>
              </div>
              <h3 class="mb-4">Timing</h3>
              <p><?php  echo $row['Timing'];?></p>
            </div>
          </div>
          <?php } ?>
        </div>
        
        <!-- Map Section with Image -->
        <!-- Map Section with Image -->
       <!-- <div class="row justify-content-center mt-5">
  <div class="col-md-8">
    <h3 class="text-center mb-4">Find Us on the Map</h3>
    <div id="map" style="width: 100%; height: 400px;"></div> <!-- Map Container -->
  </div>
</div>


      </div>
    </section>
    
    <?php include_once('includes/footer.php');?>

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>
    <script>
  function initMap() {
    // Latitude and Longitude of the location
    var location = {lat: 14.721512, lng: 121.051941};

    // Create a map centered at the location
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 14,  // Zoom level
      center: location
    });

    // Add a marker at the location
    var marker = new google.maps.Marker({
      position: location,
      map: map,
      title: 'Our Location'
    });
  }
</script>

<!-- Include Google Maps API with the initMap function -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
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
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/jquery.timepicker.min.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
