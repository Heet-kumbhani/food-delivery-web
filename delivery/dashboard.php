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
    $sql = "SELECT u.*, o.*, (SELECT r.status FROM remark r WHERE r.o_id = o.o_id ORDER BY r.remarkDate DESC LIMIT 1) AS status
    FROM users u
    INNER JOIN users_orders o ON u.u_id = o.u_id
    WHERE o.order_status = 'accepted'
    ORDER BY o.date DESC";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    $delivery_boy_name = $row['name'];
}

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

if (isset($_GET['toggle_delivery_boy'])) {
    $id = $_GET['toggle_delivery_boy'];

    // Fetch the current value of 'is_delivery_boy' for the specific user
    $check_status_sql = "SELECT is_delivery_boy FROM delivery_boys WHERE id = '$id'";
    $result = mysqli_query($db, $check_status_sql);
    $row = mysqli_fetch_assoc($result);
    $current_status = $row['is_delivery_boy'];

    // Toggle the 'is_delivery_boy' field
    $new_status = ($current_status == 'inactive') ? 'active' : 'inactive';

    // Update the status in the database
    $update_status_sql = "UPDATE delivery_boys SET is_delivery_boy = '$new_status' WHERE id = '$id'";

    if (mysqli_query($db, $update_status_sql)) {
        echo "<script>alert('Delivery boy status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating delivery boy status');</script>";
    }
}

if (isset($_GET['accept_order'])) {
    $order_id = $_GET['accept_order'];
    $delivery_boy_id = $_COOKIE['delivery_id'];
    $remark = "accepted";

    // Add a remark
    // $remark_sql = "INSERT INTO remark (o_id, status, remarkDate) VALUES ('$order_id', '$remark', NOW())";
    // mysqli_query($db, $remark_sql);

    // Update order status and assign delivery boy
    $update_sql = "UPDATE users_orders SET order_status = 'accepted', d_boy = '$delivery_boy_id' WHERE o_id = '$order_id'";
    mysqli_query($db, $update_sql);

    echo "<script>alert('Order Accepted!'); window.location='dashboard.php';</script>";
}

// if (empty($rows['d_boy']) || $rows['d_boy'] == $delivery_boy_id) {
//     echo '<a href="dashboard.php?accept_order=' . $rows['o_id'] . '" class="btn btn-success m-1" onclick="return confirm(\'Accept this order?\')">Accept</a>';
// }

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Admin Dashboard Template</title>
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
                                <li><a href="All_orders.php">All_orders</a></li>
                            </ul>
                        </li>
                        <!-- <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-shopping-cart"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_orders.php">All Orders</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Page wrapper -->
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
            <th>Order Date</th>
            <th>Status</th>
            <th>Action</th>
            <th>Accept the order</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT u.*, o.*, 
        (SELECT r.status 
         FROM remark r 
         WHERE r.o_id = o.o_id 
         ORDER BY r.remarkDate DESC 
         LIMIT 1) AS status
 FROM users u
 INNER JOIN users_orders o ON u.u_id = o.u_id
 WHERE o.order_status = 'accepted' 
   AND (o.d_boy IS NULL OR o.d_boy = '$delivery_boy_id')
 ORDER BY o.date DESC";


$query = mysqli_query($db, $sql);

if (mysqli_num_rows($query) == 0) {
    echo '<tr><td colspan="8"><center>No Orders Found!</center></td></tr>';
} else {
    while ($rows = mysqli_fetch_array($query)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($rows['title']) . '</td>';
        echo '<td>' . htmlspecialchars($rows['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($rows['price']) . '</td>';
        echo '<td>' . htmlspecialchars($rows['address'] ?? 'No Address Provided') . '</td>';
        echo '<td>' . htmlspecialchars($rows['date']) . '</td>';

        // Handle the order status
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
                echo '<td><button class="btn btn-secondary">Status Unknown</button></td>';
                break;
        }

        // Action buttons
        echo '<td>';
        echo '<a href="view_order.php?user_upd=' . $rows['o_id'] . '" class="btn btn-info m-l-5"><i class="ti-settings"></i></a>';
        echo '</td>';

        // Accept button
        echo '<td>';
        if (empty($rows['d_boy'])) {
            echo '<a href="dashboard.php?accept_order=' . $rows['o_id'] . '" class="btn btn-success m-1" onclick="return confirm(\'Accept this order?\')">Accept</a>';
        } else {
            echo '<span class="badge badge-info">Already Assigned</span>';
        }        
        echo '</td>';
        echo '</tr>';
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

    <!-- Scripts -->
    <script src="../admin/js/lib/jquery/jquery.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/js/jquery.slimscroll.js"></script>
    <script src="../admin/js/sidebarmenu.js"></script>
    <script src="../admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../admin/js/custom.min.js"></script>
    <script src="../admin/js/lib/datatables/datatables.min.js"></script>
    <script src="../admin/js/lib/datatables/datatables-init.js"></script>
</body>
</html>