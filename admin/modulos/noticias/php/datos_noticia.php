<?php
//Este php retorna los datos que hay en la tabla 'noticias' de la novedad seleccionada
require_once '../../../php/class/class.noticia.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos de la novedad
$idnoticia = $_GET['c'];

//incio el objeto
$ntc = new noticia();
$ntc->select($idnoticia);
if($ntc->getIdnoticia()!='')
	printResults($ntc);
else
	printErrorXML(65, 'No se encontraron datos para esta noticia');

//funcion de envio de resultados XML para esta pagina
function printResults($ntc){
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  

	$titulo =& $resultadosGenerales->createChild('titulo');
	$titulo->text($ntc->getTitulo());

	$cuerpo =& $resultadosGenerales->createChild('cuerpo');
	$cuerpo->text("<![CDATA[".$ntc->getCuerpo()."]]>");
	
	$imagen =& $resultadosGenerales->createChild('imagen');
	$imagen->text($ntc->getImagen());
	
	$video =& $resultadosGenerales->createChild('video');
	$video->text("<![CDATA[".$ntc->getVideo()."]]>");
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>