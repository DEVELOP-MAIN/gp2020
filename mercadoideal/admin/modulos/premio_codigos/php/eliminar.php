<?php
require_once '../../../php/class/class.codigo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decode($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idcodigo']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idcodigo = validaVars($_POST['idcodigo']);
$cdg = new codigo();
$cdg->delete($idcodigo);
?>