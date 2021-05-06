<?php
//error_reporting(0);
header('Content-Type: application/vnd.ms-excel');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Disposition: attachment; filename=puntos_asignados_al_'.date('d-m-y').'.xls');

require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

$ls = new listado();
$listado_puntos = $ls->getPlanillaPuntos($_GET['d'],$_GET['h'],$_GET['p']);
$intervalo = '';
if($_GET['d'] != '') $intervalo .= ' DESDE '.$_GET['d'];
if($_GET['h'] != '') $intervalo .= ' HASTA '.$_GET['h'];
$nro = count($listado_puntos);
if($nro>0) 
{
	$data = '<html><head><style type="text/css"><!-- .num {mso-number-format:General;}.text {	mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style><title>PUNTOS ASIGNADOS</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2"><tr><td colspan="7" align="center"><font face="arial" size="2"><strong>LISTADO DE PUNTOS ASIGNADOS'.$intervalo.'</strong></td></tr>';
	$data .= '<TR>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA DE CARGA</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA ASIGNACION</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ID</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>SUPERMERCADO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>PUNTOS</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>MOTIVO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>OBSERVACIONES</strong></TD>';
	$data .= '</TR>';
	
	for($i=0;$i<$nro;$i++)
	{
		$data .=  '<TR>';
			$data .=  '<TD valign="top" class=\'date\'>'.$listado_puntos[$i]['fecha_carga'].'</TD>';
			$data .=  '<TD valign="top" class=\'date\'>'.$listado_puntos[$i]['fecha'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_puntos[$i]['idcliente'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_puntos[$i]['nombre'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_puntos[$i]['puntos'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_puntos[$i]['motivo'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_puntos[$i]['observaciones'].'</TD>';
		$data .=  '</TR>';
	}
	$data .=  '</TABLE>';
	echo $data; 
}
else
{
	$data = '<html><head><title>LISTADO DE PUNTOS ASIGNADOS</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2">';	
	$data .=  '<TR>';
	$data .=  '<TD BGCOLOR="#CCCCCC"><strong>NO HAY PUNTOS ASIGNADOS PARA LOS SUPERMERCADOS ADHERIDOS</TD>';
	$data .=  '</TR>';
	$data .=  '</TABLE>';
	echo $data; 
}
?>