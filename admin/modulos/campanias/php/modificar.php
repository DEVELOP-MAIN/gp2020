<?php
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
//$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idcategoria'])) {echo '<script>parent.vuelveDeEdicion(2);</script>'; exit;}

$idcategoria = $_POST['idcategoria'];
$cmpn = new campania();

if($cmpn->select($idcategoria)){
	if(isset($_POST['frm_alta_nombre'])) $cmpn->setNombre($_POST['frm_alta_nombre']);

	if($cmpn->update($idcategoria))
		echo '<script>parent.vuelveDeEdicion(1);</script>';	
	else
		echo '<script>parent.vuelveDeEdicion(0);</script>';
}
else echo '<script>parent.vuelveDeEdicion(3);</script>';
?>