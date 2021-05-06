<?php
require_once '../../../php/class/class.canje.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

if(!isset($_SESSION))	session_start();

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['c']) || !isset($_GET['e']))	{printErrorXML(0, 'Faltan datos en el envio');	exit;}	

$idcanje	= $_GET['c'];
$estado	= $_GET['e'];
$idusuario	= $_SESSION['QLMS_idusuario'];
$cnj = new canje();

if($cnj->select($idcanje))
{
	$idcliente = $cnj->getIdcliente();
	$cli = new cliente();
	if($cli->select($idcliente)) 
	{
		if($cnj->cambiaEstado($idcanje,$estado))
		{
			$cliente = $cli->getRazon_social();
			printResultadoOK(1, 'El estado del canje ha sido actualizado', $cliente, $estado); 
		}	
	} 
	else	printResultadoMal(0, 'Ha habido un error ya que no se encuentra el cliente que origino el canje');
}
else	printResultadoMal(0, 'Ha habido un error ya que no se encuentra el canje que desea modificar');

//funcion de envio de resultados XML para esta pagina
function printResultadoOK($result, $msg, $cliente, $estadonuevo) {
	 header('Content-type:text/xml;charset="utf-8"');
	 $xmlDoc = new MiniXMLDoc();
	 $xmlRoot =& $xmlDoc->getRoot();
	 $resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	 $resultadosGenerales->attribute('resultado', $result);
	 $resultadosGenerales->attribute('mensaje', $msg);
	 $resultadosGenerales->attribute('apellido', $cliente);
	 $resultadosGenerales->attribute('estadonuevo', $estadonuevo);
	 
	 $mensajes =& $resultadosGenerales->createChild('mensaje');
	 $mensajes->text($msg);	 
	 print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}

//funcion de envio de resultados XML para esta pagina
function printResultadoMal($result, $msg) {
	 header('Content-type:text/xml;charset="utf-8"');
	 $xmlDoc = new MiniXMLDoc();
	 $xmlRoot =& $xmlDoc->getRoot();
	 $resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	 $resultadosGenerales->attribute('resultado', $result);
	 $resultadosGenerales->attribute('mensaje',  $msg);
	 print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}	
?>