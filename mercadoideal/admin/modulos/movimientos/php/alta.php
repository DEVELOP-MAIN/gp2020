<?php
require_once '../../../php/class/class.puntos.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST["idusuario"])) {echo "<script>parent.vuelveDelAlta(2);</script>";	exit;}	

$fecha = date("d/m/Y");
$ingrs = new puntos();

if(isset($_POST["idusuario"]))				$ingrs->setIdcliente($_POST["idusuario"]);
if(isset($_POST["frm_alta_puntos"]))		$ingrs->setPuntos($_POST["frm_alta_puntos"]);
if(isset($_POST["frm_alta_motivo"]))		$ingrs->setMotivo($_POST["frm_alta_motivo"]);
if(isset($_POST["frm_alta_observaciones"]))	$ingrs->setObservaciones('Ingreso Manual | '.$_POST["frm_alta_observaciones"]);
$ingrs->setFecha($fecha);
$ingrs->setFecha_carga($fecha);

if($ingrs->insert())
	echo "<script>parent.vuelveDelAlta(1);</script>";	
else
	echo "<script>parent.vuelveDelAlta(0);</script>";
?>