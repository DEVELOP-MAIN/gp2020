<?php
require_once '../../../php/class/class.puntos.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decode($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idingreso']))	{echo "<script>parent.vuelveDeEdicion(2);</script>";	exit;}	

$idingreso = $_POST['idingreso'];
$ingrs = new puntos();
$ingrs->delete($idingreso);
?>