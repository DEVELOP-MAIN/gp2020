<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de usuarios con rango jefe existentes en la base (tabla 'socios')
$ls = new listado();
$jefes = $ls->getUsuariosPorRango('jefe');

//validacion de resultados
if(count($jefes)<1) {printErrorXML(67, 'No hay usuarios con rango jefe disponibles en el sistema o hubo un error al traerlos!');	exit;}	

//imprimo los resultados correctos
printResults($jefes);

//funcion de envio de resultados XML para esta pagina
function printResults($jefes){
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($jefes);
	for($i=0;$i<$fin;$i++){
		$xml_jefes =& $resultadosGenerales->createChild('jefes');
		$xml_jefes->attribute('id', $jefes[$i]['apellido'].', '.$jefes[$i]['nombre']);
		$xml_jefes->attribute('display', $jefes[$i]['apellido'].', '.$jefes[$i]['nombre']);
	} 
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>