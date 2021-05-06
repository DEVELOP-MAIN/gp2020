<?php
//Este php retorna los datos que hay en la tabla 'campanias' de la campaña seleccionada
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos de la campania
$idcampania = $_GET['c'];

//incio el objeto
$cmpn = new campania();
$cmpn->select($idcampania);
if($cmpn->getidcampania()!='')
	printResults($cmpn);
else
	printErrorXML(65, 'No se encontraron datos para esta campania');

//funcion de envio de resultados XML para esta pagina
function printResults($cmpn) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  
		 
	$nombre =& $resultadosGenerales->createChild('nombre');
	$nombre->text($cmpn->getNombre());
	
	$nombre_ch =& $resultadosGenerales->createChild('nombre_ch');
	$nombre_ch->text($cmpn->getNombre_ch());
	
	$fecha_inicial =& $resultadosGenerales->createChild('fecha_inicial');
	$fecha_inicial->text($cmpn->getFecha_inicial());
	
	$fecha_final =& $resultadosGenerales->createChild('fecha_final');
	$fecha_final->text($cmpn->getFecha_final());
		
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>