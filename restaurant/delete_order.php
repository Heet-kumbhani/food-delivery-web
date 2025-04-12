<?php
include("../connection/connect.php");

if (isset($_GET['o_id'])) {
    $order_id = $_GET['o_id'];

    // Delete the order from the database
    $delete_sql = "DELETE FROM orders WHERE o_id = '$order_id'";
    if (mysqli_query($db, $delete_sql)) {
        header("Location: show_orders.php?msg=Order deleted successfully");
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>
