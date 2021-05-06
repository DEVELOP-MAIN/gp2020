<?php
require_once '../../../php/class/class.canje.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

if(!isset($_SESSION))	session_start();

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['id']) || !isset($_GET['cod']))	{printErrorXML(0, 'Faltan datos en el envio');	exit;}	

$idcanje	= $_GET['id'];
$codigo		= $_GET['cod'];

$cnj = new canje();

if($cnj->cambiaCodigo($idcanje,$codigo))
	printResultadoOK(1, 'Se ingresó el codigo de seguimiento');
else
	printResultadoMal(0, 'Ha habido un error ya que no se encuentra el canje que desea modificar');

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