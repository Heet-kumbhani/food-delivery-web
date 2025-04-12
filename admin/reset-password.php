<?php

// Check if token is provided in the URL
if (!isset($_GET["token"])) {
    die("No token provided.");
}

// Retrieve the token from the URL
$token = $_GET["token"];

// Hash the token using SHA-256 for comparison in the database (same way as we stored it)
$token_hash = hash("sha256", $token);

// Include the database connection file
$db = require __DIR__ . "/../connection/connect.php";  // Correct the path here

// Prepare the SQL query to fetch the user with the given token
$sql = "SELECT * FROM admin WHERE reset_token_hash = ?";

// Prepare the statement
$stmt = $db->prepare($sql);

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
$expiry_time = strtotime($user["reset_token_expires_at"]);
$current_time = time();

// Check if the token has expired
if ($expiry_time <= $current_time) {
    // Token has expired
    echo "The token has expired. Please request a new password reset.";
} else {
    echo "The token is valid and hasn't expired.";
}

// Close the statement and the database connection
$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

    <h1>Reset Password</h1>

    <form method="post" action="process-reset-password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <button>Send</button>
    </form>

</body>
</html>
