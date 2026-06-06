<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
} else {
  // Initialize variables for chart data
  $monthlyData = array();
  $yearlyData = array();
  
  // Get current month and year data for default chart
  $currentMonth = date('m');
  $currentYear = date('Y');
  
  // Query to get current month data
  $monthQuery = mysqli_query($con, "SELECT DATE(PostingDate) as date, 
                                  COUNT(DISTINCT BillingId) as invoices,
                                  SUM(tblservices.Cost) as revenue
                                  FROM tblinvoice 
                                  JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                                  WHERE MONTH(PostingDate) = '$currentMonth' 
                                  AND YEAR(PostingDate) = '$currentYear'
                                  GROUP BY DATE(PostingDate)
                                  ORDER BY DATE(PostingDate)");
  
  // Prepare data for chart
  $dates = array();
  $revenues = array();
  $invoiceCounts = array();
  
  if(mysqli_num_rows($monthQuery) > 0) {
    while($row = mysqli_fetch_assoc($monthQuery)) {
      $dates[] = date('d M', strtotime($row['date']));
      $revenues[] = floatval($row['revenue']);
      $invoiceCounts[] = intval($row['invoices']);
    }
  }
  
  // Convert to JSON for JavaScript
  $chartDates = json_encode($dates);
  $chartRevenues = json_encode($revenues);
  $chartInvoices = json_encode($invoiceCounts);
?>
<!DOCTYPE HTML>
<html>
<head>
<title>RFBS | Sales Reports</title>

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
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->
<style>
  .chart-container {
    position: relative;
    margin: 20px 0;
    height: 400px;
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
  .report-summary {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  .summary-item {
    display: inline-block;
    width: 32%;
    text-align: center;
    padding: 10px;
  }
  .summary-value {
    font-size: 24px;
    font-weight: bold;
    color: #ff5757;
  }
  .summary-label {
    font-size: 14px;
    color: #777;
  }
  .date-selector {
    margin-bottom: 20px;
  }
  .btn-default {
    background-color: #ff5757;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    transition: background-color 0.3s;
  }
  .btn-default:hover {
    background-color: #e04545;
    color: white;
  }
  #reportType {
    margin-bottom: 15px;
  }
  .radio-group {
    margin: 10px 0;
  }
  .form-control1 {
    border-radius: 4px;
    border: 1px solid #ddd;
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
        <div class="forms">
          <h3 class="title1">Sales Reports</h3>
          
          <!-- Dashboard Summary Cards -->
          <div class="report-summary">
            <?php
              // Get total sales for current month
              $monthlySales = mysqli_query($con, "SELECT SUM(tblservices.Cost) as total FROM tblinvoice 
                                                JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                                                WHERE MONTH(PostingDate) = '$currentMonth' 
                                                AND YEAR(PostingDate) = '$currentYear'");
              $monthlySalesData = mysqli_fetch_assoc($monthlySales);
              
              // Get total sales for current year
              $yearlySales = mysqli_query($con, "SELECT SUM(tblservices.Cost) as total FROM tblinvoice 
                                              JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                                              WHERE YEAR(PostingDate) = '$currentYear'");
              $yearlySalesData = mysqli_fetch_assoc($yearlySales);
              
              // Get total invoices count
              $invoiceCount = mysqli_query($con, "SELECT COUNT(DISTINCT BillingId) as total FROM tblinvoice");
              $invoiceCountData = mysqli_fetch_assoc($invoiceCount);
            ?>
            <div class="summary-item">
              <div class="summary-value">₱<?php echo number_format($monthlySalesData['total'], 2); ?></div>
              <div class="summary-label">Current Month Sales</div>
            </div>
            <div class="summary-item">
              <div class="summary-value">₱<?php echo number_format($yearlySalesData['total'], 2); ?></div>
              <div class="summary-label">Current Year Sales</div>
            </div>
            <div class="summary-item">
              <div class="summary-value"><?php echo $invoiceCountData['total']; ?></div>
              <div class="summary-label">Total Invoices</div>
            </div>
          </div>
          
          <!-- Chart Display -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>Sales Visualization</h4>
            </div>
            <div class="panel-body">
              <div class="chart-container">
                <canvas id="salesChart"></canvas>
              </div>
            </div>
          </div>
          
          <!-- Sales Report Form -->
          <div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
            <div class="form-title">
              <h4>Generate Detailed Sales Report:</h4>
            </div>
            <div class="form-body">
              <form method="post" name="salesReport" action="sales-reports-detail.php" enctype="multipart/form-data">
                <p style="font-size:16px; color:red" align="center"> <?php if($msg){ echo $msg; } ?> </p>

                <div class="form-group"> 
                  <label for="fromdate">From Date</label> 
                  <input type="date" class="form-control1" name="fromdate" id="fromdate" required='true'> 
                </div> 
                
                <div class="form-group"> 
                  <label for="todate">To Date</label>
                  <input type="date" class="form-control1" name="todate" id="todate" required='true'> 
                </div>
                
                <div class="form-group" id="reportType"> 
                  <label>Report Type</label>
                  <div class="radio-group">
                    <label class="radio-inline">
                      <input type="radio" name="requesttype" value="mtwise" checked="true"> Month wise
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="requesttype" value="yrwise"> Year wise
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="requesttype" value="daywise"> Day wise
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="format">Export Format</label>
                  <select name="format" id="format" class="form-control1">
                    <option value="web">Web View</option>
                    <option value="pdf">PDF</option>
                    <option value="csv">CSV</option>
                  </select>
                </div>
                
                <button type="submit" name="submit" class="btn btn-default">Generate Report</button>
              </form> 
            </div>
          </div>
        </div>
      </div>
      <?php include_once('includes/footer.php');?>
    </div>
  </div>
  
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
    
    // Chart initialization
    document.addEventListener('DOMContentLoaded', function() {
      var ctx = document.getElementById('salesChart').getContext('2d');
      
      // Parse data from PHP
      var dates = <?php echo $chartDates ? $chartDates : '[]'; ?>;
      var revenues = <?php echo $chartRevenues ? $chartRevenues : '[]'; ?>;
      var invoices = <?php echo $chartInvoices ? $chartInvoices : '[]'; ?>;
      
      var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: dates,
          datasets: [
            {
              label: 'Revenue (₱)',
              data: revenues,
              backgroundColor: 'rgba(255, 87, 87, 0.7)',
              borderColor: 'rgba(255, 87, 87, 1)',
              borderWidth: 1,
              yAxisID: 'y-axis-1'
            },
            {
              label: 'Invoices',
              data: invoices,
              type: 'line',
              fill: false,
              backgroundColor: 'rgba(54, 162, 235, 0.7)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 2,
              yAxisID: 'y-axis-2'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          title: {
            display: true,
            text: 'Current Month Sales Performance',
            fontSize: 16
          },
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                if (label.includes('Revenue')) {
                  return label + ': ₱' + Number(tooltipItem.yLabel).toFixed(2);
                } else {
                  return label + ': ' + tooltipItem.yLabel;
                }
              }
            }
          },
          scales: {
            yAxes: [
              {
                type: 'linear',
                display: true,
                position: 'left',
                id: 'y-axis-1',
                scaleLabel: {
                  display: true,
                  labelString: 'Revenue (₱)'
                },
                ticks: {
                  beginAtZero: true,
                  callback: function(value, index, values) {
                    return '₱' + value;
                  }
                }
              },
              {
                type: 'linear',
                display: true,
                position: 'right',
                id: 'y-axis-2',
                gridLines: {
                  drawOnChartArea: false
                },
                scaleLabel: {
                  display: true,
                  labelString: 'Number of Invoices'
                },
                ticks: {
                  beginAtZero: true,
                  stepSize: 1
                }
              }
            ]
          }
        }
      });
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