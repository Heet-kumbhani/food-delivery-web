<?php

$email = $_POST["email"];

// Generate a random token as binary data (16 bytes will give a 32-character hexadecimal string)
$token = random_bytes(16);  // Increased the size to ensure better uniqueness

// Convert the binary data to hexadecimal representation (this makes it readable and easier to work with)
$tokenHex = bin2hex($token);

// Hash the token string using SHA-256 (not password_hash)
$token_hash_sha256 = hash("sha256", $tokenHex);  // Using SHA-256 for consistency

// Set the expiration time to 30 minutes from the current time (not 1 hour)
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);  // 30 minutes expiration

// Include the database connection file
$db = require __DIR__ . "/connection/connect.php";

// Prepare the SQL query to update both the reset token and the reset token expiration time in the database
$sql = "UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?";

// Prepare the statement
$stmt = $db->prepare($sql);

// Bind the parameters to the prepared statement
$stmt->bind_param("sss", $token_hash_sha256, $expiry, $email);

// Execute the prepared statement
$stmt->execute();

if ($db->affected_rows) {
    // Send the reset email
    $mail = require __DIR__ . "/mailer.php";
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";

    // Send the hashed token in the URL, not the plain token
    $resetUrl = "http://localhost/online-food-ordering-system-in-php-master/reset-password.php?token=$token_hash_sha256";  // Send hashed token

    $mail->Body = <<<END
    <p>Click <a href="$resetUrl">here</a> to reset your password.</p>
    END;

    try {
        $mail->send();
        echo "Message sent, please check your inbox.";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}

?>
