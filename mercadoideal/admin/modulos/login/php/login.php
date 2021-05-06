<?php
require_once '../../../php/class/class.login.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que me pasen las variables
if(!isset($_GET['u']) || !isset($_GET['p']))	{printResults(0, 'Faltan datos en el envio');	exit;}	

//verifico el login del usuario
$log = new login();
if($log->logger($_GET['u'], $_GET['p']))
	printResults(1, 'Bienvenido: '.$_SESSION['QLMS_usuario']);
else
	printResults(0, 'Usuario o clave incorrectos');

//funcion de envio de resultados XML para esta pagina
function printResults($result, $msg) {
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', $result);
	$mensajes =& $resultadosGenerales->createChild('mensaje');
	$mensajes->text($msg);	 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>