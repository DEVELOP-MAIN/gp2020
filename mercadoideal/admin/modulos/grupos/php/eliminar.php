<?php
require_once '../../../php/class/class.grupo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decode($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idgrupo']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idgrupo = $_POST['idgrupo'];
$grp = new grupo();
$grp->delete_todo($idgrupo);
?>