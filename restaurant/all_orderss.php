<!DOCTYPE html>
<html lang="en">
<?php
// Ensure session is started
session_start();
include("../connection/connect.php");

// Check if 'res_id' cookie is set
if (!isset($_COOKIE['res_id'])) {
    die("Restaurant ID not found in cookies!");
}

// Retrieve restaurant ID from the cookie
if (!isset($_COOKIE['res_id']) || !is_numeric($_COOKIE['res_id'])) {
    die("Restaurant ID not found in cookies!");
}
$restaurant_id = intval($_COOKIE['res_id']);

// Fetch orders only for the matching restaurant ID
$querys = mysqli_query($db, "SELECT * FROM users_orders WHERE rs_id = '$restaurant_id'");

// Include the database connection

// Retrieve restaurant information from the database
$restaurant_query = "SELECT * FROM restaurant WHERE rs_id = '$restaurant_id' LIMIT 1";
$restaurant_result = mysqli_query($db, $restaurant_query);
$restaurant = mysqli_fetch_assoc($restaurant_result);

if ($restaurant) {
    // Use the session or cookie to get the restaurant's open or close status
    $status = isset($_COOKIE['open_or_close']) ? $_COOKIE['open_or_close'] : $restaurant['open_or_close'];
} else {
    $status = 'closed'; // Default if no data found
}

// SQL query to fetch user orders only for the specific restaurant
$sql = "SELECT u.*, o.*, (SELECT r.status 
 FROM remark r 
 WHERE r.o_id = o.o_id 
 ORDER BY r.remarkDate DESC 
 LIMIT 1) AS status
        FROM users u
        INNER JOIN users_orders o ON u.u_id = o.u_id
        WHERE o.rs_id = '$restaurant_id' 
        ORDER BY o.date DESC";

$query = mysqli_query($db, $sql);

$sql_total_price = "SELECT SUM(price * quantity) AS total_price FROM users_orders WHERE rs_id = '$restaurant_id'"; 
$result_total_price = mysqli_query($db, $sql_total_price);
$price_row = mysqli_fetch_assoc($result_total_price);
$total_price = $price_row['total_price'];

// Calculate 60% of the total price as the restaurant's earnings
$total_earnings_60 = $total_price * 0.6;
?>



<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Admin Dashboard</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
        $(document).ready(function() {
            $('.btn-toggle-status').on('click', function() {
                var button = $(this);
                var currentOpenClose = button.data('open_or_close');
                var restaurantId = getCookie('restaurant_id');

                var newOpenCloseStatus = currentOpenClose === 'open' ? 'close' : 'open';

                $.ajax({
                    url: 'updated_status.php',
                    type: 'POST',
                    data: {
                        rs_id: restaurantId,
                        open_or_close: newOpenCloseStatus
                    },
                    success: function(response) {
                        if (response == 'success') {
                            button.text(newOpenCloseStatus === 'close' ? 'Close' : 'Open');
                            button.data('open_or_close', newOpenCloseStatus);
                        } else {
                            alert('Failed to update status');
                        }
                    },
                    error: function() {
                        alert('AJAX error occurred!');
                    }
                });
            });
        });

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i].trim();
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length);
            }
            return null;
        }
    </script>
    <div id="main-wrapper">
        <div class="header">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
    <div class="navbar-header">
        <a class="navbar-brand" href="dashboard.php">
            <!-- <b><img src="images/logo.png" alt="homepage" class="dark-logo" /></b> -->
            <!-- Display Restaurant Name -->
            <span id="restaurantName">
                <?php 
                    if (isset($restaurant['res_name'])) {
                        echo $restaurant['res_name']; 
                    }
                ?>
            </span>
        </a>
    </div>
    <div class="navbar-collapse">
        <ul class="navbar-nav mr-auto mt-md-0">
            <li class="nav-item"> 
                <a class="nav-link nav-toggler hidden-md-up text-muted" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> 
            </li>
        </ul>
        <ul class="navbar-nav my-lg-0">
            <button class="btn btn-toggle-status mr-4 
                <?php echo $restaurant['open_or_close'] == 'open' ? 'btn-success' : 'btn-danger'; ?>" 
                data-id="<?php echo $restaurant['rs_id']; ?>" 
                data-open_or_close="<?php echo $restaurant['open_or_close']; ?>">
                <?php echo $restaurant['open_or_close'] == 'open' ? 'Open' : 'Close'; ?>    
            </button>

            <form action="send_message.php" method="POST" class="form-inline">
    <!-- Hidden field to pass res_id to PHP -->
    <input type="hidden" name="res_id" value="<?php echo $row['rs_id']; ?>">
    
    <!-- Text field to input the message -->
    <input type="text" name="message" class="form-control" placeholder="Send a message..." required>
    
    <!-- Submit button to send the message -->
    <button type="submit" name="send_message" class="btn btn-primary">Send</button>
</form>

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
                   <ul id="sidebarnav" class="mt-4">
                        <li>
                         <a class="has-arrow  " href="#" aria-expanded="false">  <span><i class="">+</i></span><span class="hide-menu">Add menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <a href="dashboard.php">Add Menu</a>
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                            <li><a href="all_menu.php">All Menu</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
								<li><a href="all_orderss.php">All Orders</a></li>
                            </ul>
                        </li>

                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center d-flex">
                    <h3 class="text-primary" style="width: 200px;" >All Orders</h3>
                     <!-- <li class="nav-item">
                        <a class="nav-link text-muted" href="javascript:void(0)">
                            <strong>Total cost of Restaurant and delivery (40%): </strong> 
                            <span class="badge badge-success">
                            <?php 
                                // Query to calculate the total amount of all orders (price * quantity)
                                $sql_total_price = "SELECT SUM(price * quantity) AS total_price FROM users_orders"; 
                                $result_total_price = mysqli_query($db, $sql_total_price);
                                $price_row = mysqli_fetch_assoc($result_total_price);
                                $total_price = $price_row['total_price'];

                                // Query to calculate 20% earnings (commission) of the total price
                                $total_earnings = $total_price * 0.4;

                                // Display the total earnings (20% of the total price), formatted to 2 decimal places
                                echo number_format($total_earnings, 2);
                                ?> INR
                            </span>
                        </a>
                    </li> -->

                    <!-- <li class="nav-item">
                        <a class="nav-link text-muted" href="javascript:void(0)">
                            <strong>Total Price of Orders: </strong> 
                            <span class="badge badge-info">
                                <?php 
                                // Display the total price of all orders, formatted to 2 decimal places
                                echo number_format($total_price, 2); 
                                ?> INR
                            </span>
                        </a>
                    </li> -->

                    <li class="nav-item">
                    <a class="nav-link text-muted" href="javascript:void(0)">
        <strong>Total Earnings (60%): </strong> 
        <span class="badge badge-success">
            <?php 
            // Display the total earnings (60% of the total price), formatted to 2 decimal places
            echo number_format($total_earnings_60, 2); 
            ?> INR
        </span>
    </a>
                    </li>
                </div>
            </div>

            <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">All User Orders</h4>
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
            <th>Accept or Reject</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($query) > 0) {
            while ($rows = mysqli_fetch_array($query)) {
                $order_id = $rows['o_id'];
                $title = $rows['title'];
                $quantity = $rows['quantity'];
                $price = $rows['price'];
                $address = isset($rows['address']) ? $rows['address'] : 'N/A';
                $order_date = $rows['date'];
                $status = $rows['status'] ?? 'pending';

                echo "<tr>";
                echo "<td>$title</td>";
                echo "<td>$quantity</td>";
                echo "<td>INR " . number_format($price, 2) . "</td>";
                echo "<td>$address</td>";
                
                if ($status == "" || $status == "NULL") {
                    echo '<td><button class="btn btn-info">Dispatch</button></td>';
                } elseif ($status == "in process") {
                    echo '<td><button class="btn btn-warning">On the Way!</button></td>';
                } elseif ($status == "closed") {
                    echo '<td><button class="btn btn-success">Delivered</button></td>';
                } elseif ($status == "rejected") {
                    echo '<td><button class="btn btn-danger">Cancelled</button></td>';
                } else {
                    echo '<td><button class="btn btn-secondary">Unknown</button></td>';
                }

                echo "<td>$order_date</td>";
                echo '<td>
                        <form action="update_order_status.php" method="POST">
    <input type="hidden" name="order_id" value="' . $order_id . '">
    <button type="submit" name="status" value="accepted" class="btn btn-success btn-sm">Accept</button>
    <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm">Reject</button>
</form>
                    </td>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'><center>No Orders Available!</center></td></tr>";
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

    <script src="../admin/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/js/jquery.slimscroll.js"></script>
    <script src="../admin/js/lib/jquery/jquery.min.js"></script>
    <script src="../admin/js/sidebarmenu.js"></script>
    <script src="../admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../admin/js/lib/datatables/datatables.min.js"></script>
    <script src="../admin/js/custom.min.js"></script>
</body>
</html>
