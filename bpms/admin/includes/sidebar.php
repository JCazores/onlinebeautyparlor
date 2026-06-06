<style>
    /* Adjust sidebar background and general size */
    .sidebar {
        background-color: #ff5757; /* Change the sidebar background color */
        padding: 0;
    }

    /* Adjust the overall size and padding of each navigation item */
    .sidebar .nav > li > a {
        font-size: 16px;  /* Set font size */
        padding: 5px 20px;  /* Adjust the padding (top-bottom, left-right) */
        color: #fff;  /* Set text color */
        margin: 6px 0;  /* Adjust the spacing between items */
        display: flex;
        align-items: center;
    }

    /* Hover effect to change the background color of the menu item */
    .sidebar .nav > li > a:hover {
        background-color: #ff3333;  /* Lighter background on hover */
        color: #fff;  /* Keep text color white */
    }

    /* Adjust the size of the icons */
    .sidebar .nav .fa {
        font-size: 18px;  /* Set the size of the icons */
        margin-right: 30px;  /* Add spacing between the icon and text */
    }

    /* Adjust sub-menu items (second-level items) */
    .sidebar .nav .nav-second-level li a {
        font-size: 14px;  /* Slightly smaller font size for sub-menu */
        padding: 8px 15px;  /* Adjust padding for sub-menu items */
        color: #fff;  /* Keep text color white for sub-menu */
    }

    /* Hover effect for sub-menu items */
    .sidebar .nav .nav-second-level li a:hover {
        background-color: #ff3333;  /* Lighter background on hover for sub-menu */
    }

    /* Adjust the collapse behavior for sub-menu items */
    .sidebar .nav .nav-second-level.collapse {
        background-color: #ff5757;  /* Background color of collapsed sub-menu */
    }

    /* Optional: Adjust width of the sidebar */
    .sidebar {
        width: 200px;  /* Adjust sidebar width */
    }

    /* Adjust the collapsible submenu icon */
    .sidebar .fa.arrow {
        font-size: 18px; /* Adjust the size of the arrow icon */
        
    }

    /* Add a hover effect for the second-level items (submenu) */
    .sidebar .nav .nav-second-level {
        margin-left: 40px;  /* Indentation for second-level items */
    }
</style>

  <div class=" sidebar" role="navigation">
            <div class="navbar-collapse">
        <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1" style="background-color: #ff5757;">
          <ul class="nav" id="side-menu">
            <li>
              <a href="dashboard.php"><i class="fa fa-home nav_icon"></i>Dashboard</a>
            </li>
            <li>
              <a href="add-services.php"><i class="fa fa-cogs nav_icon"></i>Services<span class="fa arrow"></span> </a>
              <ul class="nav nav-second-level collapse">
                <li>
                  <a href="add-services.php">Add Services</a>
                </li>
                <li>
                  <a href="manage-services.php">Manage Services</a>
                </li>
              </ul>
              <!-- /nav-second-level -->
            </li>
            <li>
              <a href="add-stylist.php"><i class="fa fa-cogs nav_icon"></i>Stylist<span class="fa arrow"></span> </a>
              <ul class="nav nav-second-level collapse">
              <li>
                  <a href="stylist.php">Stylist</a>
                </li>
                <li>
                  <a href="add-stylist.php">Add Stylist</a>
                </li>
                <li>
                  <a href="manage-stylist.php">Manage Stylist</a>
                </li>
              </ul>
              <!-- /nav-second-level -->
            </li>
            <li class="">
              <a href="about-us.php"><i class="fa fa-book nav_icon"></i>Pages <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse">
                <li>
                  <a href="about-us.php">About Us</a>
                </li>
                <li>
                  <a href="contact-us.php">Contact Us</a>
                </li>
              </ul>
              <!-- /nav-second-level -->
            </li>
          
            <li>
              <a href="all-appointment.php"><i class="fa fa-check-square-o nav_icon"></i>Appointment<span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse">
                <li>
                  <a href="all-appointment.php">All Appointment</a>
                </li>
                <li>
                  <a href="new-appointment.php">New Appointment</a>
                </li>
                <li>
                  <a href="accepted-appointment.php">Accepted Appointment</a>
                </li>
                <li>
                  <a href="rejected-appointment.php">Rejected Appointment</a>
                </li>
              </ul>
              <!-- //nav-second-level -->
            </li>
           
            <li>
              <a href="manage-cancellations.php" class="chart-nav"><i class="fa fa-ban nav_icon"></i>Cancellations</a>
            </li>
           <!--<li>
              <a href="add-customer.php" class="chart-nav"><i class="fa fa-user nav_icon"></i>Add Customer</a>
            </li>-->
             <li>
              <a href="customer-list.php" class="chart-nav"><i class="fa fa-users nav_icon"></i>Customer List</a>
            </li>
              <li>
              <a href="#"><i class="fa fa-check-square-o nav_icon"></i>Reports<span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse">
                 <li><a href="bwdates-reports-ds.php"> B/w dates</a></li>
                   
                    <li><a href="sales-reports.php">Sales Reports</a></li>
              </ul>
              
            </li>

            <li>
              <a href="invoices.php" class="chart-nav"><i class="fa fa-file-text-o nav_icon"></i>Invoices</a>
            </li>
            <li>
              <a href="search-appointment.php" class="chart-nav"><i class="fa fa-search nav_icon"></i>Search Appointment</a>
            </li>
            <li>
              <a href="search-invoices.php" class="chart-nav"><i class="fa fa-search nav_icon"></i>Search Invoice</a>
            </li>
          

          </ul>
          <div class="clearfix"> </div>
          <!-- //sidebar-collapse -->
        </nav>
      </div>
    </div>