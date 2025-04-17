<?php

session_start();
error_reporting(0);
include("connection/connect.php");

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);
    $address = trim($_POST['address']);
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($password) || empty($cpassword) || empty($address)) {
        $message = "All fields must be filled!";
    } else {
        $check_username = mysqli_query($db, "SELECT username FROM users WHERE username = '" . mysqli_real_escape_string($db, $username) . "'");
        $check_email = mysqli_query($db, "SELECT email FROM users WHERE email = '" . mysqli_real_escape_string($db, $email) . "'");

        if ($password != $cpassword) {
            $message = "Passwords do not match!";
        } elseif (strlen($password) < 6) {
            $message = "Password must be at least 6 characters!";
        } elseif (strlen($phone) < 10) {
            $message = "Invalid phone number!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email address!";
        } elseif (mysqli_num_rows($check_email) > 0) {
            $message = "Email already exists!";
        } elseif (mysqli_num_rows($check_username) > 0) {
            $message = "Username already exists!";
        } else {
            $mql = "INSERT INTO users(username, f_name, l_name, email, phone, password, address) 
                    VALUES('" . mysqli_real_escape_string($db, $username) . "',
                           '" . mysqli_real_escape_string($db, $firstname) . "',
                           '" . mysqli_real_escape_string($db, $lastname) . "',
                           '" . mysqli_real_escape_string($db, $email) . "',
                           '" . mysqli_real_escape_string($db, $phone) . "',
                           '" . md5($password) . "',
                           '" . mysqli_real_escape_string($db, $address) . "')";

            if (mysqli_query($db, $mql)) {
                $success = "Account created successfully! <p>You will be redirected in <span id='counter'>5</span> second(s).</p>
                            <script type='text/javascript'>
                            function countdown() {
                                var i = document.getElementById('counter');
                                if (parseInt(i.innerHTML) <= 0) {
                                    location.href = 'login.php';
                                }
                                i.innerHTML = parseInt(i.innerHTML) - 1;
                            }
                            setInterval(function(){ countdown(); }, 100);
                            </script>";
                header("refresh:5;url=login.php");
            } else {
                $message = "Error creating account: " . mysqli_error($db);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title>Tomato</title>
    <script>
      function validatePhoneNumber() {
        var phone = document.getElementById('phone').value;
        var regex = /^(?!.*00)[0-9]{10}$/;

        if (!regex.test(phone)) {
          alert("Phone number cannot contain consecutive zeros and must be 10 digits long.");
          return false;
        }
        return true;
      }
    </script>
</head>
<body>
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/logo.png" alt=""> </a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="restaurants.php">Restaurants</a></li>
                        <?php
                            if(empty($_SESSION["user_id"])) {
                                echo '<li class="nav-item"><a href="login.php" class="nav-link active">login</a></li>
                                      <li class="nav-item"><a href="registration.php" class="nav-link active">signup</a></li>';
                            } else {
                                echo  '<li class="nav-item"><a href="your_orders.php" class="nav-link active">your orders</a></li>';
                                echo  '<li class="nav-item"><a href="logout.php" class="nav-link active">logout</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-wrapper">
        <div class="breadcrumb">
            <div class="container">
                <ul>
                    <li><a href="#" class="active">
                        <span style="color:red;"><?php echo $message; ?></span>
                        <span style="color:green;"><?php echo $success; ?></span>
                    </a></li>
                </ul>
            </div>
        </div>

        <section class="contact-page inner-page">
            <div class="container">
                <div class="row">
                    <div class="justify-content-center">
                        <div class="widget">
                            <div class="widget-body">
                                <form action="" method="post" onsubmit="return validatePhoneNumber()">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="username">User-Name *</label>
                                            <input class="form-control" type="text" name="username" id="username" value="<?php echo $username; ?>" placeholder="UserName">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="firstname">First Name *</label>
                                            <input class="form-control" type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>" placeholder="First Name">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="lastname">Last Name *</label>
                                            <input class="form-control" type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>" placeholder="Last Name">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="email">Email address *</label>
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $email; ?>" placeholder="Enter email">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="phone">Phone number *</label>
                                            <input class="form-control" type="text" name="phone" id="phone" value="<?php echo $phone; ?>" placeholder="Phone" minlength="10" maxlength="10">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="password">Password *</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="cpassword">Repeat password *</label>
                                            <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Password">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="address">Delivery Address *</label>
                                            <textarea class="form-control" name="address" id="address" rows="2"><?php echo $address; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <p> <input type="submit" value="Register" name="submit" class="btn theme-btn"> </p>
                                        </div>
                                        <div class="col-sm-1">
                                            <p><a href="login.php" class="btn theme-btn">login</a></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
