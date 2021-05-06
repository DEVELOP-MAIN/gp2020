<?php
require_once '../../../php/class/class.socio.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idcliente']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}

$idcliente = $_POST['idcliente'];
$clnt = new socio();

if($clnt->select($idcliente)){
	$region = '';
	if(isset($_POST['frm_alta_region'])) $region = $_POST['frm_alta_region'];
	if(isset($_POST['frm_alta_otras_regiones']) && $_POST['frm_alta_otras_regiones'] != '') $region .= ','.$_POST['frm_alta_otras_regiones'];
	$clnt->setRegion($region);
	if(isset($_POST['frm_alta_codigo']))		$clnt->setCodigo($_POST['frm_alta_codigo']);
	if(isset($_POST['frm_alta_rango']))			$clnt->setRango($_POST['frm_alta_rango']);
	if(isset($_POST['frm_alta_ejecutivo']))	$clnt->setEjecutivo($_POST['frm_alta_ejecutivo']);
	if(isset($_POST['frm_alta_jefe']))			$clnt->setJefe($_POST['frm_alta_jefe']);
	if(isset($_POST['frm_alta_gerente']))		$clnt->setGerente($_POST['frm_alta_gerente']);
	if(isset($_POST['frm_alta_nombre']))		$clnt->setNombre($_POST['frm_alta_nombre']);
	if(isset($_POST['frm_alta_apellido']))	$clnt->setApellido($_POST['frm_alta_apellido']);
	if(isset($_POST['frm_alta_email']))			$clnt->setEmail($_POST['frm_alta_email']);
	if(isset($_POST['frm_alta_clave']))			$clnt->setClave($_POST['frm_alta_clave']);
	if(isset($_POST['frm_alta_direccion']))	$clnt->setDireccion($_POST['frm_alta_direccion']);
	if(isset($_POST['frm_alta_telefono']))	$clnt->setTelefono($_POST['frm_alta_telefono']);
	
	if($clnt->update($idcliente))
		echo '<script>parent.vuelveDeEdicion(1);</script>';	
	else
		echo '<script>parent.vuelveDeEdicion(0);</script>';
}
else	echo '<script>parent.vuelveDeEdicion(3);</script>';
?>