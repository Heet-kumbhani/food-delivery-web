<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Update the order status to "Ready for Delivery" when the button is clicked
if (isset($_GET['ready_for_delivery'])) {
    $order_id = $_GET['ready_for_delivery'];

    // Update the order status in the database
    $update_status_sql = "UPDATE users_orders SET status = 'Ready for Delivery' WHERE o_id = '$order_id'";
    if (mysqli_query($db, $update_status_sql)) {
        echo "<script>alert('Order status updated to Ready for Delivery!');</script>";
    } else {
        echo "<script>alert('Error updating order status');</script>";
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Admin Dashboard Template</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>

    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <b><img src="images/logo.png" alt="homepage" class="dark-logo" /></b>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/manager.png" alt="user" class="profile-pic bg-dark p-1" /></a>
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

        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label"></li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allusers.php">All Users</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-archive"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allrestraunt.php">All Restaurant</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-cutlery"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">All Menus</a></li>
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="sub_category.php">Add SubCategory</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-shopping-cart"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_orderss.php">All Orders</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Orders</h4>

                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Dish Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Order Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sql = "SELECT users.*, users_orders.* FROM users INNER JOIN users_orders ON users.u_id = users_orders.u_id";
                                                $query = mysqli_query($db, $sql);

                                                if (!mysqli_num_rows($query) > 0) {
                                                    echo '<td colspan="7"><center>No Orders Found!</center></td>';
                                                } else {
                                                    while ($rows = mysqli_fetch_array($query)) {
                                            ?>
                                                        <tr>
                                                            <td><?php echo $rows['title']; ?></td>
                                                            <td><?php echo $rows['quantity']; ?></td>
                                                            <td>$<?php echo $rows['price']; ?></td>
                                                            <td><?php echo $rows['address']; ?></td>
                                                            <?php 
                                                                $status = $rows['status'];
                                                                if (empty($status) || $status == "NULL") {
                                                            ?>
                                                                <td><button type="button" class="btn btn-info" style="font-weight: bold;">
                                                                    <a href="?ready_for_delivery=<?php echo $rows['o_id']; ?>" style="color: white;">
                                                                        Mark as Ready for Delivery
                                                                    </a>
                                                                </button></td>
                                                            <?php } elseif ($status == "Ready for Delivery") { ?>
                                                                <td><button type="button" class="btn btn-success" disabled>Ready for Delivery</button></td>
                                                            <?php } else { ?>
                                                                <td><button type="button" class="btn btn-danger" disabled>Cancelled</button></td>
                                                            <?php } ?>
                                                            <td><?php echo $rows['date']; ?></td>
                                                            <td>
                                                                <a href="delete_orders.php?order_del=<?php echo $rows['o_id']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                            <?php 
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">© 2024 All rights reserved.</footer>
        </div>
    </div>

    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
</body>
</html>
