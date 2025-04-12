<?php
include("../connection/connect.php");
session_start();

// Check if necessary data is provided
if (isset($_POST['restaurant_id']) && isset($_POST['open_or_close'])) {
    $restaurant_id = $_POST['restaurant_id'];
    $open_or_close = $_POST['open_or_close']; // new status: 'open' or 'close'

    // Ensure the restaurant_id exists in the session
    if ($_SESSION['restaurant_id'] === $restaurant_id) {
        
        // Update the open_or_close status in the database for the specific restaurant
        $query = "UPDATE restaurant 
                  SET open_or_close = '$open_or_close' 
                  WHERE rs_id = '$restaurant_id'";

        if (mysqli_query($db, $query)) {
            echo 'success'; // Return success if the update was successful
        } else {
            echo 'error'; // Return error if the query failed
        }
    } else {
        echo 'invalid_session'; // Return an error if session and restaurant_id don't match
    }
} else {
    echo 'missing_data'; // Return error if data is missing
}
?>
