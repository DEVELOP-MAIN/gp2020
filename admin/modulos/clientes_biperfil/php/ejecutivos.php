<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de usuarios con rango ejecutivo existentes en la base (tabla 'socios')
$ls = new listado();
$ejecutivos = $ls->getUsuariosPorRango('ejecutivo');

//validacion de resultados
if(count($ejecutivos)<1) {printErrorXML(67, 'No hay usuarios con rango ejecutivo disponibles en el sistema o hubo un error al traerlos!');	exit;}	

//imprimo los resultados correctos
printResults($ejecutivos);

//funcion de envio de resultados XML para esta pagina
function printResults($ejecutivos){
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($ejecutivos);
	for($i=0;$i<$fin;$i++){
		$xml_ejecutivos =& $resultadosGenerales->createChild('ejecutivos');
		$xml_ejecutivos->attribute('id', $ejecutivos[$i]['apellido'].', '.$ejecutivos[$i]['nombre']);
		$xml_ejecutivos->attribute('display', $ejecutivos[$i]['apellido'].', '.$ejecutivos[$i]['nombre']);
	} 
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>