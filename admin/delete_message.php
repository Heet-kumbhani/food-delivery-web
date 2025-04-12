<?php
include("../connection/connect.php");

// Check if the form is submitted and the message_id is set
if (isset($_POST['delete_message']) && isset($_POST['message_id'])) {
    // Sanitize the message_id to avoid SQL injection
    $message_id = mysqli_real_escape_string($db, $_POST['message_id']);

    // Delete the message from the database
    $sql_delete = "DELETE FROM restaurant_messages WHERE id = '$message_id'";

    if (mysqli_query($db, $sql_delete)) {
        // Redirect back to the message page with success message
        header("Location: dashboard.php");
    } else {
        // Error in deleting the message
        echo '<script>alert("Failed to delete the message. Please try again later."); window.location.href = "your_message_page.php";</script>';
    }
}
?>
