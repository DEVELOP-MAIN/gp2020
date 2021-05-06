<?php
require_once '../../../php/class/class.concurso.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decode($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idconcurso']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idconcurso = $_POST['idconcurso'];
$cncrs = new concurso();
$cncrs->delete_todo($idconcurso);
?>