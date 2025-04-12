<?php
include("../connection/connect.php");
error_reporting(0);

// Check if the form is submitted with the correct button click
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the order ID from the hidden field
    $order_id = $_POST['order_id'];

    // Check which button was clicked and update the status
    if (isset($_POST['dispatch'])) {
        $status = 'in process';
    } elseif (isset($_POST['delivered'])) {
        $status = 'closed';
    } elseif (isset($_POST['cancelled'])) {
        $status = 'rejected';
    } elseif (isset($_POST['rejected'])) {
        $status = 'rejected';
    } else {
        $status = 'NULL'; // Default status if no button was clicked
    }

    // Prepare the SQL query to update the status of the order
    $sql_update_status = "UPDATE users_orders SET status = ? WHERE o_id = ?";

    if ($stmt = mysqli_prepare($db, $sql_update_status)) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the orders page after successful update
            header("Location: all_orders.php");
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($db);
        }
    } else {
        echo "Error: Could not prepare the update query.";
    }
}
?>
