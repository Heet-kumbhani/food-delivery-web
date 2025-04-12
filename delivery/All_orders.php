<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Assuming you store the logged-in delivery boy's ID in the session
$delivery_boy_id = $_SESSION['delivery_id']; 

// Fetch the delivery boy's name from the database
$delivery_boy_name = "";
if (isset($delivery_boy_id)) {
    $sql = "SELECT name FROM delivery_boys WHERE id = '$delivery_boy_id'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    $delivery_boy_name = $row['name'];
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>All Orders</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>

    <!-- Main wrapper -->
    <div id="main-wrapper">
        <!-- Header -->
        <div class="header">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <!-- Removed the logo and displayed the delivery boy's name -->
                    <a class="navbar-brand" href="dashboard.php">
                        <b><?php echo htmlspecialchars($delivery_boy_name); ?></b>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                    </ul>
                    <li class="nav-item">
    <a class="nav-link text-muted" href="javascript:void(0)">
        <strong>Total Earnings: </strong> 
        <span class="badge badge-success">
            <?php 
            $delivery_boy_id = $_SESSION['delivery_id'] ?? $_COOKIE['delivery_id'] ?? null;

            if ($delivery_boy_id) {
                $sql_total_price = "SELECT SUM(price * quantity) AS total_price 
                                    FROM users_orders 
                                    WHERE d_boy = '$delivery_boy_id' AND order_status = 'accepted'";
                $result_total_price = mysqli_query($db, $sql_total_price);
                $price_row = mysqli_fetch_assoc($result_total_price);
                $total_price = $price_row['total_price'] ?? 0;
                $total_earnings = $total_price * 0.2;
                echo number_format($total_earnings, 2);
            } else {
                echo "0.00";
            }
            ?> INR
        </span>
    </a>
</li>

                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="../admin/images/manager.png" alt="user" class="profile-pic bg-dark p-1" />
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

        <!-- Left Sidebar -->
        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label"></li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="All_orders.php">All Orders</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">All Orders</h3>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Orders List</h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer Name</th>
                                                <th>Dish Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Address</th>
                                                <th>Order Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Get delivery_id from session or cookie
$delivery_boy_id = $_SESSION['delivery_id'] ?? $_COOKIE['delivery_id'] ?? null;

if ($delivery_boy_id) {
    $sql = "SELECT u.*, o.*, 
            (SELECT r.status FROM remark r WHERE r.o_id = o.o_id ORDER BY r.remarkDate DESC LIMIT 1) AS status
            FROM users u
            INNER JOIN users_orders o ON u.u_id = o.u_id
            WHERE o.d_boy = '$delivery_boy_id'
            ORDER BY o.date DESC";
    
    $query = mysqli_query($db, $sql);

    if (!mysqli_num_rows($query) > 0) {
        echo '<tr><td colspan="9"><center>No Orders Assigned to You!</center></td></tr>';
    } else {
        while ($rows = mysqli_fetch_array($query)) {
            echo '<tr>';
            echo '<td>' . $rows['o_id'] . '</td>';
            echo '<td>' . $rows['username'] . '</td>';
            echo '<td>' . $rows['title'] . '</td>';
            echo '<td>' . $rows['quantity'] . '</td>';
            echo '<td>' . $rows['price'] . '</td>';
            echo '<td>' . ($rows['address'] ?? 'No Address Provided') . '</td>';
            echo '<td>' . $rows['date'] . '</td>';

            $status = $rows['status'] ?? 'Status Unknown';
            switch ($status) {
                case "Ready for Delivery":
                    echo '<td><button class="btn btn-primary">Ready for Delivery</button></td>';
                    break;
                case "in process":
                    echo '<td><button class="btn btn-warning">On the Way!</button></td>';
                    break;
                case "closed":
                    echo '<td><button class="btn btn-success">Delivered</button></td>';
                    break;
                case "rejected":
                    echo '<td><button class="btn btn-danger">Cancelled</button></td>';
                    break;
                default:
                    echo '<td><button class="btn btn-secondary">Pending</button></td>';
                    break;
            }

            echo '<td>';
            echo '<a href="view_order.php?user_upd=' . $rows['o_id'] . '" class="btn btn-info m-l-5"><i class="ti-settings"></i></a>';
            echo '</td>';
            echo '</tr>';
        }
    }
} else {
    echo '<tr><td colspan="9"><center>No Delivery ID Found!</center></td></tr>';
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
        </div>
    </div>

    <script src="../admin/js/lib/jquery/jquery.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/js/jquery.slimscroll.js"></script>
    <script src="../admin/js/sidebarmenu.js"></script>
    <script src="../admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../admin/js/custom.min.js"></script>
</body>
</html>
