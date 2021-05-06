<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de secciones existentes en la base
$ls = new listado();
$grupos = $ls->getGruposCombo();

//validacion de resultados
if(count($grupos)<1) {printErrorXML(67, 'No hay grupos disponibles en el sistema o hubo un error al traerlos!');	exit;}	

//imprimo los resultados correctos
printResults($grupos);

//funcion de envio de resultados XML para esta pagina
function printResults($grupos)
{
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($grupos);
	for($i=0;$i<$fin;$i++)
	{
		$xml_grupos =& $resultadosGenerales->createChild('grupos');
		$xml_grupos->attribute('id', $grupos[$i]['idgrupo']);
		$xml_grupos->attribute('display', $grupos[$i]['nombre']);
	} 
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>