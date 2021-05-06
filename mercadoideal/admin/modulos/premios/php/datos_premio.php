<?php
//Este php retorna los datos que hay en la tabla 'premios' del premio seleccionado
require_once '../../../php/class/class.premio.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//armo los datos del premio
$idpremio = $_GET['c'];

//incio el objeto
$prm = new premio();
$prm->select($idpremio);
if($prm->getIdpremio()!='')
	printResults($prm);
else
	printErrorXML(65, 'No se encontraron datos para esta premio');

//funcion de envio de resultados XML para esta pagina
function printResults($prm) 
{
 	header('Content-type:text/xml;charset="utf-8"');
 	$xmlDoc = new MiniXMLDoc();
 	$xmlRoot =& $xmlDoc->getRoot();
 	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 1);  
		 
	$id =& $resultadosGenerales->createChild('id');
	$id->text($prm->getIdpremio());
	
	$nombre =& $resultadosGenerales->createChild('nombre');
	$nombre->text('<![CDATA['.$prm->getNombre().']]>');
	
	$nombre =& $resultadosGenerales->createChild('nombre_ch');
	$nombre->text('<![CDATA['.$prm->getNombre_ch().']]>');
	
	$tipo =& $resultadosGenerales->createChild('tipo');
	$tipo->text($prm->getTipo());
	
	$imagen =& $resultadosGenerales->createChild('imagen');
	$imagen->text('<![CDATA['.$prm->getImagen().']]>');
		
	$idcampania =& $resultadosGenerales->createChild('idcampania');
	$idcampania->text($prm->getIdcampania());
		
	$detalle =& $resultadosGenerales->createChild('detalle');
	$detalle->text('<![CDATA['.$prm->getDetalle().']]>');
	
	$detalle =& $resultadosGenerales->createChild('detalle_ch');
	$detalle->text('<![CDATA['.$prm->getDetalle_ch().']]>');
	
	$puntos =& $resultadosGenerales->createChild('puntos');
	$puntos->text($prm->getValor());
	
	$chances =& $resultadosGenerales->createChild('chances');
	$chances->text($prm->getChances());
	
	$stock =& $resultadosGenerales->createChild('stock');
	$stock->text($prm->getStock_inicial());
	
	if($prm->getStockReal($prm->getIdpremio())!=0)
	{
		$stock_real =& $resultadosGenerales->createChild('stock_real');
		$stock_real->text($prm->getStockReal($prm->getIdpremio()));
	}
	else
	{
		$stock_real =& $resultadosGenerales->createChild('stock_real');
		$stock_real->text('0');
	}
	
	$vigente_desde =& $resultadosGenerales->createChild('vigente_desde');
	$vigente_desde->text($prm->getVigencia_desde());
	
	$vigente_hasta =& $resultadosGenerales->createChild('vigente_hasta');
	$vigente_hasta->text($prm->getVigencia_hasta());
		
	$estado =& $resultadosGenerales->createChild('estado');
	$estado->text($prm->getEstado());
		
	$origen =& $resultadosGenerales->createChild('origen');
	$origen->text($prm->getOrigen());
		
	$garantia =& $resultadosGenerales->createChild('garantia');
	$garantia->text($prm->getGarantia());
		
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}
?>