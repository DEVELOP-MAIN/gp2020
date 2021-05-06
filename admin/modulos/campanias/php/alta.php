<?php
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['frm_alta_nombre'])) {echo '<script>parent.vuelveDelAlta(2);</script>';	exit;}	

$cmpn = new campania();

if(isset($_POST['frm_alta_nombre'])) $cmpn->setNombre($_POST['frm_alta_nombre']);

if($cmpn->insert())
	echo '<script>parent.vuelveDelAlta(1);</script>';	
else
	echo '<script>parent.vuelveDelAlta(0);</script>';
?>