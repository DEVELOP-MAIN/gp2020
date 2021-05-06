<?php
//NO SE UTILIZA
require_once '../../../php/class/class.mensaje.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['frm_alta_mensaje'])) {printErrorXML(0, 'Faltan datos en el envio'); exit;}	

if(!isset($_SESSION))	session_start();
if(!isset($_SESSION['QLMS_logged']) || $_SESSION['QLMS_logged']!=1) {printErrorXML(0, 'Faltan datos en el envio');	exit;}

//genero el nuevo mensaje
$mnsj = new mensaje();

if(isset($_GET['frm_alta_tipo']))		 		$mnsj->setTipo($_GET['frm_alta_tipo']);
if(isset($_GET['frm_alta_mensaje']))	$mnsj->setMensaje($_GET['frm_alta_mensaje']);
$mnsj->setEstado('no leido');
$mnsj->setIdusuario($_SESSION['QLMS_idusuario']);

$mnsj->insert();
printResultadoOK(1, 'El nuevo mensaje ha sido registrado');

//funcion de envio de resultados XML para esta pagina
function printResultadoOK($result, $msg) {
	 header('Content-type:text/xml;charset="utf-8"');
	 $xmlDoc = new MiniXMLDoc();
	 $xmlRoot =& $xmlDoc->getRoot();
	 $resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	 $resultadosGenerales->attribute('resultado', $result);
	 $resultadosGenerales->attribute('mensaje', $msg);
	 $mensajes =& $resultadosGenerales->createChild('mensaje');
	 $mensajes->text($msg);	 
	 print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}

//funcion de envio de resultados XML para esta pagina
function printResultadoMal($result, $msg) {
	 header('Content-type:text/xml;charset="utf-8"');
	 $xmlDoc = new MiniXMLDoc();
	 $xmlRoot =& $xmlDoc->getRoot();
	 $resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	 $resultadosGenerales->attribute('resultado', $result);
	 $resultadosGenerales->attribute('mensaje',  $msg);
	 print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>