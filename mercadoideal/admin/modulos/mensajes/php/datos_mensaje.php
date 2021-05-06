<?php
//Este php retorna los datos que hay en la tabla 'mensajes' del mensaje seleccionado
require_once '../../../php/class/class.mensaje.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

putenv("LANGUAGE=zh_CN.UTF-8");
putenv('LANG=zh_CN.UTF-8');
putenv("LC_ALL={zh_CN.UTF-8}");		
setlocale(LC_ALL, NULL);
setlocale(LC_TIME, "");
setlocale(LC_ALL, 'zh_CN.UTF-8');

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['c'])) {printErrorXML(65, 'Faltan parametros para este modulo');	exit;}

//armo los datos del mensaje
$mnsj = $_GET['c'];

//incio el objeto
$mensaje = new mensaje();
$mensaje->select($mnsj);
if($mensaje->getIdmensaje()!='')
{
	printResults($mensaje);
	$mensaje->setEstado('leido');
	$mensaje->update($mnsj);
}
else	printErrorXML(65, 'No se encontraron los datos de este mensaje');

//funcion de envio de resultados XML para esta pagina
function printResults($mensaje)
{
	$nombre_mes_abre[01] 	= 'ENE';
	$nombre_mes_abre[02] 	= 'FEB';
	$nombre_mes_abre[03] 	= 'MAR';
	$nombre_mes_abre[04] 	= 'ABR';
	$nombre_mes_abre[05] 	= 'MAY';
	$nombre_mes_abre[06] = 'JUN';
	$nombre_mes_abre[07] = 'JUL';
	$nombre_mes_abre[08] = 'AGO';
	$nombre_mes_abre[09] = 'SEP';
	$nombre_mes_abre[10] = 'OCT';
	$nombre_mes_abre[11] = 'NOV';
	$nombre_mes_abre[12] = 'DIC';
	$fecha 	= '';
	$motivo	= '';
	
	$fe = $mensaje->getFecha();
	if($fe != '' )
	{
		$f = preg_split('/ /',$fe);
		$f2 = preg_split('/-/',$f[0]);
		$fecha = $f2[2].'-'.$nombre_mes_abre[floor($f2[1])].'-'.$f2[0].' '.$f[1];
	}
	$clnt = new cliente();
	if($mensaje->getIdcliente() != '')
	{
		if($clnt->select($mensaje->getIdcliente())) {
			$remitente = $clnt->getApellido().', '.$clnt->getNombre();
			$remitente_codigounico = $clnt->getCodigo_unico();
			$remitente_razonsocial = $clnt->getRazon_social();
		}	
	}	
	
	if($mensaje->getEstado()!='')
		$estado = $mensaje->getEstado();
	
	$texto = nl2br($mensaje->getMensaje());
			
	echo '
	<table width="70%" border="0" cellspacing="0" cellpadding="0">
		<tr class="textoPetroleo12"> 
			<td width="50%" align="left"><strong>Fecha: </strong>'.$fecha.'</td>
			<td width="50%" align="left"><strong>Estado:</strong> '.$estado.'</td>
		</tr>
		<tr class="textoPetroleo12"> 
			<td width="50%" align="left"><strong>Codigo Unico: </strong>'.$remitente_codigounico.'</td>
			<td width="50%" align="left"><strong>Nombre y Apellido:</strong> '.$remitente.'</td>
		</tr>
		<tr class="textoPetroleo12"> 
			<td colspan="2" align="left"><strong>Razon Social: </strong>'.$remitente_razonsocial.'</td>			
		</tr>
		<tr class="textoPetroleo12"> 
			<td colspan="2" align="left"><strong>Mensaje</strong></td>			
		</tr>
		<tr class="textoPetroleo12b"> 
			<td colspan="2" align="left">'.$texto.'</td>			
		</tr>
	</table>';
}
?>