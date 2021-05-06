<?php
require '../admin/php/class/class.listado.php';
require '../admin/php/class/class.phpmailer.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug = 2; //2 for both client and server side response
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "mercadoidealq@gmail.com";//sender's gmail address
$mail->Password = "moldes2175";//sender's password
$mail->setFrom('mercadoidealq@gmail.com', 'Mercado Ideal');//sender's incormation
$mail->addReplyTo('mercadoidealq@gmail.com', 'Mercado Ideal');//if alternative reply to address is being used
$mail->addAddress('pablo@dixer.net', 'Pablo Cappelli');//receiver's information
$mail->addAddress('dixernet@gmail.com', 'Pablo Cappelli');//receiver's information
$mail->Subject = 'Howdy!';//subject of the email
$mail->msgHTML("Have a good day!");
$mail->AltBody = 'If you can\'t view the email, contact us';
//$mail->addAttachment('images/logo.png');//some attachment

if (!$mail->send()) {
	echo "NO SE ENVIO";
} else {
	echo "SE ENVIO!";
}
?>