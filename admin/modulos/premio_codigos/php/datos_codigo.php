<?php
//Este php retorna los datos que hay en la tabla 'premios_codigos' del codigo seleccionado
require_once '../../../php/class/class.codigo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos del codigo
$idcodigo = validaVars($_GET['c']);

//incio el objeto
$cdg = new codigo();
$cdg->select($idcodigo);
if($cdg->getIdcodigo()!='')
	printResults($cdg);
else
	printErrorXML(65, 'No se encontraron datos para este codigo');

//funcion de envio de resultados XML para esta pagina
function printResults($cdg) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  
	
	$codigo =& $resultadosGenerales->createChild('codigo');
	$codigo->text('<![CDATA['.$cdg->getCodigo().']]>');
		 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>