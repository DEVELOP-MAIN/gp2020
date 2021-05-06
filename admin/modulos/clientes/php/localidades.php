<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

$prov = 'Buenos Aires';
if(isset($_GET['p'])&&$_GET['p']!='') $prov = $_GET['p'];
	
//traigo el listado de localidades para la provincia indicada
$ls = new listado();
$localidades = $ls->getLocalidadesCombo($prov);

//validacion de resultados
if(count($localidades)<1) {printErrorXML(67, 'No hay localidades disponibles en el sistema o hubo un error al traerlas!');	exit;}	

//imprimo los resultados correctos
printResults($localidades);

//funcion de envio de resultados XML para esta pagina
function printResults($localidades)
{
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($localidades);
	for($i=0;$i<$fin;$i++)
	{
		$xml_localidades =& $resultadosGenerales->createChild('localidades');
		$xml_localidades->attribute('id', $localidades[$i]['localidad']);
		$xml_localidades->attribute('display', $localidades[$i]['localidad']);
	} 
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>