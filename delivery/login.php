<?php
include("../connection/connect.php");
session_start();
error_reporting(E_ALL); // Show all errors for debugging

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Query to check if the email exists in the database
    $query = "SELECT * FROM delivery_boys WHERE email='$email'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the user's data
        $user = mysqli_fetch_assoc($result);

        // Check if the user's status is active
        if ($user['status'] == 'active') {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables and redirect
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['phone_no'] = $user['phone_no'];
                $_SESSION['address'] = $user['address'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['status'] = $user['status'];

                // Set a cookie for the user_id with a 24-hour expiration time
                setcookie("delivery_id", $user['id'], time() + 86400, "/"); // Cookie expires in 24 hours

                // Redirect to the dashboard or any other page after successful login
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            // User status is not active
            $error = "Your account is not active. Please contact support.";
        }
    } else {
        $error = "No account found with that email address!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Delivery Boy</title>
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<style type="text/css">
      #buttn {
        color: #fff;
        background-color: #ff3300;
      }
      #ForPasss {
        color: #f30;
      }
    </style>

<body>

<div class="pen-title" >
    <h1>Delivery Boy Login</h1>
</div>
  <div class="module form-module">
  <div class="toggle"></div>
  <div class="form">
    <h2>Login to your Registration </h2>
      <form action="" method="POST">
          <div class="form-body">
              <!-- Email field -->
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <!-- <label>Email Address *</label> -->
                          <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                      </div>
                  </div>
              </div>

              <!-- Password field -->
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <!-- <label>Password *</label> -->
                          <input type="password" name="password" class="form-control" placeholder="Password" required>
                      </div>
                  </div>
              </div>

              <!-- Error message display -->
              <?php if (isset($error)) { ?>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="alert alert-danger text-center">
                              <?php echo $error; ?>
                          </div>
                      </div>
                  </div>
              <?php } ?>

              <!-- Login button -->
              <div class="form-actions text-center">
                  <button type="submit" class="btn btn-primary">Login</button>
              </div>
          </div>
        </form>
    </div>
    <div class="cta">Not registered?
<a href="index.php" style="color:#f30;"> Create an account</a>
</div>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

</body>
</html>
