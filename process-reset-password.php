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
    die("The token has expired. Please request a new password reset.");
} else {
    echo "The token is valid and hasn't expired.";
}
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
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
$sql_update = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE u_id = ?";
$stmt_update = $mysqli->prepare($sql_update);
$stmt_update->bind_param("ss", $password_hash, $user["u_id"]);
if ($stmt_update->execute()) {
    echo "Password has been successfully updated.";
    header("Location:ilogin.php");
} else {
    echo "Failed to update the password.";
}
$stmt->close();
$stmt_update->close();
$mysqli->close();

?>
