<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Secure the input to avoid SQL injection
if (isset($_GET['user_upd'])) {
    $order_id = mysqli_real_escape_string($db, $_GET['user_upd']);
    
    // Prepared statement to fetch user and order details
    $sql = "SELECT users.*, users_orders.*, r.status 
        FROM users 
        INNER JOIN users_orders ON users.u_id = users_orders.u_id 
        LEFT JOIN remark r ON users_orders.o_id = r.o_id 
        WHERE users_orders.o_id = ? 
        ORDER BY r.remarkDate DESC 
        LIMIT 1";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, 'i', $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_array($result);
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin Dashboard">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Dashboard - Order Details</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        var popUpWin = 0;
        function popUpWindow(URLStr, left, top, width, height) {
            if(popUpWin) {
                if(!popUpWin.closed) popUpWin.close();
            }
            popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+600+',height='+600+',left='+left+',top='+top+',screenX='+left+',screenY='+top+'');
        }
    </script>
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
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
                                    $sql_total_price = "SELECT SUM(price * quantity) AS total_price FROM users_orders"; 
                                    $result_total_price = mysqli_query($db, $sql_total_price);
                                    $price_row = mysqli_fetch_assoc($result_total_price);
                                    $total_price = $price_row['total_price'];
                                    $total_earnings = $total_price * 0.2;
                                    echo number_format($total_earnings, 2); 
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

        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="All_orders.php">All_orders</a></li>
                            </ul>
                        </li>
                        <!-- <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allusers.php">All Users</a></li>
                            </ul>
                        </li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-shopping-cart"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_orders.php">All Orders</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Order Details</h3>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">View User Orders</h4>
                                <div class="table-responsive m-t-20">
                                    <table id="myTable" class="table table-bordered table-striped">
                                    <tbody>
    <tr>
        <td><strong>Username:</strong></td>
        <td><center><?php echo htmlspecialchars($rows['username']); ?></center></td>
        <td><center>
            <a href="javascript:void(0);" onClick="popUpWindow('order_update.php?form_id=<?php echo urlencode($rows['o_id']); ?>');" title="Update order">
                <button type="button" class="btn btn-primary">Take Action</button>
            </a>
        </center></td>
    </tr>
    <tr>
        <td><strong>Title:</strong></td>
        <td><center><?php echo htmlspecialchars($rows['title']); ?></center></td>
    </tr>
    <tr>
        <td><strong>Quantity:</strong></td>
        <td><center><?php echo htmlspecialchars($rows['quantity']); ?></center></td>
    </tr>
    <tr>
        <td><strong>Price:</strong></td>
        <td><center>₹<?php echo htmlspecialchars($rows['price']); ?></center></td>
    </tr>
    <tr>
        <td><strong>Address:</strong></td>
        <td><center><?php echo htmlspecialchars($rows['address']); ?></center></td>
    </tr>
    <tr>
        <td><strong>Order Date:</strong></td>
        <td><center><?php echo htmlspecialchars($rows['date']); ?></center></td>
    </tr>
    <tr>
        <td><strong>Status:</strong></td>
        <td>
            <?php
            // Get the order status from the 'remark' table
            $status = isset($rows['status']) ? $rows['status'] : 'Status Unknown';

            if (empty($status) || $status == "NULL") {
                echo '<center><button type="button" class="btn btn-info"><span class="fa fa-bars" aria-hidden="true"> Dispatch</button></center>';
            } elseif ($status == "in process") {
                echo '<center><button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span> On the way!</button></center>';
            } elseif ($status == "closed") {
                echo '<center><button type="button" class="btn btn-success"><span class="fa fa-check-circle" aria-hidden="true"> Delivered</button></center>';
            } elseif ($status == "rejected") {
                // echo '<center><button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelled</button></center>';
            } else {
                echo '<center><button type="button" class="btn btn-secondary">Status Unknown</button></center>';
            }
            ?>
        </td>
    </tr>
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

    <script src="../admin/js/lib/jquery/jquery.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/js/jquery.slimscroll.js"></script>
    <script src="../admin/js/sidebarmenu.js"></script>
    <script src="../admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../admin/js/custom.min.js"></script>
    <script src="../admin/js/lib/datatables/datatables.min.js"></script>
    <script src="../admin/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
</body>
</html>
