<?php

var_dump(10.00/1.1);
var_dump(round(10.00/1.1, 2));
exit;
//phpinfo();exit;

error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once 'dscore/libs/phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.bankrot-spy.ru';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'no-reply@bankrot-spy.ru';                 // SMTP username
$mail->Password = 'C7q3H3u9';                           // SMTP password
$mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to
$mail->CharSet = 'UTF-8';
$mail->setFrom('no-reply@bankrot-spy.ru', 'Bankrot-Spy');

$mail->addAddress('imbagroup@yandex.ru');     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'bankrot-spy';
$mail->Body    = 'bankrot-spy test';
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
