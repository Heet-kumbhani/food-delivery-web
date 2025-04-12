<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();


if(isset($_POST['submit'] ))
{
    if(empty($_POST['c_name']))
		{
			$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>field Required!</strong>
															</div>';
		}
	else
	{
		
	$check_cat= mysqli_query($db, "SELECT c_name FROM food_category where c_name = '".$_POST['c_name']."' ");
	if(mysqli_num_rows($check_cat) > 0)
     {
    	$error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Category already exist!</strong>
                </div>';
     }
	else{
	$mql = "INSERT INTO food_category(c_name) VALUES('".$_POST['c_name']."')";
	mysqli_query($db, $mql);
			$success = 	'<div class="alert alert-success alert-dismissible fade show">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Congrass!</strong> New Category Added Successfully.</br></div>';
    }
	}
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
    $category_id = $_POST['category_id'];
    $current_status = $_POST['current_status'];

    $new_status = ($current_status == 'active') ? 'inactive' : 'active';

    $update_status_query = "UPDATE food_category SET status = '$new_status' WHERE c_id = '$category_id'";

    if (mysqli_query($db, $update_status_query)) {
    } else {
        echo "Error updating status: " . mysqli_error($db);
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Admin Dashboard Template</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
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
                        <!-- <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search here"> <a class="srh-btn"><i class="ti-close"></i></a> </form>
                        </li> -->
                        <li class="nav-item dropdown">
                           
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    

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
                    <h3 class="text-primary"><a href="dashboard.php">Dashboard ></a> Add category</h3>
                </div>
            </div>
            <div class="container-fluid">
					  <div class="row">
					 <div class="container-fluid">
									<?php  
									        echo $error;
									        echo $success; ?>
					    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Add Restaurant Category</h4>
                            </div>
                            <div class="card-body">
                                <form action='' method='post' >
                                    <div class="form-body">
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Category</label>
                                                    <input type="text" name="c_name" class="form-control" placeholder="Category Name">
                                                   </div>
                                            </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-success" value="save"> 
                                        <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Listed Categories</h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Category Name</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sql = "SELECT * FROM food_category ORDER BY c_id DESC";
                                                $query = mysqli_query($db, $sql);

                                                if (!mysqli_num_rows($query) > 0) {
                                                    echo '<td colspan="5"><center>No Categories-Data!</center></td>';
                                                } else {
                                                    while ($rows = mysqli_fetch_array($query)) {

                                                        $buttonText = ($status == 'inactive') ? 'Deactivate' : 'Activate';
                                                        $buttonClass = ($status == 'inactive') ? 'btn-danger' : 'btn-success';

                                                        echo '<tr>
                                                                <td>' . $rows['c_id'] . '</td>
                                                                <td>' . $rows['c_name'] . '</td>
                                                                <td>' . $rows['date'] . '</td>
                                                                <td>
                                                                    <!-- Status Button to Toggle -->
                                                                    <form method="POST" action="">
                                                                        <input type="hidden" name="category_id" value="' . $rows['c_id'] . '">
                                                                        <input type="hidden" name="current_status" value="' . $status . '">
                                                                        <button type="submit" name="toggle_status" class="btn ' . $buttonClass . ' btn-flat btn-addon btn-xs m-b-10">
                                                                            <i class="fa fa-toggle-on"></i> ' . $buttonText . '
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                                <td>
                                                                    <a href="delete_category.php?cat_del=' . $rows['c_id'] . '" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                        <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                    </a>
                                                                    <a href="update_category.php?cat_upd=' . $rows['c_id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
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
            </div>
            <footer class="footer"> © 2024 All rights reserved. </footer>
        </div>
    </div>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>


