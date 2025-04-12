<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {  
    if (empty($_POST['res_name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_FILES['file']['name'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>All fields must be filled out!</strong>
                  </div>';
    } else {
        $fname = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
        $fsize = $_FILES['file']['size'];
        $extension = strtolower(pathinfo($fname, PATHINFO_EXTENSION));  
        $fnew = uniqid().'.'.$extension;
        $store = "Res_img/".basename($fnew);  // the path to store the uploaded image

        if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
            if ($fsize > 1024000) {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Max Image Size is 1024kb!</strong> Try a different image.
                          </div>';
            } else {
                // Prepare an update SQL query
                $sql = "UPDATE restaurant SET 
                        res_name = '$_POST[res_name]',
                        email = '$_POST[email]',
                        phone = '$_POST[phone]',
                        address = '$_POST[address]',
                        image = '$fnew'
                        WHERE rs_id = '$_GET[res_upd]'";

                // Execute the query and move the uploaded file to the destination folder
                if (mysqli_query($db, $sql)) {
                    move_uploaded_file($temp, $store);

                    $success = '<div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Record updated successfully!</strong>
                              </div>';
                } else {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Failed to update the record!</strong>
                              </div>';
                }
            }
        } else {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Invalid image extension!</strong> Only jpg, png, gif are accepted.
                      </div>';
        }
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

<body class="fix-header">
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
                        <li class="nav-item"><a class="nav-link nav-toggler hidden-md-up text-muted" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a></li>
                        <li class="nav-item m-l-10"><a class="nav-link sidebartoggler hidden-sm-down text-muted" href="javascript:void(0)"><i class="ti-menu"></i></a></li>
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item hidden-sm-down search-box"><a class="nav-link hidden-sm-down text-muted" href="javascript:void(0)"><i class="ti-search"></i></a>
                        <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search here"><a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/manager.png" alt="user" class="profile-pic bg-dark p-1" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label"></li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="dashboard.php">Dashboard</a></li>
                            </ul>
                        </li>
                        <li class="nav-label">Log</li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><span><i class="fa fa-user f-s-20"></i></span><span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allusers.php">All Users</a></li>
                                <!-- <li><a href="add_users.php">Add Users</a></li> -->
                            </ul>
                        </li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="allrestraunt.php">All Restaurant</a></li>
                                <!-- <li><a href="add_restraunt.php">Add Restaurant</a></li> -->
                            </ul>
                        </li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="all_menu.php">All Menus</a></li>
                            <li><a href="add_category.php">Add Category</a></li>
                            <li><a href="sub_category.php">Add SubCategory</a></li>
                            </ul>
                        </li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="hide-menu">Orders</span></a>
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
                    <h3 class="text-primary">Update Restaurant</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>

            <div class="container-fluid">
                <?php echo $error; echo $success; ?>
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <h4 class="m-b-0">Update Restaurant</h4>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php 
                                        $ssql = "SELECT * FROM restaurant WHERE rs_id = '$_GET[res_upd]'";
                                        $res = mysqli_query($db, $ssql);
                                        $row = mysqli_fetch_array($res);
                                    ?>
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Restaurant Name *</label>
                                                <input type="text" name="res_name" value="<?php echo $row['res_name']; ?>" class="form-control" placeholder="Restaurant Name" >
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Business E-mail *</label>
                                                <input type="email" name="email" value="<?php echo $row['email']; ?>" class="form-control form-control-danger" placeholder="Business Email" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Phone *</label>
                                                <input type="tel" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" placeholder="Phone Number" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Image *</label>
                                                <input type="file" name="file" class="form-control form-control-danger" >
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="box-title m-t-40">Store Address *</h3>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="address" class="form-control" style="height:100px;" ><?php echo $row['address']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-success" value="Save">
                                        <a href="allrestraunt.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
