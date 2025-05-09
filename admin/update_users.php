<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
include("../connection/connect.php");

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    // Validate inputs
    $uname = trim($_POST['uname']);
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);

    if (empty($uname) || empty($fname) || empty($lname) || empty($email) || empty($password) || empty($phone)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                      <strong>All fields are required!</strong>
                  </div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                      <strong>Invalid email!</strong>
                  </div>';
    } elseif (strlen($password) < 6) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                      <strong>Password must be at least 6 characters!</strong>
                  </div>';
    } elseif (strlen($phone) < 10) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                      <strong>Invalid phone number!</strong>
                  </div>';
    } else {
        // Prepare and execute the update query
        $hashedPassword = md5($password); // Consider using password_hash() instead
        $userId = $_GET['user_upd'];
        $mql = "UPDATE users SET username=?, f_name=?, l_name=?, email=?, phone=?, password=? WHERE u_id=?";
        $stmt = mysqli_prepare($db, $mql);
        mysqli_stmt_bind_param($stmt, 'ssssssi', $uname, $fname, $lname, $email, $phone, $hashedPassword, $userId);
        if (mysqli_stmt_execute($stmt)) {
            $success = '<div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>User updated successfully!</strong>
                        </div>';
        } else {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                          <strong>Error updating user!</strong>
                      </div>';
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch user data
$ssql = "SELECT * FROM users WHERE u_id=?";
$stmt = mysqli_prepare($db, $ssql);
mysqli_stmt_bind_param($stmt, 'i', $_GET['user_upd']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$newrow = mysqli_fetch_array($result);
mysqli_stmt_close($stmt);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Admin Dashboard Template</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /></svg>
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

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Update User</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="form-body">
                                        <h3 class="card-title m-t-15">User Info</h3>
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Username</label>
                                                    <input type="text" name="uname" class="form-control" value="<?php echo $newrow['username']; ?>" placeholder="John doe">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">First Name</label>
                                                    <input type="text" name="fname" class="form-control" value="<?php echo $newrow['f_name']; ?>" placeholder="John">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input type="text" name="lname" class="form-control" value="<?php echo $newrow['l_name']; ?>" placeholder="Doe">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input type="email" name="email" class="form-control" value="<?php echo $newrow['email']; ?>" placeholder="john.doe@example.com">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Password</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Enter New Password">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phone</label>
                                                    <input type="tel" name="phone" class="form-control" value="<?php echo $newrow['phone']; ?>" placeholder="(999) 999-9999">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                        <a href="allusers.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">© 2024 All rights reserved.</footer>
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
