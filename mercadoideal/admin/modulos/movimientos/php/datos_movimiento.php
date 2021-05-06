<?php
require_once '../../../php/class/class.puntos.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET["c"])) {printErrorXML(65, "Faltan parametros para este modulo");exit;}

//armo los datos del ingreso manual
$idingreso = $_GET["c"];

//incio el objeto
$ingrs = new puntos();

if($ingrs->select($idingreso)) {
	//echo "si";
	printResults($ingrs);
} else
	printErrorXML(65, "No se encontraron datos para este ingreso manual");

//funcion de envio de resultados XML para esta pagina
function printResults($ingrs) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild("resultadosGenerales");
	$resultadosGenerales->attribute("resultado", 1);  
	
	$idcliente =& $resultadosGenerales->createChild("idcliente");
	$idcliente->text($ingrs->getIdcliente());
	 
	$puntos =& $resultadosGenerales->createChild("puntos");
	$puntos->text($ingrs->getPuntos());
	 
	$fecha =& $resultadosGenerales->createChild("fecha");
	$fecha->text($ingrs->getFecha());
	 
	$motivo =& $resultadosGenerales->createChild("motivo");
	$motivo->text($ingrs->getMotivo());
	 
	$observaciones =& $resultadosGenerales->createChild("observaciones");
	$observaciones->text($ingrs->getObservaciones());
		 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>