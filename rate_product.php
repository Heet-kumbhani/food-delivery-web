<?php
// Start the session to access session variables
session_start();

// Include database connection
include("connection/connect.php");

// Check if the user is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify if the user is logged in by checking the session
    if (!isset($_SESSION['user_id'])) {
        die('You must be logged in to rate products.');
    }

    // Get the data from POST request
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id']; // User ID from the session
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Validate the rating input
    if (empty($rating) || $rating < 1 || $rating > 5) {
        die('Invalid rating value. Rating must be between 1 and 5.');
    }

    // Optional: Sanitize the comment input to prevent XSS attacks
    $comment = htmlspecialchars($comment);

    // Insert the rating into the database using prepared statements
    $query = "INSERT INTO product_ratings (order_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("iiis", $order_id, $user_id, $rating, $comment);

        // Execute the query and check for success
        if ($stmt->execute()) {
            // If the query is successful, set a success message in session
            $_SESSION['rating_success'] = 'Your rating has been submitted successfully!';
            header('Location: index.php'); // Redirect after success
            exit();
        } else {
            // If there is an error with the query
            $_SESSION['rating_error'] = 'There was an error submitting your rating. Please try again later.';
            header('Location: index.php');
            exit();
        }
    } else {
        // If the prepare statement failed
        $_SESSION['rating_error'] = 'Failed to prepare the SQL statement.';
        header('Location: index.php');
        exit();
    }
}
?>
