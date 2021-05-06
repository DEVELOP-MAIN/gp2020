<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de secciones existentes en la base
$ls = new listado();
$provincias = $ls->getProvinciasCombo();

//validacion de resultados
if(count($provincias)<1) {printErrorXML(67, 'No hay provincias disponibles en el sistema o hubo un error al traerlas!');	exit;}	

//imprimo los resultados correctos
printResults($provincias);

//funcion de envio de resultados XML para esta pagina
function printResults($provincias)
{
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($provincias);
	for($i=0;$i<$fin;$i++)
	{
		$xml_provincias =& $resultadosGenerales->createChild('provincias');
		$xml_provincias->attribute('id', $provincias[$i]['provincia']);
		$xml_provincias->attribute('display', $provincias[$i]['provincia']);
	} 
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>