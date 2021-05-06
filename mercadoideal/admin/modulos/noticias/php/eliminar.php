<?php
require_once '../../../php/class/class.noticia.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decode($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idnoticia']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idnoticia = $_POST['idnoticia'];
$ntc = new noticia();
$ntc->delete($idnoticia);
?>