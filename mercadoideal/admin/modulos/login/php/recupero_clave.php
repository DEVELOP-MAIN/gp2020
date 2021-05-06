<?php
//includes generales
require_once '../../../php/minixml/minixml.inc.php';
printResults(0, "No se encontró el usuario en la base");

function printResults($result, $msg) {
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild("resultadosGenerales");
	$resultadosGenerales->attribute("resultado", $result);
	$mensajes =& $resultadosGenerales->createChild("mensaje");
	$mensajes->text($msg);	 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}?>