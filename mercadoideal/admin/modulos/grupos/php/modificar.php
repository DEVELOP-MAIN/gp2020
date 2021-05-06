<?php
require_once '../../../php/class/class.grupo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idgrupo']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idgrupo = $_POST['idgrupo'];
$grp = new grupo();

if($grp->select($idgrupo))
{	
	if(isset($_POST['frm_alta_nombre']))						$grp->setNombre($_POST['frm_alta_nombre']);
	if(isset($_POST['frm_alta_nombre_ch']))				$grp->setNombre_ch($_POST['frm_alta_nombre_ch']);
	if(isset($_POST['frm_alta_descripcion']))				$grp->setDescripcion($_POST['frm_alta_descripcion']);
	if(isset($_POST['frm_alta_descripcion_ch']))	$grp->setDescripcion_ch($_POST['frm_alta_descripcion_ch']);
	
	if($grp->update($idgrupo))
		echo '<script>parent.vuelveDeEdicion(1);</script>';	
	else
		echo '<script>parent.vuelveDeEdicion(0);</script>';
}
else	echo '<script>parent.vuelveDeEdicion(3);</script>';
?>