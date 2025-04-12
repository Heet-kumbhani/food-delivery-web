<?php

// Check if token is provided in the URL (GET request)
if (!isset($_GET["token"])) {
    die("No token provided.");
}

// Retrieve the token from the URL (GET request)
$token = $_GET["token"];

// Hash the token using SHA-256 for comparison in the database (same way as we stored it)
$token_hash = hash("sha256", $token);

// Include the database connection file
$mysqli = require __DIR__ . "/connection/connect.php";

// Prepare the SQL query to fetch the user with the given token
$sql = "SELECT * FROM users WHERE reset_token = ?";

// Prepare the statement
$stmt = $mysqli->prepare($sql);

// Bind the token to the query
$stmt->bind_param("s", $token_hash);

// Execute the query
$stmt->execute();

// Get the result of the query
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user was found
if ($user === null) {
    die("Token not found or is invalid.");
}

// Debugging: Check the token expiry time from the database
$expiry_time = strtotime($user["reset_token_expires"]);
$current_time = time();

// Check if the token has expired
if ($expiry_time <= $current_time) {
    // Token has expired
    die("The token has expired. Please request a new password reset.");
} else {
    echo "The token is valid and hasn't expired.";
}

// Check if the new password meets the requirements
if (strlen($_POST["password"]) < 6) {
    die("Password must be at least 6 characters.");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter.");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number.");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match.");
}

// Hash the new password
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Update the password and reset the token in the database
$sql_update = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE u_id = ?";

// Prepare the statement for updating the password
$stmt_update = $mysqli->prepare($sql_update);

// Bind parameters to the statement
$stmt_update->bind_param("ss", $password_hash, $user["u_id"]);

// Execute the update
if ($stmt_update->execute()) {
    echo "Password has been successfully updated.";
    header("Location:ilogin.php");
} else {
    echo "Failed to update the password.";
}

// Close the statements and the database connection
$stmt->close();
$stmt_update->close();
$mysqli->close();

?>
