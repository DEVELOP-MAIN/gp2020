<?php
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

//decodifico desde utf-8
$_POST = decodePOST($_POST);
//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idcliente']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idcliente = $_POST['idcliente'];
$clnt = new cliente();
if($clnt->select($idcliente))
	$clnt->delete_todo($idcliente);
?>