<?php
//Este php retorna los datos que hay en la tabla 'usuarios' del usuario de sistema seleccionado
require_once '../../../php/class/class.usuario.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');	exit;}

//armo los datos del operador
$operador = $_GET['c'];

//incio el objeto
$us = new usuario();
$us->select($operador);
if($us->getIdusuario()!='') 
	printResults($us);
else
	printErrorXML(65, 'No se encontraron datos para este usuario de sistema');

//funcion de envio de resultados XML para esta pagina
function printResults($us) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  
	
	$op_tipo =& $resultadosGenerales->createChild('tipo');
	$op_tipo->text($us->getTipo());
	
	$op_usuario =& $resultadosGenerales->createChild('usuario');
	$op_usuario->text($us->getUsuario());
	
	$op_clave =& $resultadosGenerales->createChild('clave');
	$op_clave->text($us->getClave());
	
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>