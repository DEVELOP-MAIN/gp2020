<?php
require_once '../../../php/minixml/minixml.inc.php';

if(!isset($_SESSION))	session_start();
if(isset($_SESSION['QLMS_logged']) && ($_SESSION['QLMS_logged']==1))
	printResultado(1, $_SESSION['QLMS_usuario'], $_SESSION['QLMS_tipo']);
else
	printResultado(0, '', '', '', '', '');

//funcion de envio de resultados XML para esta pagina
function printResultado($result, $usuario, $tipo) {
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', $result);	 
	$xml_seguridad =& $resultadosGenerales->createChild('usuario');
	$xml_seguridad->attribute('usuario', $usuario);
	$xml_seguridad->attribute('tipo', $tipo);
	$xml_seguridad->attribute('dia', date('d'));
	$xml_seguridad->attribute('mes', date('m'));
	$xml_seguridad->attribute('ano', date('Y'));
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>