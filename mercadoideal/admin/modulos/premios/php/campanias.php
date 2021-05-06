<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de campañas existentes en la base
$ls = new listado();
$campanias = $ls->getCampaniasCombo();

//validacion de resultados
if(count($campanias)<1) {printErrorXML(67, 'No hay campañas disponibles en el sistema o hubo un error al traerlas!');	exit;}	

//imprimo los resultados correctos
printResults($campanias);

//funcion de envio de resultados XML para esta pagina
function printResults($campanias)
{
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($campanias);
	for($i=0;$i<$fin;$i++)
	{
		$xml_campanias =& $resultadosGenerales->createChild('campanias');
		$xml_campanias->attribute('id', $campanias[$i]['idcampania']);
		$xml_campanias->attribute('display', $campanias[$i]['nombre']." / ".$campanias[$i]['nombre_ch']);
	} 
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>