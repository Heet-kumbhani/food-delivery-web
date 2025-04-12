<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$db = require __DIR__ . "/../connection/connect.php";

$sql = "SELECT * FROM admin
        WHERE reset_token_hash = ?";

$stmt = $db->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if (strlen($_POST["password"]) < 6) {
    die("Password must be at least 6 characters");
}

// if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
//     die("Password must contain at least one letter");
// }

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE admin
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE adm_id = ?";

$stmt = $db->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["adm_id"]);

$stmt->execute();

echo 'Password updated. You can now login. <a href="http://localhost/online-food-ordering-system-in-php-master/admin/index.php">login</a>';