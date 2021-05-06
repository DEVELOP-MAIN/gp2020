<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.login.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/class/class.phpmailer.php';

if(!isset($_SESSION)) {session_start();}

$cli = new cliente();
if(!isset($_GET['code'])) header('location:index.php');
$code = base64_decode($_GET['code']);
if($cli->select($code)) {
	if($cli->activar($code))	header('location:index.php?a=true');
}
?>