<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>login</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
  <link rel="stylesheet" href="css/login.css">
 
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
<?php
include("connection/connect.php"); // Include connection
error_reporting(0); // Hide undefined index errors
session_start(); // Start session

if(isset($_POST['submit'])) {  // If the submit button is pressed
    $email = $_POST['email'];  // Fetch email from form
    $password = $_POST['password']; // Fetch password
    
    if(!empty($_POST["submit"])) {   // If records are not empty
        // Select matching records and check for user status
        $loginquery = "SELECT * FROM users WHERE email='$email' && password='".md5($password)."'";
        $result = mysqli_query($db, $loginquery); // Execute query
        $row = mysqli_fetch_array($result);
        
        if(is_array($row)) {  // If matching records are found
            // Check if the user is active
            if($row['status'] == 'active') {
                $_SESSION["user_id"] = $row['u_id']; // Store user ID in session
                // Set the cookie for 1 day (86400 seconds)
                setcookie('u_id', $row['u_id'], time() + 86400, "/");
                header("refresh:1;url=index.php"); // Redirect to index.php page
            } else {
                // If user status is inactive
                $message = "Your account is inactive. Please contact support."; 
            }
        } else {
            // If no matching records found
            $message = "Invalid Email or Password!"; // Show error message
        }
    }
}
?>

  
<!-- Form Mixin-->
<!-- Input Mixin-->
<!-- Button Mixin-->
<!-- Pen Title-->
<div class="pen-title">
  <h1>Login Form</h1>
</div>
<!-- Form Module-->
<div class="module form-module">
  <div class="toggle"></div>
  <div class="form">
    <h2>Login to your account</h2>
    <span style="color:red;"><?php echo $message; ?></span> 
    <span style="color:green;"><?php echo $success; ?></span>
    <form action="" method="post">
      <input type="email" placeholder="Email *" name="email" required />
      <input type="password" placeholder="Password *" name="password" required />
      <input type="submit" id="buttn" name="submit" value="Login" />
    </form>
    <p class="message"><a href="forgot_password.php" id="ForPasss">Forgot Password?</a></p>
  </div>
  
  <div class="cta">Not registered?
    <a href="registration.php" style="color:#f30;"> Create an account</a>
  </div>
</div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

</body>
</html>