<?php
session_start();
include("../connection/connect.php");
error_reporting(0);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Query to check if restaurant exists with the provided email and status is active
    $query = "SELECT * FROM restaurant WHERE email = '$email' AND status = 'active' LIMIT 1";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['restaurant_id'] = $row['rs_id'];  // Store rs_id in session
            $_SESSION['restaurant_name'] = $row['res_name'];
            $_SESSION['restaurant_email'] = $row['email'];
            // Set session expiration time (1 hour from now)
            $_SESSION['expire_time'] = time() + 36000;  // 1 hour = 3600 seconds

            // Set a cookie for restaurant_id for 1 hour (optional)
            setcookie('res_id', $row['rs_id'], time() + 8226400, '/');  // Set cookie to store rs_id for 24 hour

            // Redirect to restaurant dashboard
            $update_query = "UPDATE restaurant SET open_or_close = 'open' WHERE open_or_close = 'close'";
            if (mysqli_query($db, $update_query)) {
            } else {
                // Handle query error
                echo "Error updating record: " . mysqli_error($db);
            }
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No restaurant found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="../css/login.css">
    <style type="text/css">
      #buttn {
        color: #fff;
        background-color: #ff3300;
      }
      #ForPasss {
        color: #f30;
      }
    </style>
</head>
<body>

  <!-- Form Mixin-->
  <div class="pen-title">
    <h1>Restaurant Login Form</h1>
  </div>
  <div class="module form-module">
    <div class="toggle"></div>
    <div class="form">
      <h2>Login to your Registration</h2>
      <!-- Display Error Message -->
      <?php if (isset($error_message)): ?>
          <div class="alert alert-danger"><?php echo $error_message; ?></div>
      <?php endif; ?>
      
      <form action="" method="POST">
        <input type="email" placeholder="Email *" name="email" required />
        <input type="password" placeholder="Password *" name="password" required />
        <input type="submit" id="buttn" name="submit" value="Login" />
      </form>
      <!-- <p class="message"><a href="forgot_password.php" id="ForPasss">Forgot Password?</a></p> -->
    </div>

    <div class="cta">Not registered?
      <a href="index.php" style="color:#f30;"> Create an account</a>
    </div>
  </div>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>
