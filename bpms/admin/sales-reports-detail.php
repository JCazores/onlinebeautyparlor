<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
} else {
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
    height: 300px;
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
  }
  .sales-summary {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }
  .summary-card {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    flex: 1;
    margin: 0 10px 10px 0;
    min-width: 200px;
    text-align: center;
    transition: all 0.3s ease;
  }
  .summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  .card-value {
    font-size: 24px;
    font-weight: bold;
    color: #ff5757;
  }
  .card-label {
    color: #777;
    font-size: 14px;
  }
  .table th {
    background-color: #f5f5f5;
  }
  .btn-export {
    margin-right: 10px;
    margin-bottom: 10px;
  }
  .date-range {
    font-weight: bold;
    margin-bottom: 15px;
    background: #f5f5f5;
    padding: 10px;
    border-radius: 4px;
    display: inline-block;
  }
  .pagination {
    margin-top: 20px;
  }
  .top-services, .top-customers {
    margin-top: 30px;
  }
  .service-item, .customer-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #eee;
  }
  .service-name, .customer-name {
    font-weight: bold;
  }
  .service-count, .service-revenue, .customer-count, .customer-revenue {
    color: #ff5757;
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
        <div class="tables">
          <h3 class="title1">Sales Reports</h3>
          
          <?php
          $fdate=$_POST['fromdate'];
          $tdate=$_POST['todate'];
          $rtype=$_POST['requesttype'];
          $format=$_POST['format'];
          
          // Format dates for display
          $fromDateFormatted = date("j M Y", strtotime($fdate));
          $toDateFormatted = date("j M Y", strtotime($tdate));
          ?>
          
          <div class="date-range">
            Report Period: <?php echo $fromDateFormatted; ?> to <?php echo $toDateFormatted; ?>
            <?php 
              if($rtype == 'mtwise') {
                echo " (Month-wise Analysis)";
              } else if($rtype == 'yrwise') {
                echo " (Year-wise Analysis)";
              } else {
                echo " (Day-wise Analysis)";
              }
            ?>
          </div>
          
          <div class="export-buttons">
            <a href="export-report.php?from=<?php echo $fdate; ?>&to=<?php echo $tdate; ?>&type=<?php echo $rtype; ?>&format=pdf" class="btn btn-default btn-export">
              <i class="fa fa-file-pdf-o"></i> Export as PDF
            </a>
            <a href="export-report.php?from=<?php echo $fdate; ?>&to=<?php echo $tdate; ?>&type=<?php echo $rtype; ?>&format=csv" class="btn btn-default btn-export">
              <i class="fa fa-file-excel-o"></i> Export as CSV
            </a>
            <a href="export-report.php?from=<?php echo $fdate; ?>&to=<?php echo $tdate; ?>&type=<?php echo $rtype; ?>&format=print" class="btn btn-default btn-export">
              <i class="fa fa-print"></i> Print Report
            </a>
          </div>
          
          <!-- Summary Cards -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>Sales Summary</h4>
            </div>
            <div class="panel-body">
              <div class="sales-summary">
                <?php
                  // Total sales in date range
                  $totalSales = mysqli_query($con, "SELECT SUM(tblservices.Cost) as total 
                                                FROM tblinvoice 
                                                JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                                                WHERE date(PostingDate) BETWEEN '$fdate' AND '$tdate'");
                  $salesData = mysqli_fetch_assoc($totalSales);
                  
                  // Total invoices in date range
                  $totalInvoices = mysqli_query($con, "SELECT COUNT(DISTINCT BillingId) as total 
                                                    FROM tblinvoice 
                                                    WHERE date(PostingDate) BETWEEN '$fdate' AND '$tdate'");
                  $invoiceData = mysqli_fetch_assoc($totalInvoices);
                  
                  // Average invoice value
                  $avgValue = 0;
                  if($invoiceData['total'] > 0) {
                    $avgValue = $salesData['total'] / $invoiceData['total'];
                  }
                  
                  // Total unique customers
                  $uniqueCustomers = mysqli_query($con, "SELECT COUNT(DISTINCT Userid) as total 
                                                      FROM tblinvoice 
                                                      WHERE date(PostingDate) BETWEEN '$fdate' AND '$tdate'");
                  $customerData = mysqli_fetch_assoc($uniqueCustomers);
                ?>
                
                <div class="summary-card">
                  <div class="card-value">₱<?php echo number_format($salesData['total'], 2); ?></div>
                  <div class="card-label">Total Revenue</div>
                </div>
                
                <div class="summary-card">
                  <div class="card-value"><?php echo $invoiceData['total']; ?></div>
                  <div class="card-label">Total Invoices</div>
                </div>
                
                <div class="summary-card">
                  <div class="card-value">₱<?php echo number_format($avgValue, 2); ?></div>
                  <div class="card-label">Average Invoice Value</div>
                </div>
                
                <div class="summary-card">
                  <div class="card-value"><?php echo $customerData['total']; ?></div>
                  <div class="card-label">Unique Customers</div>
                </div>
              </div>
              
              <!-- Chart -->
              <div class="chart-container">
                <canvas id="salesChart" style="width:100%; height:300px;"></canvas>
                <?php if(empty($labels)) { ?>
                <div class="text-center" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
                  <p class="text-muted">No data available for the selected period</p>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          
          <!-- Sales Data Table -->
          <div class="table-responsive bs-example widget-shadow">
            <h4>Sales Detail:</h4>
            <?php
            // Prepare query based on report type
            if($rtype=='mtwise'){
              $query = "SELECT MONTH(PostingDate) as month, YEAR(PostingDate) as year, 
                        COUNT(DISTINCT BillingId) as invoices,
                        SUM(tblservices.Cost) as revenue
                        FROM tblinvoice
                        JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                        WHERE date(PostingDate) BETWEEN '$fdate' AND '$tdate'
                        GROUP BY YEAR(PostingDate), MONTH(PostingDate)
                        ORDER BY YEAR(PostingDate), MONTH(PostingDate)";
              
              $periodLabel = "Month";
            } 
            else if($rtype=='yrwise'){
              $query = "SELECT YEAR(PostingDate) as year, 
                        COUNT(DISTINCT BillingId) as invoices,
                        SUM(tblservices.Cost) as revenue
                        FROM tblinvoice
                        JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                        WHERE date(PostingDate) BETWEEN '$fdate' AND '$tdate' 
                        GROUP BY YEAR(PostingDate)
                        ORDER BY YEAR(PostingDate)";
              
              $periodLabel = "Year";
            }
            else {
              $query = "SELECT DATE(PostingDate) as date, 
                        COUNT(DISTINCT BillingId) as invoices,
                        SUM(tblservices.Cost) as revenue
                        FROM tblinvoice
                        JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                        WHERE date(PostingDate) BETWEEN '$fdate' AND '$tdate'
                        GROUP BY DATE(PostingDate)
                        ORDER BY DATE(PostingDate)";
              
              $periodLabel = "Date";
            }
            
            $result = mysqli_query($con, $query);
            
            if(mysqli_num_rows($result) > 0) {
              // Prepare chart data
              $labels = array();
              $revenues = array();
              $invoiceCounts = array();
              
              // Output data table
              echo '<table class="table table-bordered">';
              echo '<thead>';
              echo '<tr>';
              echo '<th>' . $periodLabel . '</th>';
              echo '<th>Invoices</th>';
              echo '<th>Revenue</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              
              $totalRevenue = 0;
              $totalInvoices = 0;
              
              while($row = mysqli_fetch_assoc($result)) {
                // Format period label based on report type
                if($rtype=='mtwise') {
                  $period = date("F Y", mktime(0, 0, 0, $row['month'], 1, $row['year']));
                  $labels[] = date("M Y", mktime(0, 0, 0, $row['month'], 1, $row['year']));
                } 
                else if($rtype=='yrwise') {
                  $period = $row['year'];
                  $labels[] = $row['year'];
                }
                else {
                  $period = date("d M Y", strtotime($row['date']));
                  $labels[] = date("d M", strtotime($row['date']));
                }
                
                $revenues[] = floatval($row['revenue']);
                $invoiceCounts[] = intval($row['invoices']);
                
                echo '<tr>';
                echo '<td>' . $period . '</td>';
                echo '<td>' . $row['invoices'] . '</td>';
                echo '<td>₱' . number_format($row['revenue'], 2) . '</td>';
                echo '</tr>';
                
                $totalRevenue += $row['revenue'];
                $totalInvoices += $row['invoices'];
              }
              
              echo '<tr class="info">';
              echo '<td><strong>Total</strong></td>';
              echo '<td><strong>' . $totalInvoices . '</strong></td>';
              echo '<td><strong>₱' . number_format($totalRevenue, 2) . '</strong></td>';
              echo '</tr>';
              
              echo '</tbody>';
              echo '</table>';
              
              // Convert data for JavaScript
              $chartLabels = json_encode($labels);
              $chartRevenues = json_encode($revenues);
              $chartInvoices = json_encode($invoiceCounts);
            }
            else {
              echo '<div class="alert alert-info">No sales data found for the selected date range.</div>';
              
              // Empty arrays for chart
              $chartLabels = '[]';
              $chartRevenues = '[]';
              $chartInvoices = '[]';
            }
            ?>
          </div>
          
          <!-- Additional Analysis -->
          <div class="row">
            <!-- Top Services -->
            <div class="col-md-6">
              <div class="panel panel-default top-services">
                <div class="panel-heading">
                  <h4>Top Services</h4>
                </div>
                <div class="panel-body">
                  <?php
                  $topServices = mysqli_query($con, "SELECT tblservices.ServiceName, 
                                                  COUNT(*) as count,
                                                  SUM(tblservices.Cost) as revenue
                                                  FROM tblinvoice
                                                  JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                                                  WHERE date(PostingDate) BETWEEN '$fdate' AND '$tdate'
                                                  GROUP BY tblinvoice.ServiceId
                                                  ORDER BY count DESC
                                                  LIMIT 5");
                  
                  if(mysqli_num_rows($topServices) > 0) {
                    while($row = mysqli_fetch_assoc($topServices)) {
                      echo '<div class="service-item">';
                      echo '<div class="service-name">' . $row['ServiceName'] . '</div>';
                      echo '<div>';
                      echo '<span class="service-count">' . $row['count'] . ' services</span> | ';
                      echo '<span class="service-revenue">₱' . number_format($row['revenue'], 2) . '</span>';
                      echo '</div>';
                      echo '</div>';
                    }
                  } else {
                    echo '<div class="alert alert-info">No service data available.</div>';
                  }
                  ?>
                </div>
              </div>
            </div>
            
            <!-- Top Customers -->
            <div class="col-md-6">
              <div class="panel panel-default top-customers">
                <div class="panel-heading">
                  <h4>Top Customers</h4>
                </div>
                <div class="panel-body">
                  <?php
                  $topCustomers = mysqli_query($con, "SELECT tbluser.Name, 
                                                  COUNT(DISTINCT tblinvoice.BillingId) as count,
                                                  SUM(tblservices.Cost) as revenue
                                                  FROM tblinvoice
                                                  JOIN tblservices ON tblinvoice.ServiceId = tblservices.ID
                                                  JOIN tbluser ON tblinvoice.Userid = tbluser.ID
                                                  WHERE date(tblinvoice.PostingDate) BETWEEN '$fdate' AND '$tdate'
                                                  GROUP BY tblinvoice.Userid
                                                  ORDER BY revenue DESC
                                                  LIMIT 5");
                  
                  if(mysqli_num_rows($topCustomers) > 0) {
                    while($row = mysqli_fetch_assoc($topCustomers)) {
                      echo '<div class="customer-item">';
                      echo '<div class="customer-name">' . $row['Name'] . '</div>';
                      echo '<div>';
                      echo '<span class="customer-count">' . $row['count'] . ' visits</span> | ';
                      echo '<span class="customer-revenue">₱' . number_format($row['revenue'], 2) . '</span>';
                      echo '</div>';
                      echo '</div>';
                    }
                  } else {
                    echo '<div class="alert alert-info">No customer data available.</div>';
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Initialize Chart -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Chart initialization
      var ctx = document.getElementById('salesChart').getContext('2d');
      
      // Set default height for canvas element
      ctx.canvas.height = 300;
      
      // Check if we have data
      var labels = <?php echo $chartLabels; ?>;
      var revenues = <?php echo $chartRevenues; ?>;
      var invoiceCounts = <?php echo $chartInvoices; ?>;
      
      // Fix for empty chart - add default placeholder if no data
      if (labels.length === 0) {
        labels = ["No Data"];
        revenues = [0];
        invoiceCounts = [0];
      }
      
      var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Revenue (₱)',
              data: revenues,
              backgroundColor: 'rgba(255, 87, 87, 0.5)',
              borderColor: 'rgba(255, 87, 87, 1)',
              borderWidth: 1,
              yAxisID: 'y'
            },
            {
              label: 'Invoices',
              data: invoiceCounts,
              backgroundColor: 'rgba(54, 162, 235, 0.5)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
              type: 'line',
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'Sales Performance'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  var label = context.dataset.label || '';
                  var value = context.raw;
                  if (context.datasetIndex === 0) {
                    return label + ': ₱' + value.toLocaleString();
                  } else {
                    return label + ': ' + value;
                  }
                }
              }
            }
          },
          scales: {
            y: {
              type: 'linear',
              display: true,
              position: 'left',
              title: {
                display: true,
                text: 'Revenue'
              },
              ticks: {
                callback: function(value) {
                  return '₱' + value.toLocaleString();
                }
              }
            },
            y1: {
              type: 'linear',
              display: true,
              position: 'right',
              grid: {
                drawOnChartArea: false
              },
              title: {
                display: true,
                text: 'Number of Invoices'
              }
            }
          }
        }
      });
    });
    </script>
    <!--footer-->
    <?php include_once('includes/footer.php');?>
    <!--//footer-->
  </div>
  
  <!-- Classie -->
  <script src="js/classie.js"></script>
  <script>
    var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
      showLeftPush = document.getElementById( 'showLeftPush' ),
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
<?php }  ?>