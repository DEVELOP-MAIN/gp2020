<?php
require_once '../../../php/class/class.premio.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['idp']) || !isset($_GET['d'])) {printErrorXML(0, 'Faltan datos en el envio');	exit;}	
$f = preg_split('/_/',$_GET['idp']);
$idprm = $f[0];

//genero el premio
$prm = new premio();
if($prm->select($idprm)) 
{
	$prm->cambiaEstado($idprm,$_GET['d']);
	printResultadoOK(1, 'La característica destacado del premio ha sido cambiada');
}
else
{
	$msg = 'Error: no se encuentra el premio que desea modificar';
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