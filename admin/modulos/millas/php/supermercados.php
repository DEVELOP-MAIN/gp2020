<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de supermercados existentes en la base
$ls = new listado();
$supermercados = $ls->getSupermercadosCombo();

//validacion de resultados
if(count($supermercados)<1) {printErrorXML(67, 'No hay supermercados disponibles en el sistema o hubo un error al traerlos!');	exit;}	

//imprimo los resultados correctos
printResults($supermercados);

//funcion de envio de resultados XML para esta pagina
function printResults($supermercados)
{
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($supermercados);
	for($i=0;$i<$fin;$i++)
	{
		$xml_supermercados =& $resultadosGenerales->createChild('supermercados');
		$xml_supermercados->attribute('id', $supermercados[$i]['idcliente']);
		if($supermercados[$i]['razon_social']!='')
		$xml_supermercados->attribute('display', $supermercados[$i]['razon_social']);
		else
		$xml_supermercados->attribute('display', $supermercados[$i]['idcliente']);
	} 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>