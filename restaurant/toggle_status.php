<?php
include("../connection/connect.php");

if (isset($_POST['restaurant_id']) && isset($_POST['status'])) {
    $restaurantId = $_POST['restaurant_id'];
    $status = $_POST['status'];

    // Update the restaurant status in the database
    $sql = "UPDATE restaurant SET status = ? WHERE rs_id = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $restaurantId);

    if (mysqli_stmt_execute($stmt)) {
        echo 'success'; // Respond back if the status was updated successfully
    } else {
        echo 'failure'; // Respond back if there was an error
    }

    mysqli_stmt_close($stmt);
}
?>
