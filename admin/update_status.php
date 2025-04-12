<?php
include("../connection/connect.php");
error_reporting(E_ALL);  // Enable error reporting for debugging
session_start();

// Ensure the user is logged in (if required by your system)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Check if the 'update_status' parameter is set in the URL
if (isset($_GET['update_status'])) {
    $id = $_GET['update_status'];

    // Fetch the current value of 'is_delivery_boy' for the specific delivery boy
    $check_status_sql = "SELECT is_delivery_boy FROM delivery_boys WHERE id = '$id'";
    $result = mysqli_query($db, $check_status_sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $current_status = $row['is_delivery_boy'];

        // Toggle the status (from active to inactive or vice versa)
        $new_status = ($current_status == 'active') ? 'inactive' : 'active';

        // Update the status in the database
        $update_status_sql = "UPDATE delivery_boys SET is_delivery_boy = '$new_status' WHERE id = '$id'";

        if (mysqli_query($db, $update_status_sql)) {
            // Redirect back to the page after successfully updating the status
            header("Location: delivery_boy.php"); // Replace with your page where you list the delivery boys
            exit();
        } else {
            // Handle the error if the update fails
            echo "Error updating status: " . mysqli_error($db);
        }
    } else {
        // Handle error if fetching status failed
        echo "Error fetching current status: " . mysqli_error($db);
    }
} else {
    // If the 'update_status' parameter is not present in the URL, redirect to the list page
    header("Location: delivery_boy.php"); // Replace with your page where you list the delivery boys
    exit();
}
?>
