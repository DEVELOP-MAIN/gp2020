<?php
require_once '../../../php/class/class.noticia.php';
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

$ntc = new noticia();

if(isset($_POST['frm_alta_titulo']))	$ntc->setTitulo($_POST['frm_alta_titulo']);
if(isset($_POST['frm_alta_tipo']))		$ntc->setTipo($_POST['frm_alta_tipo']);
if(isset($_POST['frm_alta_estado']))	$ntc->setEstado($_POST['frm_alta_estado']);
if(isset($_POST['frm_alta_cuerpo']))	$ntc->setCuerpo($_POST['frm_alta_cuerpo']);
$ntc->setImagen($imagen);
if(isset($_POST['frm_alta_video']))		$ntc->setVideo($_POST['frm_alta_video']);

if($ntc->insert())
	echo '<script>parent.vuelveDelAlta(1);</script>';	
else
	echo '<script>parent.vuelveDelAlta(0);</script>';
?>