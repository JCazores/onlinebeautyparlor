<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
} 
?>
<!DOCTYPE HTML>
<html>
<head>
<title>RFBS | Admin Dashboard</title>

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
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<!--Calender-->
<link rel="stylesheet" href="css/clndr.css" type="text/css" />
<script src="js/underscore-min.js" type="text/javascript"></script>
<script src= "js/moment-2.2.1.js" type="text/javascript"></script>
<script src="js/clndr.js" type="text/javascript"></script>
<script src="js/site.js" type="text/javascript"></script>
<!--End Calender-->
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->
<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: transform 0.3s;
        background-color: #fff;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .card-header {
        border-radius: 15px 15px 0 0;
        padding: 15px;
        color: white;
        font-weight: bold;
    }
    .card-body {
        padding: 20px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .summary-card {
        border-radius: 15px;
        background: linear-gradient(145deg, #ff5757, #cd4949);
        color: white;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .summary-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .summary-title {
        font-size: 1rem;
        opacity: 0.8;
        margin-top: 0;
    }
    .dashboard-title {
        margin-bottom: 30px;
        color: #333;
        font-weight: 700;
        border-left: 5px solid #ff5757;
        padding-left: 15px;
    }
    
    /* Keeping original color scheme */
    .color-primary { background-color: #cd4949; }
    .color-secondary { background-color: #9d3535; }
    .color-tertiary { background-color: #ff5757; }
    .color-quaternary { background-color: #e49191; }
</style>
</head> 
<body class="cbp-spmenu-push">
<div class="main-content">
    
    <?php include_once('includes/sidebar.php');?>
    
    <?php include_once('includes/header.php');?>
    <!-- main content start-->
    <div id="page-wrapper" class="row calender widget-shadow">
        <div class="main-page">
            <h2 class="dashboard-title">Dashboard Analytics</h2>
            
            <div class="row">
                <!-- Fetch all the data first -->
                <?php 
                // Customers count
                $query1=mysqli_query($con,"Select * from tblcustomers");
                $totalcust=mysqli_num_rows($query1);
                
                // Appointment count
                $query2=mysqli_query($con,"Select * from tblappointment");
                $totalappointment=mysqli_num_rows($query2);
                
                // Accepted appointments
                $query3=mysqli_query($con,"Select * from tblappointment where Status='1'");
                $totalaccapt=mysqli_num_rows($query3);
                
                // Rejected appointments
                $query4=mysqli_query($con,"Select * from tblappointment where Status='2'");
                $totalrejapt=mysqli_num_rows($query4);
                
                // Services count
                $query5=mysqli_query($con,"Select * from tblservices");
                $totalser=mysqli_num_rows($query5);
                
                // Today's sales
                $todysale = 0;
                $query6=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
                from tblinvoice 
                join tblservices on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)=CURDATE();");
                while($row=mysqli_fetch_array($query6)) {
                    $todays_sale=$row['Cost'];
                    $todysale+=$todays_sale;
                }
                
                // Yesterday's sales
                $yesterdaysale = 0;
                $query7=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
                from tblinvoice 
                join tblservices on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)=CURDATE()-1;");
                while($row7=mysqli_fetch_array($query7)) {
                    $yesterdays_sale=$row7['Cost'];
                    $yesterdaysale+=$yesterdays_sale;
                }
                
                // Last 7 days sales
                $tseven = 0;
                $query8=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
                from tblinvoice 
                join tblservices on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)>=(DATE(NOW()) - INTERVAL 7 DAY);");
                while($row8=mysqli_fetch_array($query8)) {
                    $sevendays_sale=$row8['Cost'];
                    $tseven+=$sevendays_sale;
                }
                
                // Total sales
                $totalsale = 0;
                $query9=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
                from tblinvoice 
                join tblservices on tblservices.ID=tblinvoice.ServiceId");
                while($row9=mysqli_fetch_array($query9)) {
                    $total_sale=$row9['Cost'];
                    $totalsale+=$total_sale;
                }
                
                // Get monthly sales data for the current year
                $currentYear = date('Y');
                $monthlySales = array_fill(0, 12, 0); // Initialize with zeroes for all 12 months
                $monthNames = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
                
                $queryMonthly = mysqli_query($con, "SELECT MONTH(PostingDate) as month, 
                                                  SUM(tblservices.Cost) as monthly_sale
                                           FROM tblinvoice
                                           JOIN tblservices ON tblservices.ID=tblinvoice.ServiceId
                                           WHERE YEAR(PostingDate) = $currentYear
                                           GROUP BY MONTH(PostingDate)
                                           ORDER BY MONTH(PostingDate)");
                                           
                while($row = mysqli_fetch_array($queryMonthly)) {
                    $month = $row['month'] - 1; // Adjust for 0-indexed array
                    $monthlySales[$month] = $row['monthly_sale'];
                }
                
                // Get service distribution data (top 5 services by revenue)
                $serviceRevenue = array();
                $serviceNames = array();
                
                $queryServices = mysqli_query($con, "SELECT tblservices.ServiceName as service_name,
                                                   SUM(tblservices.Cost) as service_revenue,
                                                   COUNT(*) as service_count
                                            FROM tblinvoice
                                            JOIN tblservices ON tblservices.ID=tblinvoice.ServiceId
                                            GROUP BY tblinvoice.ServiceId
                                            ORDER BY service_revenue DESC
                                            LIMIT 5");
                                            
                while($row = mysqli_fetch_array($queryServices)) {
                    $serviceNames[] = $row['service_name'];
                    $serviceRevenue[] = $row['service_revenue'];
                }
                ?>
                
                <!-- Summary Cards Row -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="summary-card" style="background: linear-gradient(145deg, #ff5757, #cd4949);">
                                <h3 class="summary-number"><?php echo $totalcust; ?></h3>
                                <p class="summary-title">Total Customers</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card" style="background: linear-gradient(145deg, #ff5757, #cd4949);">
                                <h3 class="summary-number"><?php echo $totalappointment; ?></h3>
                                <p class="summary-title">Total Appointments</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card" style="background: linear-gradient(145deg, #ff5757, #cd4949);">
                                <h3 class="summary-number"><?php echo $totalsale; ?></h3>
                                <p class="summary-title">Total Sales</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card" style="background: linear-gradient(145deg, #ff5757, #cd4949);">
                                <h3 class="summary-number"><?php echo $totalser; ?></h3>
                                <p class="summary-title">Services Offered</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Appointments Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header color-primary">
                            <h4>Appointment Status</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="appointmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Sales Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header color-secondary">
                            <h4>Monthly Sales (<?php echo $currentYear; ?>)</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthlySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sales Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header color-tertiary">
                            <h4>Sales Comparison</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="salesComparisonChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Revenue Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header color-quaternary">
                            <h4>Revenue Distribution</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Top Services by Revenue -->
                <?php if(count($serviceNames) > 0): ?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header color-primary">
                            <h4>Top Services by Revenue</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="serviceRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--footer-->
    <?php include_once('includes/footer.php');?>
    <!--//footer-->
</div>

<!-- Chart.js Implementation -->
<script>
    // Original color palette
    const colorPalette = {
        primary: ['#ff5757', '#cd4949', '#9d3535', '#e49191', '#ffaaaa'],
        secondary: ['#cd4949', '#ff5757', '#9d3535', '#e49191', '#ffaaaa'],
        accent: ['#9d3535', '#cd4949', '#ff5757', '#e49191', '#ffaaaa'],
        revenue: ['#ff5757', '#cd4949', '#9d3535']
    };
    
    // Appointment Status Chart
    var ctxAppointment = document.getElementById('appointmentChart').getContext('2d');
    var appointmentChart = new Chart(ctxAppointment, {
        type: 'pie',
        data: {
            labels: ['Accepted', 'Rejected', 'Pending'],
            datasets: [{
                label: 'Appointments',
                data: [
                    <?php echo $totalaccapt; ?>, 
                    <?php echo $totalrejapt; ?>, 
                    <?php echo $totalappointment - $totalaccapt - $totalrejapt; ?>
                ],
                backgroundColor: [
                    '#28a745',  // Green for accepted
                    '#dc3545',  // Red for rejected
                    '#ffc107'   // Yellow for pending
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
    
    // Monthly Sales Chart
    var ctxMonthlySales = document.getElementById('monthlySalesChart').getContext('2d');
    var monthlySalesChart = new Chart(ctxMonthlySales, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthNames); ?>,
            datasets: [{
                label: 'Monthly Sales',
                data: <?php echo json_encode($monthlySales); ?>,
                backgroundColor: colorPalette.secondary,
                borderColor: '#5E35B1',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    
    // Sales Comparison Chart
    var ctxSalesComparison = document.getElementById('salesComparisonChart').getContext('2d');
    var salesComparisonChart = new Chart(ctxSalesComparison, {
        type: 'bar',
        data: {
            labels: ['Today', 'Yesterday', 'Last 7 Days', 'Total'],
            datasets: [{
                label: 'Sales Amount',
                data: [
                    <?php echo $todysale; ?>, 
                    <?php echo $yesterdaysale; ?>, 
                    <?php echo $tseven; ?>, 
                    <?php echo $totalsale; ?>
                ],
                backgroundColor: colorPalette.primary,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    
    // Revenue Distribution Chart (donut) with improved colors
    var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctxRevenue, {
        type: 'doughnut',
        data: {
            labels: ['Today', 'Yesterday', 'Previous Days'],
            datasets: [{
                data: [
                    <?php echo $todysale; ?>, 
                    <?php echo $yesterdaysale; ?>, 
                    <?php echo $totalsale - $todysale - $yesterdaysale; ?>
                ],
                backgroundColor: colorPalette.revenue,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            cutoutPercentage: 70,
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                        return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                    }
                }
            }
        }
    });
    
    <?php if(count($serviceNames) > 0): ?>
    // Service Revenue Chart
    var ctxServiceRevenue = document.getElementById('serviceRevenueChart').getContext('2d');
    var serviceRevenueChart = new Chart(ctxServiceRevenue, {
        type: 'horizontalBar',
        data: {
            labels: <?php echo json_encode($serviceNames); ?>,
            datasets: [{
                label: 'Revenue by Service',
                data: <?php echo json_encode($serviceRevenue); ?>,
                backgroundColor: colorPalette.accent,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    <?php endif; ?>
</script>

<!-- Classie -->
<script src="js/classie.js"></script>
<script>
    var menuLeft = document.getElementById('cbp-spmenu-s1'),
        showLeftPush = document.getElementById('showLeftPush'),
        body = document.body;
        
    showLeftPush.onclick = function() {
        classie.toggle(this, 'active');
        classie.toggle(body, 'cbp-spmenu-push-toright');
        classie.toggle(menuLeft, 'cbp-spmenu-open');
        disableOther('showLeftPush');
    };
    

    function disableOther(button) {
        if(button !== 'showLeftPush') {
            classie.toggle(showLeftPush, 'disabled');
        }
    }
</script>
<!--scrolling js-->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!--//scrolling js-->
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.js"></script>
</body>
</html>