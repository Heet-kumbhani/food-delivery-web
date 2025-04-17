<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
$buttonHidden = isset($_SESSION['button_hidden']) && $_SESSION['button_hidden'] === true;
if (empty($_SESSION['user_id'])) { 
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id']; 

$query_res = mysqli_query($db, "SELECT u_orders.o_id, u_orders.title, u_orders.quantity, u_orders.price, u_orders.date, 
                COALESCE(r.status, 'pending') AS remark_status, u_orders.order_status 
                FROM users_orders u_orders
                LEFT JOIN remark r ON r.o_id = u_orders.o_id
                WHERE u_orders.u_id = '$user_id' 
                ORDER BY u_orders.date DESC");
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Tomato </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css" rel="stylesheet">
        table {
            width: 750px;
            border-collapse: collapse;
            margin: auto;
        }

        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #ff3300;
            color: white;
            font-weight: bold;
        }

        td, th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 14px;
        }

        @media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {
            table {
                width: 100%;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #ccc;
            }

            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                position: absolute;
                top: 6px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                content: attr(data-column);
                color: #000;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
<script>
function openRateModal(orderId) {
    document.getElementById('order_id').value = orderId;
    $('#rateProductModal').modal('show');
}
</script>
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.html"><img class="img-rounded" src="images/logo.png" alt=""></a>
                <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="restaurants.php">Restaurants</a></li>
                        <?php
                        if (empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">login</a></li>';
                            echo '<li class="nav-item"><a href="registration.php" class="nav-link active">signup</a></li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">your orders</a></li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link active">logout</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-wrapper">
        <div class="inner-page-hero bg-image" data-image-src="images/img/res3.jpeg">
            <div class="container"></div>
        </div>

        <div class="result-show">
            <div class="container">
                <div class="row">
                </div>
            </div>
        </div>

        <section class="restaurants-page">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-7 col-md-7">
                        <div class="bg-gray restaurant-entry">
                            <div class="row">
                            <table>
    <thead>
        <tr>
            <th>Item name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
            <th>Order Status some message</th>
        </tr>
    </thead>
    <tbody>
            <?php
            if (mysqli_num_rows($query_res) == 0) {
                echo '<tr><td colspan="7" class="text-center">You have no orders placed yet.</td></tr>';
                echo '<tr><td colspan="7" class="text-center"><a href="restaurants.php" class="btn btn-primary">Order Now</a></td></tr>';
            } else {
                while ($row = mysqli_fetch_array($query_res)) {
                    ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>INR <?php echo $row['price']; ?></td>
                        <td>
                            <?php
                            $status = $row['remark_status'];
                            if ($status == "in process") {
                                echo '<button class="btn btn-warning">On the Way!</button>';
                            } elseif ($status == "closed") {
                                echo '<button class="btn btn-success">Delivered</button>';
                            } elseif ($status == "rejected") {
                                echo '<button class="btn btn-danger">Cancelled</button>';
                            } else {
                                echo '<button class="btn btn-info">Dispatch</button>';
                            }
                            ?>
                        </td>
                        <td><?php echo $row['date']; ?></td>
                        <td>
                            <?php if ($row['order_status'] == "accepted") { ?>
                                <button class="btn btn-primary" onclick="openRateModal(<?php echo $row['o_id']; ?>)">Rate Product</button>
                            <?php } else { ?>
                                <a href="delete_orders.php?order_del=<?php echo $row['o_id']; ?>" 
                                   onclick="return confirm('Are you sure you want to cancel your order?');" 
                                   class="btn btn-danger">Cancel</a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            if ($row['order_status'] == "accepted") {
                                echo '<span class="badge badge-success">Accepted</span>';
                            } elseif ($row['order_status'] == "rejected") {
                                echo '<span class="badge badge-danger">Rejected</span>';
                            } else {
                                echo '<span class="badge badge-secondary">Pending</span>';
                            }
                            ?>
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
        </section>

        <section class="app-section">
            <div class="app-wrap">
                <div class="container">
                    <div class="row text-img-block text-xs-left">
                        <div class="container">
                            <div class="col-xs-12 col-sm-6 hidden-xs-down right-image text-center">
                                <figure><img src="images/app.png" alt="Right Image"></figure>
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
        <div class="modal fade" id="rateProductModal" tabindex="-1" role="dialog" aria-labelledby="rateProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rateProductModalLabel">Rate Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="rate_product.php">
          <input type="hidden" id="order_id" name="order_id" value="">
          <div class="form-group">
            <label for="rating">Rating</label>
            <select id="rating" name="rating" class="form-control">
              <option value="1">1 - Poor</option>
              <option value="2">2 - Fair</option>
              <option value="3">3 - Good</option>
              <option value="4">4 - Very Good</option>
              <option value="5">5 - Excellent</option>
            </select>
          </div>
          <div class="form-group">
            <label for="comment">Comment</label>
            <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Submit Rating</button>
        </form>
      </div>
    </div>
  </div>
</div>

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
