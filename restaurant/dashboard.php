<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {
    if (empty($_POST['d_name']) || trim($_POST['d_name']) == '' ||  $_POST['price'] == '' || trim($_POST['price']) == '' || $_POST['res_name'] == '' || $_POST['d_description'] == '' || trim($_POST['d_description']) == '' || $_POST['subcat_id'] == '') {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>All fields must be filled out!</strong>
                  </div>';
    } else {
        
        $d_name = mysqli_real_escape_string($db, $_POST['d_name']);
        $check_dish = "SELECT * FROM dishes WHERE d_name = '$d_name' LIMIT 1";
        $check_result = mysqli_query($db, $check_dish);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>Dish with this name already exists!</strong>
                      </div>';
        } else {
            
            $price = $_POST['price'];
            if ($price <= 0) {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <strong>Price must be greater than ₹0 and at least ₹70!</strong>
                          </div>';
            } elseif ($price < 70) {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <strong>Price must be at least ₹70!</strong>
                          </div>';
            } else {
                
                $fname = $_FILES['file']['name'];
                $temp = $_FILES['file']['tmp_name'];
                $fsize = $_FILES['file']['size'];
                $extension = explode('.', $fname);
                $extension = strtolower(end($extension));
                $fnew = uniqid() . '.' . $extension;
                $store = "../admin/Res_img/dishes/" . basename($fnew);

                
                if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
                    if ($fsize >= 10000000) {  
                        $error = '<div class="alert alert-danger alert-dismissible fade show">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <strong>Max image size is 10MB!</strong> Try a different image.
                                  </div>';
                    } else {
                        
                        $sql = "INSERT INTO dishes(subcat_id, d_name, d_discription, price, img, rs_id, status) 
                                VALUES ('" . $_POST['subcat_id'] . "', '" . $d_name . "', '" . $_POST['d_description'] . "', '" . $price . "', '" . $fnew . "', '" . $_POST['res_name'] . "', 'inactive')";
                        mysqli_query($db, $sql);
                        move_uploaded_file($temp, $store);

                        $success = '<div class="alert alert-success alert-dismissible fade show">
                                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                       <strong>Congrats!</strong> New dish added successfully.
                                   </div>';
                                   header("location:dashboard.php");
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
    }
}

if (!isset($_SESSION['restaurant_id'])) {
    header("Location: login.php");
    exit();
}


$restaurant_id = $_SESSION['restaurant_id'];


$query = "SELECT * FROM restaurant WHERE rs_id = '$restaurant_id' LIMIT 1";
$result = mysqli_query($db, $query);
$restaurant = mysqli_fetch_assoc($result);

if ($restaurant) {
    
    $status = isset($_COOKIE['open_or_close']) ? $_COOKIE['open_or_close'] : $restaurant['open_or_close'];
} else {
    $status = 'closed'; 
}


$restaurant_id = $_SESSION['restaurant_id'];


$query = "SELECT * FROM restaurant WHERE rs_id = '$restaurant_id' LIMIT 1";
$result = mysqli_query($db, $query);
$restaurant = mysqli_fetch_assoc($result);

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
    <link href="../admin/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

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

$(document).ready(function() {
    $('.btn-toggle-status').on('click', function() {
        var button = $(this);
        var currentOpenClose = button.data('open_or_close');  
        var restaurantId = getCookie('restaurant_id');  

        if (!restaurantId) {
            alert("Restaurant ID not found in cookies.");
            return; 
        }

        
        var newOpenCloseStatus = currentOpenClose === 'open' ? 'close' : 'open';

        
        button.prop('disabled', true).text('Updating...');

        
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
                    button.prop('disabled', false); 
                } else {
                    alert('Failed to update the status. Please try again.');
                    button.prop('disabled', false).text(currentOpenClose === 'open' ? 'Open' : 'Close');
                }
            },
            error: function() {
                alert('An error occurred while updating the status. Please try again later.');
                button.prop('disabled', false).text(currentOpenClose === 'open' ? 'Open' : 'Close');
            }
        });
    });
});
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
                        <li>
                            <!-- <a href="profile.php"><img src="../admin/images/manager.png" alt="user" class="profile-pic bg-dark p-1 mr-3" />Profile</a> -->
                        </a></li>
                        <li class="ml-2"><a href="logout.php"><i class="fa fa-power-off profile-pic"></i> Logout</a></li>
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
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>

        <div class="page-wrapper" style="height:1200px; width: auto;">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center d-flex">
                    <h3 style="width: 200px;" class="text-primary">Add menu</h3>
                    <!-- <li class="nav-item">
                        <a class="nav-link text-muted" href="javascript:void(0)">
                            <strong>Total cost of Restaurant and delivery (40%): </strong> 
                            <span class="badge badge-success">
                                <?php 
                                
                                $sql_total_price = "SELECT SUM(price * quantity) AS total_price FROM users_orders"; 
                                $result_total_price = mysqli_query($db, $sql_total_price);
                                $price_row = mysqli_fetch_assoc($result_total_price);
                                $total_price = $price_row['total_price'];

                                
                                $total_earnings = $total_price * 0.4;

                                
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
                                
                                $total_earnings_40 = $total_price * 0.6;

                                
                                echo number_format($total_earnings_40, 2); 
                                ?> INR
                            </span>
                        </a>
                    </li>
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
                                    <input type="text" name="d_name" class="form-control" placeholder="Morzirella" required value="<?php echo isset($_POST['d_name']) ? $_POST['d_name'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Price *</label>
                                    <input type="number" name="price" class="form-control" placeholder="₹" required min="100" max="10000" value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group has-danger">
                                    <label class="control-label">Image *</label>
                                    <input type="file" name="file" class="form-control form-control-danger" required >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Dish Description *</label>
                                    <textarea name="d_description" class="form-control" placeholder="Describe the dish" required><?php echo isset($_POST['d_description']) ? $_POST['d_description'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Select Category *</label>
                                                <select name="category_id" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" id="category-select" required>
                                                    <option value="">--Select Category--</option>
                                                    <?php
                                                        
                                                        $category_sql = "SELECT * FROM food_category";  
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
                                                <select name="subcat_id" class="form-control custom-select" data-placeholder="Choose a Subcategory" tabindex="1" id="subcategory-select" required>
                                                    <option value="">--Select Subcategory--</option>
                                                </select>
                                            </div>
                                      </div>
                                    </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Select Restaurant *</label>
                                <select name="res_name" class="form-control custom-select" data-placeholder="Choose a Restaurant" tabindex="1" required>
    <option value="">--Select Restaurant--</option>
    <?php
        
        if (isset($_COOKIE['res_id'])) {
            
            $restaurant_id = $_COOKIE['res_id'];

            
            $ssql = "SELECT rs_id, res_name FROM restaurant WHERE rs_id = '$restaurant_id'";
            $res = mysqli_query($db, $ssql);  

            
            if ($res && mysqli_num_rows($res) > 0) {
                
                $row = mysqli_fetch_assoc($res);
                
                echo '<option value="' . $row['rs_id'] . '" selected>' . $row['res_name'] . '</option>';
            } else {
                
                echo "<option disabled>No restaurant found based on cookie</option>";
            }
        } else {
            
            echo "<option disabled>No restaurant selected in cookies</option>";
        }
    ?>
</select>
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

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- AJAX Script for Dynamic Subcategory Loading -->
    <script>
        $('#category-select').on('change', function() {
            var categoryId = $(this).val(); 

            if (categoryId) {
                $.ajax({
                    url: 'get_subcategories.php', 
                    type: 'POST',
                    data: { category_id: categoryId },
                    success: function(response) {
                        $('#subcategory-select').html(response); 
                    },
                    error: function() {
                        alert('Error loading subcategories!');
                    }
                });
            } else {
                $('#subcategory-select').html('<option value="">--Select Subcategory--</option>');
            }
        });
    </script>

    <script src="../admin/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="../admin/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin/js/jquery.slimscroll.js"></script>
    <script src="../admin/js/sidebarmenu.js"></script>
    <script src="../admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../admin/js/custom.min.js"></script>
</body>
</html>
