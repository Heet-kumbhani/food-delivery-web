<?php
include("../connection/connect.php");
error_reporting(E_ALL);  // Enable error reporting for debugging
session_start();

if (isset($_POST['toggle_status'])) {
  $id = $_POST['id'];
  $current_status = $_POST['current_status'];

  // Toggle status logic
  $new_status = ($current_status == 'active') ? 'inactive' : 'active';

  // Update the status in the database
  $sql = "UPDATE delivery_boys SET status = '$new_status' WHERE id = $id"; // Added semicolon here

  if (mysqli_query($db, $sql)) {
    // Success (optional: you can add a success message or redirect here)
  } else {
    echo "Error: " . mysqli_error($db);
  }
}
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
    <title>Admin Dashboard</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
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
                    <a class="navbar-brand" href="dashboard.php">
                        <b><img src="images/logo.png" alt="homepage" class="dark-logo" /></b>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                    <li class="nav-item">
                            <a class="nav-link text-muted" href="javascript:void(0)">
                                <strong>Total Earnings: </strong> 
                                <span class="badge badge-success">
                                    <?php 
                                    // Query to calculate the total amount of all orders (price * quantity)
                                    $sql_total_price = "SELECT SUM(price * quantity) AS total_price FROM users_orders"; 
                                    $result_total_price = mysqli_query($db, $sql_total_price);
                                    $price_row = mysqli_fetch_assoc($result_total_price);
                                    $total_price = $price_row['total_price'];

                                    // Query to calculate 20% earnings (commission) of the total price
                                    $total_earnings = $total_price * 0.2;

                                    // Display the total earnings (20% of the total price), formatted to 2 decimal places
                                    echo number_format($total_earnings, 2); 
                                    ?> INR
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                        <a class="nav-link text-muted" href="javascript:void(0)">
                            <strong>Total cost of Restaurant and delivery (40%): </strong> 
                            <span class="badge badge-info">
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
                    </li>


                    <li class="nav-item">
                        <a class="nav-link text-muted" href="javascript:void(0)">
                            <strong>Total Price of Orders: </strong> 
                            <span class="badge badge-info">
                                <?php 
                                // Display the total price of all orders, formatted to 2 decimal places
                                echo number_format($total_price, 2); 
                                ?> INR
                            </span>
                        </a>
                    </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/manager.png" alt="user" class="profile-pic bg-dark p-1" /></a>
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
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                            </ul>
                        </li>
                        <li class="nav-label">Log</li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false">  <span><i class="fa fa-user f-s-20"></i></span><span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allusers.php">All Users</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allrestraunt.php">All Restaurant</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Delivery boy</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="delivery_boy.php">All delivery boy</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">All Menues</a></li>
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="sub_category.php">Add SubCategory</a></li>
                            </ul>
                        </li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="hide-menu">Orders</span></a>
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

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary"><a href="dashboard.php">Dashboard ></a> Delivery Boys</h3>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Delivery Boys</h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Phone No</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sql = "SELECT * FROM delivery_boys ORDER BY id DESC";
                                                $query = mysqli_query($db, $sql);

                                                if (mysqli_num_rows($query) <= 0) {
                                                    echo '<tr><td colspan="5"><center>No Delivery Boys Found!</center></td></tr>';
                                                } else {
                                                    while ($rows = mysqli_fetch_array($query)) {
                                                        $status = $rows['status'];
                                                        $buttonText = ($status == 'active') ? 'Activate' : 'Deactivate';
                                                        $buttonClass = ($status == 'active') ? 'btn-success' : 'btn-danger';
                                            ?>
                                                        <tr>
                                                            <td><?php echo $rows['full_name']; ?></td>
                                                            <td><?php echo $rows['phone_no']; ?></td>
                                                            <td><?php echo $rows['email']; ?></td>
                                                            <td><?php echo $rows['address']; ?></td>
                                                            <td>
                                                                <form method="POST" action="">
                                                                  <input type="hidden" name="id" value="<?php echo $rows['id']; ?>">
                                                                  <input type="hidden" name="current_status" value="<?php echo $status; ?>">
                                                                  <button type="submit" name="toggle_status" class="btn <?php echo $buttonClass; ?> btn-flat btn-addon btn-xs m-b-10">
                                                                  <i class="fa fa-toggle-on"></i> <?php echo $buttonText; ?>
                                                                  </button>
                                                              </form>
                                                            </td>
                                                            <td><?php echo $rows["date_created"]?></td>
                                                            <td>
                                                                <!-- Corrected action links -->
                                                                <a href="delete_delivery_boy.php?order_del=<?php echo $rows['id']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i class="fa fa-trash-o" style="font-size:16px"></i></a>
                                                                <!-- <a href="view_order.php?user_upd=<?php echo $rows['id']; ?>" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i class="ti-settings"></i></a> -->
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

    <script src="../admin/js/lib/jquery/jquery.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/js/jquery.slimscroll.js"></script>
    <script src="../admin/js/sidebarmenu.js"></script>
    <script src="../admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../admin/js/custom.min.js"></script>
</body>
</html>
