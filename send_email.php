<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function sendApprovalEmail($toEmail, $firstName)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use Gmail's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'carsaction1@gmail.com'; // Replace with your Gmail email address
        $mail->Password = 'xthb nuvq ctcb yhql'; // Use the generated app password
        $mail->SMTPSecure = 'tls'; // Use 'tls' instead of 'ssl'
        $mail->Port = 587; // Use port 587 for TLS

        //Recipients
        $mail->setFrom('carsaction1@gmail.com', 'CARsaction Admin'); // Replace with your email and name
        $mail->addAddress($toEmail, $firstName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Approved';
        $mail->Body = 'Dear ' . $firstName . ',<br>Your account on CARsaction has been approved by admin. You can now log in and access your account.<br>Thank you for joining CARsaction!';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
