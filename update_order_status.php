<?php
include("connection/connect.php");
session_start();

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $status = 'in process'; // Default status for dispatch

    if (isset($_POST['dispatch'])) {
        $status = 'in process';
    }

    // Update order status in users_orders
    $update_sql = "UPDATE users_orders SET status = ? WHERE o_id = ?";
    
    if ($stmt = mysqli_prepare($db, $update_sql)) {
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the page after status is updated
            header("Location: user_orders.php");
            exit;
        } else {
            echo "Error: Could not update the order status.";
        }
    } else {
        echo "Error: Could not prepare the update query.";
    }
}
?>
