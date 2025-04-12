<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Delete the cookies by setting their expiration time in the past
setcookie("adm_id", "", time() - 3600, "/"); // Expire the "adm_id" cookie

// Redirect the user to the login page or home page
header("Location: index.php");
exit();
?>
