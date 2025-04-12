<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {           // If the submit button is pressed
    if (empty($_POST['d_name']) || empty($_POST['slogan']) || $_POST['price'] == '' || $_POST['res_name'] == '' || $_POST['d_description'] == '' || $_POST['subcat_id'] == '') {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>All fields must be filled out!</strong>
                  </div>';
    } else {
        $fname = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
        $fsize = $_FILES['file']['size'];
        $extension = explode('.', $fname);
        $extension = strtolower(end($extension));
        $fnew = uniqid() . '.' . $extension;
        $store = "Res_img/dishes/" . basename($fnew);  // Path to store uploaded image

        if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
            if ($fsize >= 1000000) {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <strong>Max image size is 1024KB!</strong> Try a different image.
                          </div>';
            } else {
                // Insert the dish into the database
                $sql = "INSERT INTO dishes(subcat_id, d_name, d_discription, price, img, rs_id, status) 
                        VALUES ('" . $_POST['subcat_id'] . "', '" . $_POST['d_name'] . "', '" . $_POST['d_description'] . "', '" . $_POST['price'] . "', '" . $fnew . "', '" . $_POST['res_name'] . "', 'active')";
                mysqli_query($db, $sql);
                move_uploaded_file($temp, $store);

                $success = '<div class="alert alert-success alert-dismissible fade show">
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                               <strong>Congrats!</strong> New dish added successfully.
                           </div>';
            }
        } elseif ($extension == '') {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>Select an image!</strong>
                      </div>';
        } else {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>Invalid extension!</strong> Only PNG, JPG, GIF are accepted.
                      </div>';
        }
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
    <link rel="icon" type="image/png" sizes="16x16" href="12">
    <title>Admin Dashboard Template</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
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
                    </ul>
                    <ul class="navbar-nav my-lg-0">
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
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
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
                                <!-- <li><a href="add_restraunt.php">Add Restaurant</a></li> -->
                                
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
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>

        <div class="page-wrapper" style="height:1200px;">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> 
                </div>
            </div>

            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Add Menu to Restaurant</h4>
                        </div>
                        <div class="card-body">
                            <?php  
                                echo $error;
                                echo $success; 
                            ?>
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Dish Name *</label>
                                                <input type="text" name="d_name" class="form-control" placeholder="Morzirella" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Slogan *</label>
                                                <input type="text" name="slogan" class="form-control form-control-danger" placeholder="Slogan" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Price *</label>
                                                <input type="text" name="price" class="form-control" placeholder="₹" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Image *</label>
                                                <input type="file" name="file" class="form-control form-control-danger" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Dish Description *</label>
                                                <textarea name="d_description" class="form-control" placeholder="Describe the dish" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Select Category *</label>
                                                <select name="category_id" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" required>
                                                    <option>--Select Category--</option>
                                                    <?php
                                                        // Fetch categories from the database
                                                        $category_sql = "SELECT * FROM food_category";  // Assuming you have a categories table
                                                        $category_res = mysqli_query($db, $category_sql);
                                                        while ($row = mysqli_fetch_array($category_res)) {
                                                            echo '<option value="' . $row['c_id'] . '">' . $row['c_name'] . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Select Subcategory *</label>
                                                <select name="subcat_id" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" required>
                                                    <option>--Select Subcategory--</option>
                                                    <?php
                                                        $ssql = "SELECT * FROM subcategories";  // Assuming you have a table for subcategories
                                                        $res = mysqli_query($db, $ssql);
                                                        while ($row = mysqli_fetch_array($res)) {
                                                            echo '<option value="' . $row['subcat_id'] . '">' . $row['subcat_name'] . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Select Restaurant *</label>
                                                <select name="res_name" class="form-control custom-select" data-placeholder="Choose a Restaurant" tabindex="1" required>
                                                    <option>--Select Restaurant--</option>
                                                    <?php
                                                        $ssql = "SELECT * FROM restaurant";
                                                        $res = mysqli_query($db, $ssql);
                                                        while ($row = mysqli_fetch_array($res)) {
                                                            echo '<option value="' . $row['rs_id'] . '">' . $row['res_name'] . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-success" value="Save">
                                        <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
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
</body>
</html>
