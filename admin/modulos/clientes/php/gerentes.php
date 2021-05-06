<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de usuarios con rango gerente existentes en la base (tabla 'socios')
$ls = new listado();
$gerentes = $ls->getUsuariosPorRango('gerente');

//validacion de resultados
if(count($gerentes)<1) {printErrorXML(67, 'No hay usuarios con rango gerente disponibles en el sistema o hubo un error al traerlos!');	exit;}	

//imprimo los resultados correctos
printResults($gerentes);

//funcion de envio de resultados XML para esta pagina
function printResults($gerentes){
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);
	$fin = count($gerentes);
	for($i=0;$i<$fin;$i++){
		$xml_gerentes =& $resultadosGenerales->createChild('gerentes');
		$xml_gerentes->attribute('id', $gerentes[$i]['apellido'].', '.$gerentes[$i]['nombre']);
		$xml_gerentes->attribute('display', $gerentes[$i]['apellido'].', '.$gerentes[$i]['nombre']);
	} 
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>