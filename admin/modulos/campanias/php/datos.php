<?php
//Este php retorna los datos que hay en la tabla 'campanias' de la categoría seleccionada
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos de la categoria
$idcategoria = $_GET['c'];

//incio el objeto
$cmpn = new campania();
if($cmpn->select($idcategoria))
	printResults($cmpn);
else
	printErrorXML(65, 'No se encontraron datos para esta categoria');

//funcion de envio de resultados XML para esta pagina
function printResults($cmpn) {
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  

	$nombre =& $resultadosGenerales->createChild('nombre');
	$nombre->text($cmpn->getNombre());
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>