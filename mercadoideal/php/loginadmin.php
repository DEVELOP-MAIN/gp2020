<?php
require_once '../admin/php/class/class.login.php';
require_once '../admin/php/minixml/minixml.inc.php';
require_once '../admin/php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que me pasen las variables
if(!isset($_GET['u'])) {printResults(0, 'Faltan datos en el envio');	exit;}	

//verifico el login del operador como sup
$log = new login();
if($log->loggerFrontAdmin(strrev($_GET['u'])))
	header('location:../premios.php');
else
	printResultsMal(0, 'CLAVE incorrecta');

//funcion de envio de resultados XML para esta pagina
function printResults($result, $msg) {
	header('Content-type:text/xml;charset="iso-8859-1"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', $result);
	$resultadosGenerales->attribute('nombre', $_SESSION['QLMSF_nombre']);
	$resultadosGenerales->attribute('puntos', $_SESSION['QLMSF_puntos']);
	$resultadosGenerales->attribute('foto', $_SESSION['QLMSF_foto']);
	$mensajes =& $resultadosGenerales->createChild('mensaje');
	$mensajes->text($msg);	 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}

function printResultsMal($result, $msg) {
	header('Content-type:text/xml;charset="iso-8859-1"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', $result);
	$mensajes =& $resultadosGenerales->createChild('mensaje');
	$mensajes->text($msg);	 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>