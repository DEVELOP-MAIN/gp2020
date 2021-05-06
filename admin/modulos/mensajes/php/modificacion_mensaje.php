<?php
require_once '../../../php/class/class.mensaje.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';
require_once '../../../php/traduccion.php';

//decodifico desde utf-8
//$_POST = decode($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idmensaje_rta'])) {printErrorXML(0, 'Faltan datos en el envio'); exit;}
	
//genero el mensaje
$mnsj = new mensaje();
if($mnsj->select($_POST['idmensaje_rta']))
{	
	//recupero el dato del mail de la persona
	$idcliente = $mnsj->getIdcliente();
	$mailto = '';
	
	$clnt = new cliente();
	if($clnt->select($idcliente)) $mailto = $clnt->getEmail();	
	
	//recupero el mail de la persona logueada
	$mailfrom = 'mercadoideal@aper.net';
	
	//mando el mail en forma de texto plano junto al texto del mail
	$textorespuesta = $_POST['frm_rta_respuesta'];
	$textorespuesta .= '\n -------------Mensaje recibido-------------- \n';
	$textorespuesta .= $mnsj->getMensaje();
	
	//seteo el texto como texto + '---- RTA ----' + respuesta
	$nuevotexto = $mnsj->getMensaje();
	$nuevotexto .= '\n<strong>Respuesta Enviada por '.$mailfrom.'</strong>\n';
	$nuevotexto .= $_POST['frm_rta_respuesta'];
	$mnsj->setMensaje($nuevotexto);
	
	//seteo el estado a repondido
	$mnsj->setEstado('respondido');
	$mnsj->update($_POST['idmensaje_rta']);
	$mnsj->sendRespuesta($mailto);
	
	printResultadoOK(1, 'Su respuesta al mensaje ha sido registrada');
}	
else 
{
	$msg = 'Ha habido un error ya que no se encuentra el mensaje que desea editar';
	printResultadoMal(0, $msg);
}			

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