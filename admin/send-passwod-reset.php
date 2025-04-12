<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$db = require __DIR__ . "/../connection/connect.php";

$sql = "UPDATE admin SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $db->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($db->affected_rows) {

    $mail = require __DIR__ . "/../mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://localhost/online-food-ordering-system-in-php-master/admin/reset-password.php?token=$token">here</a> 
    to reset your password.</n>
    END;

    try {

        $mail->send();
        echo "Message sent, please check your inbox.";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}
