<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();

$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'heetp821@gmail.com';  // Your Gmail address
$mail->Password = 'awplosspllmaoubh';     // App Password (not your regular Gmail password)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Or PHPMailer::ENCRYPTION_SMTPS for SSL
$mail->Port = 587;  // 587 for STARTTLS, or 465 for SSL


$mail->isHtml(true);

return $mail;
?>