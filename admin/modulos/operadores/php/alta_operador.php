<?php
require_once '../../../php/class/class.usuario.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['frm_alta_tipo']) || !isset($_GET['frm_alta_usuario']) || !isset($_GET['frm_alta_clave']))	{printErrorXML(0, 'Faltan datos en el envio');	exit;}	

//genero el nuevo usuario de sistema
$us = new usuario();

if(isset($_GET['frm_alta_tipo'])) 			$us->setTipo($_GET['frm_alta_tipo']);
if(isset($_GET['frm_alta_usuario'])) $us->setUsuario($_GET['frm_alta_usuario']);
if(isset($_GET['frm_alta_clave'])) 		$us->setClave($_GET['frm_alta_clave']);

if($us->validaInsert()) 
{
	$us->insert();
	printResultadoOK(1, 'El nuevo usuario del sistema ha sido agregado');
}
else printResultadoMal(0, 'Ya existe otra persona con el mismo nombre de usuario y clave');	

//funcion de envio de resultados XML para esta pagina
function printResultadoOK($result, $msg) {
	 header('Content-type:text/xml;charset="utf-8"');
	 $xmlDoc = new MiniXMLDoc();
	 $xmlRoot =& $xmlDoc->getRoot();
	 $resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	 $resultadosGenerales->attribute('resultado', $result);
	 $resultadosGenerales->attribute('mensaje', $msg);
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