<?php
// Include database connection
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if the subcategory ID is passed in the URL
if (isset($_GET['subcat_upd'])) {
    $subcat_id = $_GET['subcat_upd'];

    // Query to fetch the subcategory details
    $sql = "SELECT * FROM subcategories WHERE subcat_id = '$subcat_id'";
    $query = mysqli_query($db, $sql);

    if ($row = mysqli_fetch_array($query)) {
        // Fetch the category name for the subcategory
        $category_query = mysqli_query($db, "SELECT c_name FROM food_category WHERE c_id = " . $row['c_id']);
        $category = mysqli_fetch_array($category_query);
        
        // Store details for display
        $subcat_name = $row['subcat_name'];
        $category_name = $category['c_name'];
    } else {
        echo "Subcategory not found!";
    }
}

// Handling the form submission to update subcategory
if (isset($_POST['update'])) {
    $subcat_name = $_POST['subcat_name'];
    $category_id = $_POST['category_id'];

    // Update query
    $update_query = "UPDATE subcategories SET subcat_name = '$subcat_name', c_id = '$category_id' WHERE subcat_id = '$subcat_id'";

    if (mysqli_query($db, $update_query)) {
        echo "Subcategory updated successfully!";
        // You can redirect to another page if needed
        header("Location: sub_category.php");
        exit();
    } else {
        echo "Error updating subcategory: " . mysqli_error($db);
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
    <title>Admin Dashboard Template</title>
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <style>
        #foot {
            background: #ffffff none repeat scroll 0 0;
            border-top: 1px solid rgba(120, 130, 140, 0.13);
            color: #67757c;
            position: absolute;
            left: 0px;
        }
    </style>
</head>

<body class="fix-header" style="background:#fafafa;">
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
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
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
                            <li><a href="oncome.php">Income</a></li>                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="page-wrapper" style="height:1200px;">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary"><a href="dashboard.php">Dashboard ></a><a href="sub_category.php"> SubCategory ></a> Update Subcategory</h3>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="container-fluid">
                        <?php
                            echo $error;
                            echo $success;
                        ?>
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Update Subcategory</h4>
                                </div>
                                <div class="card-body">
                                    <form action="update_subcategory.php?subcat_upd=<?php echo $subcat_id; ?>" method="post">
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">
                                              <div class="col-md-12">
                                                <div class="form-group">
                                                  <label class="control-label">Category</label>
                                                        <select name="category_id" class="form-control">
                                                          <option value="<?php echo $row['c_id']; ?>" selected><?php echo $category_name; ?></option>
                                                          <?php
                                                            $category_query = mysqli_query($db, "SELECT * FROM food_category");
                                                            while ($category_row = mysqli_fetch_array($category_query)) {
                                                              echo '<option value="' . $category_row['c_id'] . '">' . $category_row['c_name'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Subcategory Name</label>
                                                            <input type="text" name="subcat_name" value="<?php echo $subcat_name; ?>" class="form-control" placeholder="Subcategory Name">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="update" class="btn btn-success">Update Subcategory</button>
                                            <a href="sub_category.php" class="btn btn-inverse">Back</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer" id="foot"> © 2024 All rights reserved. </footer>
    </div>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
