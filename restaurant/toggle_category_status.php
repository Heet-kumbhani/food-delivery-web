<?php
include("../connection/connect.php");

if (isset($_POST['toggle_status'])) {
    $dish_id = $_POST['dish_id'];
    $current_status = $_POST['current_status'];

    // Toggle the status (active to inactive, or inactive to active)
    $new_status = ($current_status == 'inactive') ? 'active' : 'inactive';

    // Update the dish status in the database
    $update_sql = "UPDATE dishes SET status = '$new_status' WHERE d_id = '$dish_id'";

    if (mysqli_query($db, $update_sql)) {
        // Redirect back to the dishes page with a success message
        header("Location: all_dishes.php?msg=Status updated successfully!");
    } else {
        echo "Error: " . mysqli_error($db);
    }
}

?>
