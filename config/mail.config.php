<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Mail:
class Mail {
    const SMTP_USER = 'phpmailer958@gmail.com';
    const SMTP_PWD = '1hdlaivhkenvk3';
    const PORT = 587;
    const HOST = 'smtp.gmail.com';

    public function sendAuthentication($user, $email) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->isHTML(true);
            $mail->Host = self::HOST;
            $mail->Port =self::PORT;
            $mail->Username = self::SMTP_USER;
            $mail->Password = self::SMTP_PWD;
            $mail->setFrom('noreply@dev.org', 'Address Book');
            $mail->Subject = 'New account authentication';
            $mail->Body = 'Hi '.$user.', your account has been created. Please authenticate below.';
            $mail->addAddress($email);
            $mail->send();
            return true;
        } catch(Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

}

    

