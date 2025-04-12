<?php
session_start();
include("../connection/connect.php");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($db, $_POST['status']);

    // Only allow accepted or rejected for security
    if (!in_array($new_status, ['accepted', 'rejected'])) {
        die("Invalid status value.");
    }

    // Update order status directly in users_orders table
    $query = "UPDATE users_orders SET order_status = '$new_status' WHERE o_id = $order_id";
    if (mysqli_query($db, $query)) {
        // Optionally add a record to the remarks table if needed
        /*
        $date = date('Y-m-d H:i:s');
        $remark_insert = "INSERT INTO remark (o_id, status, remarkDate) VALUES ($order_id, '$new_status', '$date')";
        mysqli_query($db, $remark_insert);
        */

        header("Location: all_orderss.php");
        exit();
    } else {
        echo "Error updating order: " . mysqli_error($db);
    }
} else {
    echo "Invalid request method.";
}
?>