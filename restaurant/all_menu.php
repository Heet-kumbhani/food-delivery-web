<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['toggle_status']) && isset($_POST['dish_id']) && isset($_POST['current_status'])) {
    $dish_id = mysqli_real_escape_string($db, $_POST['dish_id']);
    $current_status = mysqli_real_escape_string($db, $_POST['current_status']);
    $new_status = ($current_status == 'active') ? 'inactive' : 'active';
    $sql_update = "UPDATE dishes SET status = '$new_status' WHERE d_id = '$dish_id'";

    if (mysqli_query($db, $sql_update)) {
    } else {
        echo '<script>alert("Failed to update dish status. Please try again later."); window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
    }
}

if (!isset($_SESSION['restaurant_id'])) {
    header("Location: login.php");
    exit();
}

// Get the restaurant ID from the session
$restaurant_id = $_SESSION['restaurant_id'];

// Retrieve restaurant information from the database
$query = "SELECT * FROM restaurant WHERE rs_id = '$restaurant_id' LIMIT 1";
$result = mysqli_query($db, $query);
$restaurant = mysqli_fetch_assoc($result);

if ($restaurant) {
    // Use the session or cookie to get the restaurant's open or close status
    $status = isset($_COOKIE['open_or_close']) ? $_COOKIE['open_or_close'] : $restaurant['open_or_close'];
} else {
    $status = 'closed'; // Default if no data found
}

// Retrieve dishes only for the current restaurant
$sql = "SELECT * FROM dishes WHERE rs_id = '$restaurant_id' ORDER BY d_id DESC";
$query = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">

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

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        // Function to get the value of a specific cookie by its name
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        $(document).ready(function () {
            $('.btn-toggle-status').on('click', function () {
                var button = $(this);
                var currentOpenClose = button.data('open_or_close');  // Get current 'open' or 'close' status
                var restaurantId = getCookie('restaurant_id');  // Get restaurant_id from cookies

                // Toggle between 'open' and 'close'
                var newOpenCloseStatus = currentOpenClose === 'open' ? 'close' : 'open';

                // Send AJAX request to update only the open/close status in the database
                $.ajax({
                    url: 'updated_status.php',  // The PHP file to update the status
                    type: 'POST',
                    data: {
                        rs_id: restaurantId,
                        open_or_close: newOpenCloseStatus // Send only the open/close status
                    },
                    success: function (response) {
                        // If the open/close status was updated successfully, change the button text and data
                        if (response == 'success') {
                            button.text(newOpenCloseStatus === 'close' ? 'close' : 'open'); // Update button text
                            button.data('open_or_close', newOpenCloseStatus);  // Update the open_or_close status
                        } else {
                            alert('Failed to update status');
                        }
                    },
                    error: function () {
                        alert('AJAX error occurred!');
                    }
                });
            });
        });
    </script>

    <div id="main-wrapper">
        <!-- Header and Sidebar code -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
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
                    <h3 class="text-primary" style="width: 200px;" >All Menu</h3>
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
                                // Calculate 40% earnings (commission) of the total price
                                $total_earnings_40 = $total_price * 0.6;

                                // Display the total earnings (40% of the total price), formatted to 2 decimal places
                                echo number_format($total_earnings_40, 2); 
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
                                <h4 class="card-title">All Menu Data</h4>
                                <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Restaurant</th>
                                                <th>Dish Name</th>
                                                <th>Description</th>
                                                <th>Price(INR)</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Restaurant</th>
                                                <th>Dish Name</th>
                                                <th>Description</th>
                                                <th>Price(INR)</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="7"><center>No Dishes Data!</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $mql = "SELECT * FROM restaurant WHERE rs_id='" . $rows['rs_id'] . "'";
                                                    $newquery = mysqli_query($db, $mql);
                                                    $fetch = mysqli_fetch_array($newquery);
                                                    $status = $rows['status'];
                                                    $buttonText = ($status == 'active') ? 'Active' : 'Inactive';
                                                    $buttonClass = ($status == 'active') ? 'btn-success' : 'btn-danger';

                                                    echo '<tr>
                                                            <td>' . $fetch['res_name'] . '</td>
                                                            <td>' . $rows['d_name'] . '</td>
                                                            <td>' . $rows['d_discription'] . '</td>
                                                            <td>' . $rows['price'] . '</td>
                                                            <td><div class="col-md-3 col-lg-8 m-b-10">
                                                                <center><img src="../admin/Res_img/dishes/' . $rows['img'] . '" class="img-responsive radius" style="max-height:100px;max-width:150px;" /></center></div></td>
                                                            <td>
                                                                <form method="POST" action="">
                                                                    <input type="hidden" name="dish_id" value="' . $rows['d_id'] . '">
                                                                    <input type="hidden" name="current_status" value="' . $status . '">
                                                                    <button type="submit" name="toggle_status" class="btn ' . $buttonClass . ' btn-flat btn-addon btn-xs m-b-10">
                                                                        <i class="fa fa-toggle-on"></i> ' . $buttonText . '
                                                                    </button>
                                                                </form>
                                                            </td>
                                                            <td>
                                                                <a href="delete_dish.php?dish_del=' . $rows['d_id'] . '" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                </a>
                                                                <a href="update_dish.php?dish_upd=' . $rows['d_id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
                                                                    <i class="ti-settings"></i>
                                                                </a>
                                                            </td>
                                                        </tr>';
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
                <footer class="footer"> © 2024 All rights reserved. </footer>
            </div>
        </div>
    </div>

    <script src="../admin/js/lib/jquery/jquery.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/js/jquery.slimscroll.js"></script>
    <script src="../admin/js/lib/datatables/datatables.min.js"></script>
    <script src="../admin/js/sidebarmenu.js"></script>
    <script src="../admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../admin/js/custom.min.js"></script>
</body>
</html>
