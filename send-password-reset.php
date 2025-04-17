<?php

$email = $_POST["email"];
$token = random_bytes(16);
$tokenHex = bin2hex($token);
$token_hash_sha256 = hash("sha256", $tokenHex);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);
$db = require __DIR__ . "/connection/connect.php";
$sql = "UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("sss", $token_hash_sha256, $expiry, $email);
$stmt->execute();

if ($db->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $resetUrl = "http://localhost/online-food-ordering-system-in-php-master/reset-password.php?token=$token_hash_sha256"; 
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
