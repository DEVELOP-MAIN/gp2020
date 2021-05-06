<?php
//Este php retorna los datos que hay en la tabla 'concursos' del concurso seleccionado
require_once '../../../php/class/class.concurso.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos de la concurso
$idconcurso = $_GET['c'];

//incio el objeto
$cncrs = new concurso();
$cncrs->select($idconcurso);
if($cncrs->getIdconcurso()!='')
	printResults($cncrs);
else
	printErrorXML(65, 'No se encontraron datos para este concurso');

//funcion de envio de resultados XML para esta pagina
function printResults($cncrs) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  
	
	$titulo =& $resultadosGenerales->createChild('titulo');
	$titulo->text($cncrs->getTitulo());
	
	$descripcion =& $resultadosGenerales->createChild('descripcion');
	$descripcion->text($cncrs->getDescripcion());
	
	$imagen =& $resultadosGenerales->createChild('imagen');
	$imagen->text($cncrs->getImagen());
	
	$chances_minimas =& $resultadosGenerales->createChild('chances_minimas');
	$chances_minimas->text($cncrs->getChances_minimas());
	
	$aviso_legal =& $resultadosGenerales->createChild('aviso_legal');
	$aviso_legal->text($cncrs->getAviso_legal());
	
	$fecha_desde =& $resultadosGenerales->createChild('fecha_desde');
	$fecha_desde->text($cncrs->getFecha_desde());
	
	$fecha_hasta =& $resultadosGenerales->createChild('fecha_hasta');
	$fecha_hasta->text($cncrs->getFecha_hasta());
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>