<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="css/helper.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  
  <!-- Add FontAwesome and MDI for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <title>Income Dashboard</title>
</head>
<body>
<div class="header">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <!-- Logo -->
        <div class="navbar-header">
            <a class="navbar-brand" href="dashboard.php">
                <b><img src="images/logo.png" alt="homepage" class="dark-logo" /></b>
            </a>
        </div>
        <!-- End Logo -->
        <div class="navbar-collapse">
            <!-- toggle and nav items -->
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
            </ul>
            <!-- User profile and search -->
            <ul class="navbar-nav my-lg-0">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                        <ul>
                            <li><div class="drop-title">Notifications</div></li>
                            <li><a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a></li>
                        </ul>
                    </div>
                </li>

                <!-- Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="images/manager.png" alt="user" class="profile-pic bg-dark p-1" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                        <ul class="dropdown-user">
                            <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div class="left-sidebar">
    <div class="scroll-sidebar">
    <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label"></li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                
                            </ul>
                        </li>
                        <li class="nav-label">Log</li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false">  <span><i class="fa fa-user f-s-20 "></i></span><span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allusers.php">All Users</a></li>
								<!-- <li><a href="add_users.php">Add Users</a></li> -->
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="allrestraunt.php">All Restaurant</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Delivery boy</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="delivery_boy.php">All delivery boy</a></li>
                            </ul>
                        </li>
                       <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="all_menu.php">All Menues</a></li>
								
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="sub_category.php">Add SubCategory</a></li>
                            </ul>
                        </li>
						 <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="all_orders.php">All Orders</a></li>
                            </ul>
                        </li>
						<li> <a class="has-arrow  " href="#" aria-expanded="false"><i aria-hidden="true">₹</i><span class="hide-menu">Income</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="income.php">Income</a></li>
                            </ul>
                        </li>
                         
                    </ul>
                </nav>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Pie Chart with medium size -->
            <canvas id="incomePieChart" width="400" height="400"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Total Earnings -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <strong>Total Earnings: </strong>
                    <span class="badge badge-success">
                        <?php 
                        $sql_total_price = "SELECT SUM(price * quantity) AS total_price FROM users_orders"; 
                        $result_total_price = mysqli_query($db, $sql_total_price);
                        $price_row = mysqli_fetch_assoc($result_total_price);
                        $total_price = $price_row['total_price'];
                        $total_earnings = $total_price * 0.2;
                        echo number_format($total_earnings, 2); 
                        ?> INR
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Cost of Restaurant and Delivery -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <strong>Total Cost of Restaurant and Delivery (40%): </strong>
                    <span class="badge badge-info">
                        <?php 
                        $total_earnings = $total_price * 0.4;
                        echo number_format($total_earnings, 2); 
                        ?> INR
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Price of Orders -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <strong>Total Price of Orders: </strong>
                    <span class="badge badge-info">
                        <?php 
                        echo number_format($total_price, 2); 
                        ?> INR
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/lib/jquery/jquery.min.js"></script>
<script src="js/lib/bootstrap/js/popper.min.js"></script>
<script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="js/jquery.slimscroll.js"></script>
<!--Menu sidebar -->
<script src="js/sidebarmenu.js"></script>
<!--stickey kit -->
<script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<!--Custom JavaScript -->
<script src="js/custom.min.js"></script>

<script>
    // Data for the Pie chart
    var ctx = document.getElementById('incomePieChart').getContext('2d');
    var incomePieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Total Earnings (20%)', 'Restaurant & Delivery Cost (40%)', 'Total Price of Orders'],
            datasets: [{
                label: 'Amount in INR',
                data: [
                    <?php echo $total_earnings; ?>, // Total earnings (20% of total price)
                    <?php echo $total_price * 0.4; ?>, // Total cost (40% of total price)
                    <?php echo $total_price; ?>  // Total price of orders
                ],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107'],
                borderColor: ['#28a745', '#17a2b8', '#ffc107'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ₹' + tooltipItem.raw.toFixed(2);
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
