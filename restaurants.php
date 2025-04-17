<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>
<head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Tomato</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
        <header id="header" class="header-scroll top-header headrom">
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/logo.png" alt=""> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="restaurants.php">Restaurants <span class="sr-only"></span></a> </li>

							<?php
						if(empty($_SESSION["user_id"]))
							{
								echo '<li class="nav-item"><a href="login.php" class="nav-link active">login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active">signup</a> </li>';
							}
						else
							{
								echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">your orders</a> </li>';
								echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">logout</a> </li>';
							}

						?>
							 
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="page-wrapper">
            <div class="top-links">
                <div class="container">
                    <ul class="row links">
                       
                        <li class="col-xs-12 col-sm-4 link-item active"><span>1</span><a href="restaurants.php">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="#">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay ofline</a></li>
                    </ul>
                </div>
            </div>
            <div class="inner-page-hero bg-image" data-image-src="images/img/res3.jpeg">
                <div class="container"> </div>
            </div>
            <div class="result-show">
                <div class="container">
                    <div class="row">
                       
                       
                    </div>
                </div>
            </div>
            <section class="restaurants-page">
    <div class="container">
        <form method="GET">
            <select name="category" class="px-2 py-1 m-2" onchange="this.form.submit()">
                <option value="">Select Category</option>
                <?php
                    $category_query = "SELECT * FROM food_category WHERE status = 'active'";
                    $category_result = mysqli_query($db, $category_query);

                    while ($category = mysqli_fetch_assoc($category_result)) {
                        $selected = (isset($_GET['category']) && $_GET['category'] == $category['c_id']) ? 'selected' : '';
                        echo '<option value="' . $category['c_id'] . '" ' . $selected . '>' . $category['c_name'] . '</option>';
                    }
                ?>
            </select>
        </form>

        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-12">
                <div class="bg-gray restaurant-entry">
                    <div class="row">
                    <?php
    $where_clause = "";
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $category_id = mysqli_real_escape_string($db, $_GET['category']);
        $subcategory_query = "SELECT * FROM subcategories WHERE c_id = '$category_id' AND status = 'active'";
        $subcategory_result = mysqli_query($db, $subcategory_query);
        $subcategory_ids = [];
        while ($subcategory = mysqli_fetch_assoc($subcategory_result)) {
            $subcategory_ids[] = $subcategory['subcat_id'];
        }
        if (!empty($subcategory_ids)) {
            $subcategory_ids_list = implode(",", $subcategory_ids);
            $where_clause = " WHERE subcat_id IN ($subcategory_ids_list)";
        }
    }
    $query_res = mysqli_query($db, "
        SELECT d.*, COUNT(r.rating_id) AS review_count
        FROM dishes d
        LEFT JOIN product_ratings r ON d.d_id = r.order_id
        " . $where_clause . "
        GROUP BY d.d_id
    ");
    
    if (mysqli_num_rows($query_res) > 0) {
        while ($r = mysqli_fetch_array($query_res)) {
            if ($r['status'] == 'active') {
                echo '<div class="col-xs-12 col-sm-6 col-md-4 food-item">
                        <div class="food-item-wrap">
                            <div class="figure-wrap bg-image" data-image-src="admin/Res_img/dishes/'.$r['img'].'">
                                <div class="rating pull-left"><a href="#">' . $r['review_count'] . ' reviews</a></div>
                            </div>
                            <div class="content">
                                <h5><a href="dishes.php?res_id='.$r['rs_id'].'">'.$r['d_name'].'</a></h5>
                                <div class="product-name">'.htmlspecialchars($r['d_discription']).'</div>
                                <div class="price-btn-block"> 
                                    <span class="price">INR '.$r['price'].'</span> 
                                    <a href="dishes.php?res_id='.$r ['rs_id'].'" class="btn theme-btn-dash pull-right">Order Now</a> 
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
    } else {
        echo '<div class="col-12"><p class="text-center">No dishes found for this category or subcategory.</p></div>';
    }
?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



            <section class="app-section">
                <div class="app-wrap">
                    <div class="container">
                        <div class="row text-img-block text-xs-left">
                            <div class="container">
                                <div class="col-xs-12 col-sm-6 hidden-xs-down right-image text-center">
                                    <figure> <img src="images/app.png" alt="Right Image"> </figure>
                                </div>
                                <div class="col-xs-12 col-sm-6 left-text">
                                    <h3>The Best Food Delivery App</h3>
                                    <p>Now you can make food happen pretty much wherever you are thanks to the free easy-to-use Food Delivery &amp; Takeout App.</p>
                                    <div class="social-btns">
                                        <a href="#" class="app-btn apple-button clearfix">
                                            <div class="pull-left"><i class="fa fa-apple"></i> </div>
                                            <div class="pull-right"> <span class="text">Available on the</span> <span class="text-2">App Store</span> </div>
                                        </a>
                                        <a href="#" class="app-btn android-button clearfix">
                                            <div class="pull-left"><i class="fa fa-android"></i> </div>
                                            <div class="pull-right"> <span class="text">Available on the</span> <span class="text-2">Play store</span> </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="footer">
            <div class="container">
            <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <a href="#"> <img src="images/logo.png" alt="Footer logo"> </a>
                        <p>Order Delivery &amp; Take-Out</p>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <h5>Contact Us</h5>
                        <p>Phone: <a href="tel:+919978059125">+91 910 488 5040</a></p>
                        <p>Email: <a href="mailto:info@example.com">info@example.com</a></p>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <h5></h5>
                        <ul>
                            <li><a target="_blank" href="http://localhost/online-food-ordering-system-in-php-master/restaurant/">Add your restauarnat</a></li>
                            <li><a  target="_blank" href="http://localhost/online-food-ordering-system-in-php-master/delivery/index.php">delivery boy</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <h5>Follow Us</h5>
                        <ul>
                            <li><a href="#">Facebook</a></li>
                            <li><a href="#">Twitter</a></li>
                            <li><a href="#">Instagram</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>

</html>