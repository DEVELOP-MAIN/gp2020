<?php
require_once '../../../php/class/class.puntos.php';
require_once '../../../php/class/class.socio.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idusuario'])) {echo '<script>parent.vuelveDelAlta(2);</script>';	exit;}	

//recupero codigo y nombre-apellido del socio
$clnt = new socio();
if($clnt->select($_POST['idusuario'])){
	$codigo = $clnt->getCodigo();
	$nombre = $clnt->getNombre();
	$apellido = $clnt->getApellido();
	if($nombre!='') $socio = $nombre.' '.$apellido; else $socio = $apellido;
}

if(isset($_POST['fecha_movimiento'])) {
	$fecha = $_POST['fecha_movimiento'];
}
else {
	$fecha = date('Y-m-d');
}

$ingrs = new puntos();

if(isset($_POST['idusuario']))							$ingrs->setIdcliente($_POST['idusuario']);
if(isset($_POST['frm_alta_puntos']))				$ingrs->setPuntos($_POST['frm_alta_puntos']);
if(isset($_POST['frm_alta_motivo']))				$ingrs->setLinea($_POST['frm_alta_motivo']);
if(isset($_POST['frm_alta_observaciones']))	$ingrs->setObservaciones('Ingreso Manual | '.$_POST['frm_alta_observaciones']);
$ingrs->setFecha($fecha);
$ingrs->setFecha_carga($fecha);
$ingrs->setCodigo($codigo);
$ingrs->setUsuario($socio);

if($ingrs->insert())
	echo '<script>parent.vuelveDelAlta(1);</script>';	
else
	echo '<script>parent.vuelveDelAlta(0);</script>';
?>