<?php
require_once '../../../php/class/class.puntos.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idingreso']))	{echo "<script>parent.vuelveDeEdicion(2);</script>";	exit;}	

$idingreso = $_POST['idingreso'];
$ingrs = new puntos();

if($ingrs->select($idingreso))
{
	if(isset($_POST["frm_alta_puntos"]))		$ingrs->setPuntos($_POST["frm_alta_puntos"]);
	if(isset($_POST["frm_alta_observaciones"]))	$ingrs->setObservaciones($_POST["frm_alta_observaciones"]);
	if(isset($_POST["frm_alta_motivo"]))		$ingrs->setMotivo($_POST["frm_alta_motivo"]);
	
	if($ingrs->update($idingreso))
		echo "<script>parent.vuelveDeEdicion(1);</script>";	
	else
		echo "<script>parent.vuelveDeEdicion(0);</script>";
}
else
	echo "<script>parent.vuelveDeEdicion(3);</script>";
?>