<?php
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idcategoria']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}

$idcategoria = $_POST['idcategoria'];
$cmpn = new campania();
if($cmpn->delete($idcategoria))
	echo '1';
else
	echo '0';
?>