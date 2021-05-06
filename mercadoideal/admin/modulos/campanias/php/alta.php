<?php
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['frm_alta_nombre'])) {echo '<script>parent.vuelveDelAlta(2);</script>';	exit;}	

$cmpn = new campania();

if(isset($_POST['frm_alta_nombre']))				$cmpn->setNombre($_POST['frm_alta_nombre']);
if(isset($_POST['frm_alta_nombre_ch']))		$cmpn->setNombre_ch($_POST['frm_alta_nombre_ch']);
/*if(isset($_POST['frm_alta_fecha_inicial']))	$cmpn->setFecha_inicial($_POST['frm_alta_fecha_inicial']);
if(isset($_POST['frm_alta_fecha_final']))		$cmpn->setFecha_final($_POST['frm_alta_fecha_final']);*/

if($cmpn->insert())
	echo '<script>parent.vuelveDelAlta(1);</script>';	
else
	echo '<script>parent.vuelveDelAlta(0);</script>';
?>