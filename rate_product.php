<?php
session_start();
include("connection/connect.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die('You must be logged in to rate products.');
    }
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    if (empty($rating) || $rating < 1 || $rating > 5) {
        die('Invalid rating value. Rating must be between 1 and 5.');
    }
    $comment = htmlspecialchars($comment);
    $query = "INSERT INTO product_ratings (order_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("iiis", $order_id, $user_id, $rating, $comment);
        if ($stmt->execute()) {
            $_SESSION['rating_success'] = 'Your rating has been submitted successfully!';
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['rating_error'] = 'There was an error submitting your rating. Please try again later.';
            header('Location: index.php');
            exit();
        }
    } else {
        $_SESSION['rating_error'] = 'Failed to prepare the SQL statement.';
        header('Location: index.php');
        exit();
    }
}
?>
