<?php
include("../connection/connect.php");
session_start();

// Check if the message form is submitted
if (isset($_POST['send_message'])) {
    // Get the message from the form
    $message = mysqli_real_escape_string($db, $_POST['message']);  // Sanitize the message

    // Get the current timestamp for created_at
    $created_at = date("Y-m-d H:i:s");

    // Check if message is not empty
    if (!empty($message)) {
        // Insert the message into the restaurant_messages table
        $sql_insert = "INSERT INTO restaurant_messages (res_message, status, created_at) 
                       VALUES ('$message', 'active', '$created_at')";

        if (mysqli_query($db, $sql_insert)) {
            // Message successfully saved
            echo '<script>alert("Your message has been sent successfully!");</script>';
            header("Location: dashboard.php");
        } else {
            // Error inserting message
            echo '<script>alert("Failed to send the message. Please try again later.");</script>';
        }
    } else {
        // Validation error: message is empty
        echo '<script>alert("Please provide a message.");</script>';
    }
}
?>
