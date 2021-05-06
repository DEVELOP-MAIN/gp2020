<?php
//Este php retorna los datos que hay en la tabla 'clientes' del sup seleccionado
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos del sup
$idcliente = $_GET['c'];

//incio el objeto
$clnt = new cliente();
$clnt->select($idcliente);
if($clnt->getIdcliente()!='')
	printResults($clnt);
else
	printErrorXML(65, 'No se encontraron datos para este sup');

//funcion de envio de resultados XML para esta pagina
function printResults($clnt) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  
	
	$grupo =& $resultadosGenerales->createChild('grupo');
	$grupo->text($clnt->getIdgrupo());
	 
	$codigo_cliente =& $resultadosGenerales->createChild('codigo_cliente');
	$codigo_cliente->text($clnt->getCodigo_cliente());
	 
	$codigo_unico =& $resultadosGenerales->createChild('codigo_unico');
	$codigo_unico->text($clnt->getCodigo_unico());
	 
	$razon_social =& $resultadosGenerales->createChild('razon_social');
	$razon_social->text($clnt->getRazon_social());
	 
	$nombre =& $resultadosGenerales->createChild('nombre');
	$nombre->text($clnt->getNombre());
	 
	$apellido =& $resultadosGenerales->createChild('apellido');
	$apellido->text($clnt->getApellido());
	 
	$clave =& $resultadosGenerales->createChild('clave');
	$clave->text($clnt->getClave());
	 
	$email =& $resultadosGenerales->createChild('email');
	$email->text($clnt->getEmail());
	 
	$cuit =& $resultadosGenerales->createChild('cuit');
	$cuit->text($clnt->getCuit());
		
	$domicilio =& $resultadosGenerales->createChild('domicilio');
	$domicilio->text($clnt->getDomicilio());
	 
	$domicilio_provincia =& $resultadosGenerales->createChild('domicilio_provincia');
	$domicilio_provincia->text($clnt->getDomicilio_provincia());
	 
	$domicilio_localidad =& $resultadosGenerales->createChild('domicilio_localidad');
	$domicilio_localidad->text($clnt->getDomicilio_localidad());
	 
	$domicilio_cp =& $resultadosGenerales->createChild('domicilio_cp');
	$domicilio_cp->text($clnt->getDomicilio_cp());
	 
	$tel_movil =& $resultadosGenerales->createChild('tel_movil');
	$tel_movil->text($clnt->getTel_movil());
	 
	$tel_otro =& $resultadosGenerales->createChild('tel_otro');
	$tel_otro->text($clnt->getTel_otro());
	 
	$acepta_basesycond =& $resultadosGenerales->createChild('acepta_basesycond');
	$acepta_basesycond->text($clnt->getAcepta_basesycond());
	 
	$estado =& $resultadosGenerales->createChild('estado');
	$estado->text($clnt->getEstado());
	 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>