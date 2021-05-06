<?php
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idcampania']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idcampania = $_POST['idcampania'];
$cmpn = new campania();
if($cmpn->delete($idcampania))
	echo "1";
else 
	echo "0";
?>