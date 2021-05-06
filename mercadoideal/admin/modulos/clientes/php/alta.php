<?php
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/class/class.simpleimage.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['frm_alta_email']) || !isset($_POST['frm_alta_clave'])) {echo '<script>parent.vuelveDelAlta(2);</script>'; exit;}	

/*$foto = '';
if(isset($_FILES['frm_alta_foto']) && $_FILES['frm_alta_foto']['tmp_name']!='')
{
	$fname = $_FILES['frm_alta_foto']['name'];
	$ext = substr(strrchr($fname, '.'), 0);
	$foto = date('dmYHis').'_'.$fname;
	move_uploaded_file($_FILES['frm_alta_foto']['tmp_name'], '../../../../archivos/'.$foto);
	
	//si quiero cambiar el tamaño de la foto
	$image = new SimpleImage();
	$image->load('../../../../archivos/'.$foto);
	$image->resize(567,426);
	$image->save('../../../../archivos/'.$foto);
	$image->resize(83,62);
	$image->save('../../../../archivos/tn_'.$foto);
}*/

$clnt = new cliente();

if(isset($_POST['frm_alta_grupo']))											$clnt->setIdgrupo($_POST['frm_alta_grupo']);
if(isset($_POST['frm_alta_codigo_cliente']))				$clnt->setCodigo_cliente($_POST['frm_alta_codigo_cliente']);
if(isset($_POST['frm_alta_codigo_unico']))					$clnt->setCodigo_unico($_POST['frm_alta_codigo_unico']);
if(isset($_POST['frm_alta_estado']))										$clnt->setEstado($_POST['frm_alta_estado']);
if(isset($_POST['frm_alta_razon_social']))						$clnt->setRazon_social($_POST['frm_alta_razon_social']);
if(isset($_POST['frm_alta_nombre']))									$clnt->setNombre($_POST['frm_alta_nombre']);
if(isset($_POST['frm_alta_apellido']))									$clnt->setApellido($_POST['frm_alta_apellido']);
if(isset($_POST['frm_alta_clave']))											$clnt->setClave($_POST['frm_alta_clave']);
if(isset($_POST['frm_alta_email']))											$clnt->setEmail($_POST['frm_alta_email']);
if(isset($_POST['frm_alta_cuit']))													$clnt->setCuit($_POST['frm_alta_cuit']);
if(isset($_POST['frm_alta_domicilio']))									$clnt->setDomicilio($_POST['frm_alta_domicilio']);
if(isset($_POST['frm_alta_domicilio_provincia']))	$clnt->setDomicilio_provincia($_POST['frm_alta_domicilio_provincia']);
if(isset($_POST['frm_alta_domicilio_localidad']))	$clnt->setDomicilio_localidad($_POST['frm_alta_domicilio_localidad']);
if(isset($_POST['frm_alta_domicilio_cp']))						$clnt->setDomicilio_cp($_POST['frm_alta_domicilio_cp']);
if(isset($_POST['frm_alta_tel_movil']))								$clnt->setTel_movil($_POST['frm_alta_tel_movil']);
if(isset($_POST['frm_alta_tel_otro']))									$clnt->setTel_otro($_POST['frm_alta_tel_otro']);
if(isset($_POST['acepta_basesycond']))							$clnt->setAcepta_basesycond($_POST['acepta_basesycond']);

if($clnt->validaInsert($_POST['frm_alta_codigo_unico']))
{
	if($clnt->insert())
		echo '<script>parent.vuelveDelAlta(1);</script>';	
	else
		echo '<script>parent.vuelveDelAlta(0);</script>';
}
else echo '<script>parent.vuelveDelAlta(3);</script>';
?>