<?php
include("../connection/connect.php");

if (isset($_GET['d_id'])) {
    $d_id = $_GET['d_id'];

    // Fetch the current status
    $sql = "SELECT * FROM dishes WHERE d_id = '$d_id'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);

    // Toggle status (active to inactive, inactive to active)
    $new_status = ($row['status'] == 'active') ? 'inactive' : 'active';

    // Update status in the database
    $update_sql = "UPDATE dishes SET status = '$new_status' WHERE d_id = '$d_id'";

    if (mysqli_query($db, $update_sql)) {
        header("Location: all_menu.php?msg=Status updated successfully!");
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>
