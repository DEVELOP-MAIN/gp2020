<?php
require_once '../../../php/class/class.usuario.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['idop'])) {printErrorXML(0, 'Faltan datos en el envio');	exit;}	

//genero el usuario de sistema
$us = new usuario();
if($us->select($_GET['idop'])) 
{	
	$us->delete($_GET['idop']);
	printResultadoOK(1, 'El usuario de sistema ha sido eliminado');
} 
else printResultadoMal(0, 'Ha habido un error ya que no se encuentra el usuario de sistema que desea eliminar');

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