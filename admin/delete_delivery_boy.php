<?php
include("../connection/connect.php");
session_start();

// Check if the delete parameter is passed
if (isset($_GET['order_del'])) {
    // Get the delivery boy ID from the URL
    $delivery_boy_id = $_GET['order_del'];

    // Sanitize the input to prevent SQL injection
    $delivery_boy_id = mysqli_real_escape_string($db, $delivery_boy_id);

    // SQL query to delete the delivery boy
    $sql = "DELETE FROM delivery_boys WHERE id = '$delivery_boy_id'";

    if (mysqli_query($db, $sql)) {
        // Delivery boy deleted successfully, redirect to the all delivery boys page
        header("Location: delivery_boy.php?message=Delivery boy deleted successfully.");
        exit();
    } else {
        // Error while deleting
        echo "Error deleting delivery boy: " . mysqli_error($db);
    }
} else {
    // If no delivery boy ID is passed, redirect to the delivery boy page with an error message
    header("Location: delivery_boy.php?error=No delivery boy ID provided.");
    exit();
}
?>
