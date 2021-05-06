<?php
require_once '../../../php/class/class.mensaje.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['idmensaje']))	{printErrorXML(0, 'Faltan datos en el envio');	exit;}	

//genero el mensaje
$mnsj = new mensaje();
if($mnsj->select($_GET['idmensaje'])) 
{	
	$mnsj->delete($_GET['idmensaje']);
	printResultadoOK(1, 'El mensaje ha sido eliminado');
} 
else 
{
	$msg = 'Ha habido un error ya que no se encuentra el mensaje que desea eliminar';
	printResultadoMal(0, $msg);
}			

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