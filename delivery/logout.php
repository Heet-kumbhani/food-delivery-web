<?php
session_start();

// Destroy session variables
session_unset();
session_destroy();

// Delete the user_id cookie by setting its expiration time to a past date
setcookie("user_id", "", time() - 3600, "/");  // Cookie expired in the past

// Redirect to the login page
header("Location: login.php");
exit();
?>
