<?php
session_start();
include("../connection/connect.php");

// Query to update the 'open_or_close' field to 'close' for all restaurants
$update_query = "UPDATE restaurant SET open_or_close = 'close' WHERE open_or_close = 'open'";

if (mysqli_query($db, $update_query)) {

} else {
    // Handle query error
    echo "Error updating record: " . mysqli_error($db);
}

// Destroy the session and remove the restaurant_id cookie
setcookie('restaurant_id', '', time() - 3600, '/');  // Expire the cookie by setting its time in the past

// Destroy the session data
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

// Redirect to login page or home page
header("Location: login.php");  // Change this to wherever you want to redirect after logout
exit();
?>
