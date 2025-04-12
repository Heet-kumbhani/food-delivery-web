<?php
if (isset($_POST['rs_id']) && isset($_POST['open_or_close'])) {
    $restaurantId = $_POST['rs_id'];
    $openOrCloseStatus = $_POST['open_or_close'];

    // Assuming you have a database connection already
    // Update the status in the database for the specific restaurant
    $query = "UPDATE restaurants SET open_or_close = ? WHERE rs_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $openOrCloseStatus, $restaurantId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}
?>
