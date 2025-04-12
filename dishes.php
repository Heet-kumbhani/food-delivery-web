<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php"); // connection to db
error_reporting(0);
session_start();

include_once 'product-action.php';


if (isset($_GET['action']) && $_GET['action'] == "add") {
    $productId = $_GET['id']; // Product ID
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Quantity

    // Fetch product details from the database
    $stmt = $db->prepare("SELECT * FROM dishes WHERE d_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!empty($product)) {
        // If product already exists in cart, update quantity
        if (isset($_SESSION['cart_item'][$productId])) {
            $_SESSION['cart_item'][$productId]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            // $_SESSION['cart_item'][$productId] = array(
            //     'title' => $product['title'],
            //     'price' => $product['price'],
            //     'quantity' => $quantity,
            //     'd_id' => $productId,
            // );
            $_SESSION["cart_item"][] = array(
                "d_id" => $product['d_id'],
                "title" => $product['d_name'],  // Ensure you use 'd_name' here
                "price" => $product['price'],
                "quantity" => $quantity
            );            
        }
    }

    // Redirect back to the same page to reflect updated cart
    header("Location: dishes.php?res_id=" . $_GET['res_id']);
    exit;
}

// Handle remove from cart functionality
if (isset($_GET['action']) && $_GET['action'] == "remove") {
    $productId = $_GET['id'];

    // Remove item from cart
    if (isset($_SESSION['cart_item'][$productId])) {
        unset($_SESSION['cart_item'][$productId]);
    }

    // Redirect back to the same page
    header("Location: dishes.php?res_id=" . $_GET['res_id']);
    exit;
}


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
    <link href="css/style.css" rel="stylesheet"> </head>
<body>
        <header id="header" class="header-scroll top-header headrom">
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.html"> <img class="img-rounded" src="images/logo.png" alt=""> </a>
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
                      
                        <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="restaurants.php">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item active"><span>2</span><a href="dishes.php?res_id=<?php echo $_GET['res_id']; ?>">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay online</a></li>
                    </ul>
                </div>
            </div>
			<?php $ress= mysqli_query($db,"select * from restaurant where rs_id='$_GET[res_id]'");
				$rows=mysqli_fetch_array($ress);
			?>
            <section class="inner-page-hero bg-image" data-image-src="images/img/res7.jpg">
                <div class="profile">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12  col-md-4 col-lg-4 profile-img">
                                <div class="image-wrap">
                                    <figure><?php echo '<img src="admin/Res_img/'.$rows['image'].'" alt="Restaurant logo">'; ?></figure>
                                </div>
                            </div>
							
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 profile-desc">
                                <div class="pull-left right-text white-txt">
                                    <!-- <input type="text" name="res_name" class="form-control" required> -->

                                    <h6><a href="#"><?php echo $rows['res_name'];?></a></h6>
                                    <p><?php echo $rows['address']; ?></p>
                                    <ul class="nav nav-inline">
                                        <li class="nav-item"> <a class="nav-link active" href="#"><i class="fa fa-check"></i> Min INR 100</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="#"><i class="fa fa-motorcycle"></i> 30 min</a> </li>
                                        <li class="nav-item ratings">
                                            <a class="nav-link" href="#"> <span>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    </span> </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="container m-t-30">
    <div class="row">
        <!-- Shopping Cart Section -->
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
            <div class="widget widget-cart">
                <div class="widget-heading">
                    <h3 class="widget-title text-dark">Your Shopping Cart</h3>
                    <div class="clearfix"></div>
                </div>
                <div class="order-row bg-white">
                    <div class="widget-body">
                        <?php
                        $item_total = 0;
                        if (isset($_SESSION["cart_item"])) {
                            foreach ($_SESSION["cart_item"] as $item) {
                        ?>
                                <div class="title-row">
                                    <?php echo $item["title"]; ?>
                                    <a href="dishes.php?res_id=<?php echo $_GET['res_id']; ?>&action=remove&id=<?php echo $item["d_id"]; ?>">
                                        <i class="fa fa-trash pull-right"></i>
                                    </a>
                                </div>
                                <div class="form-group row no-gutter">
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control b-r-0" value="INR <?php echo $item["price"]; ?>" readonly id="exampleSelect1">
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="form-control" type="text" readonly value="<?php echo $item["quantity"]; ?>" id="example-number-input">
                                    </div>
                                </div>
                        <?php
                                $item_total += ($item["price"] * $item["quantity"]);
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="widget-body">
                <div class="price-wrap text-xs-center">
    <p>TOTAL</p>
    <?php if ($item_total > 0): ?>
        <h3 class="value"><strong>INR <?php echo $item_total; ?></strong></h3>
        <p>Free Shipping</p>
        <a href="checkout.php?res_id=<?php echo $_GET['res_id']; ?>&action=check" class="btn theme-btn btn-lg">Checkout</a>
    <?php else: ?>
        <h3 class="value"><strong>INR 0</strong></h3>
        <p>Free Shipping</p>
        <a href="" class="btn theme-btn btn-lg disabled" disabled>Checkout</a>
    <?php endif; ?>
</div>

                </div>
            </div>
        </div>

        <!-- Popular Orders Section -->
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
            <div class="menu-widget" id="2">
                <div class="widget-heading">
                    <h3 class="widget-title text-dark">
                        POPULAR ORDERS Delicious hot food!
                        <a class="btn btn-link pull-right" data-toggle="collapse" href="#popular2" aria-expanded="true">
                            <i class="fa fa-angle-right pull-right"></i>
                            <i class="fa fa-angle-down pull-right"></i>
                        </a>
                    </h3>
                    <h4><?php echo $rows['res_name']; ?></h4>
                    <div class="clearfix"></div>
                </div>
                <div class="collapse in" id="popular2">
                <?php  
    // Fetch dishes from the database where status is 'active'
    $stmt = $db->prepare("SELECT * FROM dishes WHERE rs_id = ? AND status = 'active'");
    $stmt->bind_param("i", $_GET['res_id']);
    $stmt->execute();
    $products = $stmt->get_result();

    if ($products->num_rows > 0) {
        while ($product = $products->fetch_assoc()) {
?>
            <div class="food-item">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-lg-8">
                        <!-- Form to add dish to the cart -->
                        <form method="post" action="dishes.php?res_id=<?php echo $_GET['res_id']; ?>&action=add&id=<?php echo $product['d_id']; ?>">
                            <div class="rest-logo pull-left">
                                <a class="restaurant-logo pull-left" href="#">
                                    <?php echo '<img src="admin/Res_img/dishes/'.$product['img'].'" alt="Food logo">'; ?>
                                    <?php echo $_GET['res_name']; ?>
                                </a>
                            </div>
                            <div class="rest-descr">
                                <!-- Dish Name (Title) -->
                                <h6><a href="#"><?php echo $product['d_name']; ?></a></h6>
                                <p><?php echo $product['d_discription']; ?></p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info">
                            <span class="price pull-left">INR <?php echo $product['price']; ?></span>
                            <input class="b-r-0" type="number" name="quantity" style="margin-left:30px;" value="1" min="1" size="1" max='10' />
                            <input type="submit" class="btn theme-btn" style="margin-left:40px;" value="Add to cart" />
                        </div>
                    </form>
                </div>
            </div>
<?php
        }
    } else {
        echo "<p>No active dishes available!</p>";
    }
?>

                </div>
            </div>
        </div>
    </div>
</div>

            
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
                        <div class="col-xs-12 col-sm-4">
                            <a href="#"> <img src="images/logo.png" alt="Footer logo"> </a>
                            <p>Order Delivery &amp; Take-Out</p>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <h5>Contact Us</h5>
                            <p>Phone: <a href="tel:+919978059125">+91 910 488 5040</a></p>
                            <p>Email: <a href="mailto:info@example.com">info@example.com</a></p>
                        </div>
                        <div class="col-xs-12 col-sm-4">
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
    </div>
    <div class="modal fade" id="order-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <div class="modal-body cart-addon">
                    <div class="food-item white">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                            </div>
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 2.99</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect2">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-2"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                            </div>
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 2.49</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect3">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-3"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                            </div>
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 1.99</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect5">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-4"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="food-item">
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
                            <!-- <li><a href="#">Instagram</a></li> -->
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn theme-btn">Add to cart</button>
                </div>
            </div>
        </div>
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
