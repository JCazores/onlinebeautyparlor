<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
} else {
?>

<!DOCTYPE HTML>
<html>
<head>
<title>RFBS || Stylist Performance Analytics</title>

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
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
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
<style>
    .card {
        border-radius: 10px;
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
        border-radius: 10px 10px 0 0;
        padding: 15px;
        color: white;
        font-weight: bold;
        background-color: #cd4949;
    }
    .card-body {
        padding: 20px;
    }
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
        margin-bottom: 30px;
    }
    .top-performer {
        background-color: #fff8e1;
    }
    .recommendation-badge {
        padding: 5px 10px;
        border-radius: 15px;
        color: white;
        font-weight: bold;
        display: inline-block;
        background-color: #ff5757;
    }
    .table th {
        background-color: #f5f5f5;
    }
    .main-title {
        margin-bottom: 20px;
        color: #333;
        font-weight: 700;
        border-left: 5px solid #ff5757;
        padding-left: 15px;
    }
    .stats-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .stat-box {
        flex: 1;
        padding: 15px;
        margin: 0 10px;
        text-align: center;
        background: linear-gradient(145deg, #ff5757, #cd4949);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .stat-box h4 {
        font-size: 24px;
        margin: 5px 0;
    }
    .stat-box p {
        margin: 0;
        opacity: 0.8;
    }
</style>
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <div id="page-wrapper">
            <div class="main-page">
                <h3 class="main-title">Stylist Performance Analytics</h3>
                
                <?php
                // Get the total stylists count
                $totalStylists = 0;
                $queryTotal = mysqli_query($con, "SELECT COUNT(DISTINCT Stylist) AS TotalStylists FROM tblappointment WHERE Stylist != ''");
                if($row = mysqli_fetch_array($queryTotal)) {
                    $totalStylists = $row['TotalStylists'];
                }
                
                // Get the total appointments assigned to stylists
                $totalAppointments = 0;
                $queryAppointments = mysqli_query($con, "SELECT COUNT(*) AS TotalAppointments FROM tblappointment WHERE Status = 1 AND Stylist != ''");
                if($row = mysqli_fetch_array($queryAppointments)) {
                    $totalAppointments = $row['TotalAppointments'];
                }
                
                // Get the average appointments per stylist
                $averageAppointments = ($totalStylists > 0) ? round($totalAppointments / $totalStylists, 1) : 0;
                ?>
                
                <!-- Statistics Summary -->
                <div class="stats-container">
                    <div class="stat-box">
                        <p>Total Active Stylists</p>
                        <h4><?php echo $totalStylists; ?></h4>
                    </div>
                    <div class="stat-box">
                        <p>Total Appointments</p>
                        <h4><?php echo $totalAppointments; ?></h4>
                    </div>
                    <div class="stat-box">
                        <p>Average Per Stylist</p>
                        <h4><?php echo $averageAppointments; ?></h4>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Chart Section -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fa fa-bar-chart"></i> Stylist Appointment Distribution</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="stylistChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table Section -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fa fa-table"></i> Detailed Stylist Performance</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Stylist Name</th>
                                                <th>Appointments Count</th>
                                                <th>% of Total</th>
                                                <th>Performance</th>
                                                <th>Recommendation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // SQL query to find all stylists and their appointment count based on accepted appointments
                                        $query = "
                                            SELECT Stylist, COUNT(*) AS AppointmentCount
                                            FROM tblappointment
                                            WHERE Status = 1  /* Only accepted appointments */
                                              AND Stylist != '' /* Exclude empty stylist names */
                                            GROUP BY Stylist
                                            ORDER BY AppointmentCount DESC
                                        ";

                                        // Execute the query
                                        $result = mysqli_query($con, $query);
                                        
                                        // Arrays to store data for chart
                                        $stylistNames = array();
                                        $appointmentCounts = array();
                                        $backgroundColors = array();

                                        // Check if the query was successful
                                        if ($result) {
                                            // Check if the query returned any rows
                                            if (mysqli_num_rows($result) > 0) {
                                                $count = 1; // To keep track of the number in the table
                                                $highestAppointmentCount = 0; // Variable to store the highest appointment count
                                                
                                                // First, find the highest appointment count
                                                $row = mysqli_fetch_array($result);
                                                $highestAppointmentCount = $row['AppointmentCount'];

                                                // Move the pointer back to the first row
                                                mysqli_data_seek($result, 0);

                                                // Loop through all stylists
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $stylistName = $row['Stylist'];
                                                    $appointmentCount = $row['AppointmentCount'];
                                                    $percentageOfTotal = ($totalAppointments > 0) ? 
                                                        round(($appointmentCount / $totalAppointments) * 100, 1) : 0;
                                                    
                                                    // Performance rating based on average
                                                    $performance = '';
                                                    if ($appointmentCount > $averageAppointments * 1.2) {
                                                        $performance = '<span class="label label-success">Excellent</span>';
                                                    } elseif ($appointmentCount >= $averageAppointments) {
                                                        $performance = '<span class="label label-primary">Good</span>';
                                                    } elseif ($appointmentCount >= $averageAppointments * 0.8) {
                                                        $performance = '<span class="label label-warning">Average</span>';
                                                    } else {
                                                        $performance = '<span class="label label-danger">Below Average</span>';
                                                    }

                                                    // Check if the stylist has the highest appointment count
                                                    $rowClass = '';
                                                    $recommendation = '';
                                                    if ($appointmentCount == $highestAppointmentCount) {
                                                        $recommendation = '<span class="recommendation-badge">Add Salary</span>';
                                                        $rowClass = 'top-performer';
                                                    }

                                                    // Add data for chart
                                                    $stylistNames[] = $stylistName;
                                                    $appointmentCounts[] = $appointmentCount;
                                                    
                                                    // Generate a color based on performance
                                                    if ($appointmentCount == $highestAppointmentCount) {
                                                        $backgroundColors[] = '#ff5757'; // Top performer
                                                    } elseif ($appointmentCount > $averageAppointments) {
                                                        $backgroundColors[] = '#cd4949'; // Above average
                                                    } else {
                                                        $backgroundColors[] = '#9d3535'; // Below average
                                                    }

                                                    // Display the stylist and appointment count with recommendation
                                                    echo "
                                                    <tr class='$rowClass'>
                                                        <td>$count</td>
                                                        <td>$stylistName</td>
                                                        <td>$appointmentCount</td>
                                                        <td>$percentageOfTotal%</td>
                                                        <td>$performance</td>
                                                        <td>$recommendation</td>
                                                    </tr>
                                                    ";
                                                    $count++; // Increment the counter for the next stylist
                                                }
                                            } else {
                                                // If no results found
                                                echo "<tr><td colspan='6'>No accepted appointments found.</td></tr>";
                                            }
                                        } else {
                                            // If the query failed, show error
                                            echo "<tr><td colspan='6'>Error: " . mysqli_error($con) . "</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Performance Insights Section -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fa fa-lightbulb-o"></i> Performance Insights</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                // Get top performer name
                                $topPerformer = (isset($stylistNames[0])) ? $stylistNames[0] : 'None';
                                
                                // Calculate workload distribution (if we have data)
                                $workloadDistribution = 'Balanced';
                                if (count($appointmentCounts) > 1) {
                                    $maxCount = max($appointmentCounts);
                                    $minCount = min($appointmentCounts);
                                    
                                    if ($maxCount > $minCount * 2) {
                                        $workloadDistribution = 'Highly Uneven';
                                    } elseif ($maxCount > $minCount * 1.5) {
                                        $workloadDistribution = 'Somewhat Uneven';
                                    }
                                }
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Top Performer</div>
                                            <div class="panel-body">
                                                <h4><?php echo $topPerformer; ?></h4>
                                                <p>Consider providing additional incentives to maintain performance.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Workload Distribution</div>
                                            <div class="panel-body">
                                                <h4><?php echo $workloadDistribution; ?></h4>
                                                <p>
                                                <?php 
                                                if ($workloadDistribution == 'Highly Uneven') {
                                                    echo "Consider redistributing appointments more evenly among stylists.";
                                                } elseif ($workloadDistribution == 'Somewhat Uneven') {
                                                    echo "Some rebalancing of workload might be beneficial.";
                                                } else {
                                                    echo "Current workload distribution appears to be well-balanced.";
                                                }
                                                ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Recommendation</div>
                                            <div class="panel-body">
                                                <h4>Performance-Based Incentives</h4>
                                                <p>Consider implementing a tiered bonus system based on appointment counts.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include_once('includes/footer.php'); ?>
    </div>
    
    <!-- Chart.js Implementation for Stylist Data -->
    <script>
        // Stylist Appointment Chart
        var ctx = document.getElementById('stylistChart').getContext('2d');
        var stylistChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($stylistNames); ?>,
                datasets: [{
                    label: 'Appointment Count',
                    data: <?php echo json_encode($appointmentCounts); ?>,
                    backgroundColor: <?php echo json_encode($backgroundColors); ?>,
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
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var value = dataset.data[tooltipItem.index];
                            var total = dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = Math.round((value / total * 100) * 10) / 10;
                            return value + ' appointments (' + percentage + '% of total)';
                        }
                    }
                }
            }
        });
    </script>
    
    <!--scrolling js-->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <!--//scrolling js-->
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>
</body>
</html>

<?php } ?>