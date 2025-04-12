<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if the dish ID is provided in the URL for deleting
if (isset($_GET['dish_del'])) {
    $dish_id = $_GET['dish_del'];

    // Delete the dish from the database
    $delete_sql = "DELETE FROM dishes WHERE d_id = '$dish_id'";

    if (mysqli_query($db, $delete_sql)) {
        $success_message = "Dish deleted successfully!";
        header("Location: all_menu.php?msg=" . urlencode($success_message));  // Redirect to all menu page
        exit();
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    die("Dish ID is missing.");
}
?>
