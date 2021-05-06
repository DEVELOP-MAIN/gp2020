<?php
require_once '../../../php/class/class.premio.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decode($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idpremio']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idpremio = $_POST['idpremio'];
$prm = new premio();
$prm->delete_todo($idpremio);
?>