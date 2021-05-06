<?php
require_once '../../../php/class/class.concurso.php';
require_once '../../../php/class/class.simpleimage.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['frm_alta_titulo'])) {echo '<script>parent.vuelveDelAlta(2);</script>'; exit;}	

$imagen = '';
if(isset($_FILES['frm_alta_imagen']) && $_FILES['frm_alta_imagen']['tmp_name']!='')
{
	$fname = $_FILES['frm_alta_imagen']['name'];
	$ext = substr(strrchr($fname, '.'), 0);
	$imagen = date('dmYHis').'_'.$fname;
	move_uploaded_file($_FILES['frm_alta_imagen']['tmp_name'], "../../../../archivos/".$imagen);
	/*
	cambiar el tamaño de la imagen
	$image = new SimpleImage();
	$image->load("../../../../archivos/".$imagen);
	$image->resize(567,426);
	$image->save("../../../../archivos/".$imagen);
	$image->resize(83,62);
	$image->save("../../../../archivos/tn_".$imagen);
	*/
}

$cncrs = new concurso();

if(isset($_POST['frm_alta_titulo']))										$cncrs->setTitulo($_POST['frm_alta_titulo']);
$cncrs->setImagen($imagen);
if(isset($_POST['frm_alta_descripcion']))						$cncrs->setDescripcion($_POST['frm_alta_descripcion']);
if(isset($_POST['frm_alta_chances_minimas']))	$cncrs->setChances_minimas($_POST['frm_alta_chances_minimas']);
if(isset($_POST['frm_alta_aviso_legal']))						$cncrs->setAviso_legal($_POST['frm_alta_aviso_legal']);
if(isset($_POST['frm_alta_fecha_desde']))				$cncrs->setFecha_desde($_POST['frm_alta_fecha_desde']);
if(isset($_POST['frm_alta_fecha_hasta']))					$cncrs->setFecha_hasta($_POST['frm_alta_fecha_hasta']);

if($cncrs->insert())
	echo '<script>parent.vuelveDelAlta(1);</script>';	
else
	echo '<script>parent.vuelveDelAlta(0);</script>';
?>