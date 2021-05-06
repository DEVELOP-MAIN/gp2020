<?php
//Este php retorna los datos que hay en la tabla 'grupos' del grupo seleccionado
require_once '../../../php/class/class.grupo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos del grupo
$idgrupo = $_GET['c'];

//incio el objeto
$grp = new grupo();
$grp->select($idgrupo);
if($grp->getIdgrupo()!='')
	printResults($grp);
else
	printErrorXML(65, 'No se encontraron datos para esta grupo');

//funcion de envio de resultados XML para esta pagina
function printResults($grp) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  
		 
	$nombre =& $resultadosGenerales->createChild('nombre');
	$nombre->text($grp->getNombre());
	
	$nombre_ch =& $resultadosGenerales->createChild('nombre_ch');
	$nombre_ch->text($grp->getNombre_ch());
	
	$descripcion =& $resultadosGenerales->createChild('descripcion');
	$descripcion->text($grp->getDescripcion());
		
	$descripcion_ch =& $resultadosGenerales->createChild('descripcion_ch');
	$descripcion_ch->text($grp->getDescripcion_ch());
		
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>