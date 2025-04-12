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
include("connection/connect.php"); //INCLUDE CONNECTION
error_reporting(0); // hide undefined index errors
session_start(); // start session

?>
  
<!-- Form Mixin-->
<!-- Input Mixin-->
<!-- Button Mixin-->
<!-- Pen Title-->
<div class="pen-title">
  <h1>Forgot Password</h1>
</div>
<!-- Form Module-->
<div class="module form-module">
  <div class="toggle"></div>
  <div class="form">
    <form method="post" action="send-password-reset.php">
      <input type="email" name="email" id="email" placeholder="email *">
      <button>send</button>
    </form>
  </div>
  
  <div class="cta">Not registered?
    <a href="registration.php" style="color:#f30;"> Create an account</a>
    <p class="button"><a href="login.php" id="ForPasss">Login?</a></p>
  </div>
</div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

</body>
</html>