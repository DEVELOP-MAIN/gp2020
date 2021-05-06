<?php
require_once '../../../php/class/class.grupo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['frm_alta_nombre'])) {echo '<script>parent.vuelveDelAlta(2);</script>';	exit;}	

$grp = new grupo();

if(isset($_POST['frm_alta_nombre']))						$grp->setNombre($_POST['frm_alta_nombre']);
if(isset($_POST['frm_alta_nombre_ch']))				$grp->setNombre_ch($_POST['frm_alta_nombre_ch']);
if(isset($_POST['frm_alta_descripcion']))				$grp->setDescripcion($_POST['frm_alta_descripcion']);
if(isset($_POST['frm_alta_descripcion_ch']))	$grp->setDescripcion_ch($_POST['frm_alta_descripcion_ch']);

if($grp->insert())
	echo '<script>parent.vuelveDelAlta(1);</script>';	
else
	echo '<script>parent.vuelveDelAlta(0);</script>';
?>