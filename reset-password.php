<?php
if (!isset($_GET["token"])) {
    die("No token provided.");
}
$token = $_GET["token"];
$token_hash = hash("sha256", $token);
$mysqli = require __DIR__ . "/connection/connect.php";
$sql = "SELECT * FROM users WHERE reset_token = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if ($user === null) {
    die("Token not found or is invalid.");
}
$expiry_time = strtotime($user["reset_token_expires"]);
$current_time = time();
if ($expiry_time <= $current_time) {
    echo "The token has expired. Please request a new password reset.";
} else {
    echo "The token is valid and hasn't expired.";
}
$stmt->close();
$mysqli->close();
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