<?php
require '../admin/php/class/configuracion.php';
require '../admin/php/class/class.listado.php';
require '../admin/php/class/class.phpmailer.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP(); 
$mail->Host     = MAIL_HOST; 
$mail->SMTPAuth	= MAIL_SMTP_AUTH;
$mail->Username	= MAIL_USERNAME;
$mail->Password = MAIL_PASSWORD;
$mail->SMTPSecure = MAIL_SMTP_SECURE;
$mail->Port     = MAIL_PORT;


$mail->setFrom('mercadoideal@aper.net', 'Mailer');
$mail->addAddress('pablo@dixer.net', 'Pablo');     // Add a recipient
$mail->addAddress('dixernet@gmail.com', 'Pablo');     // Add a recipient
$mail->addReplyTo('mercadoideal@aper.net', 'Information');

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject 3';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


echo MAIL_HOST."<BR>"; 
echo MAIL_PORT."<BR>"; 
echo MAIL_SMTP_AUTH."<BR>"; 
echo MAIL_USERNAME."<BR>"; 
echo MAIL_PASSWORD."<BR>"; 

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>