<?php
//Este php retorna los datos que hay en la tabla 'socios' del usuario seleccionado
require_once '../../../php/class/class.socio.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos del usuario
$idcliente = $_GET['c'];

//incio el objeto
$clnt = new socio();
$clnt->select($idcliente);
if($clnt->getIdsocio()!='')
	printResults($clnt);
else
	printErrorXML(65, 'No se encontraron datos para este usuario');

//funcion de envio de resultados XML para esta pagina
function printResults($clnt){
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);

	$region =& $resultadosGenerales->createChild('region');
	$region->text($clnt->getRegion());

	$codigo =& $resultadosGenerales->createChild('codigo');
	$codigo->text($clnt->getCodigo());

	$rango =& $resultadosGenerales->createChild('rango');
	$rango->text($clnt->getRango());

	$ejecutivo =& $resultadosGenerales->createChild('ejecutivo');
	$ejecutivo->text($clnt->getEjecutivo());

	$jefe =& $resultadosGenerales->createChild('jefe');
	$jefe->text($clnt->getJefe());

	$gerente =& $resultadosGenerales->createChild('gerente');
	$gerente->text($clnt->getGerente());

	$nombre =& $resultadosGenerales->createChild('nombre');
	$nombre->text($clnt->getNombre());

	$apellido =& $resultadosGenerales->createChild('apellido');
	$apellido->text($clnt->getApellido());

	$email =& $resultadosGenerales->createChild('email');
	$email->text($clnt->getEmail());

	$clave =& $resultadosGenerales->createChild('clave');
	$clave->text($clnt->getClave());

	$direccion =& $resultadosGenerales->createChild('direccion');
	$direccion->text($clnt->getDireccion());

	$telefono =& $resultadosGenerales->createChild('telefono');
	$telefono->text($clnt->getTelefono());

	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>