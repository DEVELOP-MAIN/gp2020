<?php
//error_reporting(0);
header('Content-Type: application/vnd.ms-excel');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Disposition: attachment; filename=Canjes_al_'.date('d-m-Y').'.xls');

require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

$ls = new listado();
$listado_canjes = $ls->getCanjesPlanillaSolicitados();
$nro = count($listado_canjes);
if($nro>0) 
{
	$data = '<html><head><style type="text/css"><!-- .num {mso-number-format:General;}.text {mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style><title>CANJES</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2"><TR><td colspan="9" align="center"><font face="arial" size="2"><strong>LISTADO DE CANJES</strong></td></tr>';
	$data .= '<TR>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>Nº PEDIDO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>VALOR Pts</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>CUIT</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>COD UNICO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>DESTINATARIO</strong></TD>';
	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ENTREGA DIRECCION</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ENTREGA LOCALIDAD</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ENTREGA PROVINCIA</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ENTREGA CP</strong></TD>';
	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TELEFONO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>EMAIL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA PEDIDO</strong></TD>';	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>Nº PRODUCTO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>PRODUCTO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>OBSERVACIONES</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ESTADO</strong></TD>';
	$data .= '</TR>';

	for($i=0;$i<$nro;$i++)
	{
		$data .=  '<TR>';
			$data .=  '<TD>'.$listado_canjes[$i]['idcanje'].'</TD>';
			$data .=  '<TD>'.$listado_canjes[$i]['valor'].'</TD>';
			$data .=  '<TD>'.$listado_canjes[$i]['cuit'].'</TD>';
			$data .=  '<TD>'.$listado_canjes[$i]['codigo_unico'].'</TD>';
			$data .=  '<TD>'.$listado_canjes[$i]['cliente'].'</TD>';
			
			
			$entrega_direccion = "";
			$entrega_localidad = "";
			$entrega_provincia = "";
			$entrega_cp = "";
			$entrega_arr = preg_split('/-/', $listado_canjes[$i]['lugar_entrega']);			
			if(isset($entrega_arr[0])) $entrega_direccion = trim($entrega_arr[0]);
			if(isset($entrega_arr[1])) $entrega_localidad = trim($entrega_arr[1]);
			if(isset($entrega_arr[2])) $entrega_provincia = trim($entrega_arr[2]);
			if(isset($entrega_arr[3])) $entrega_cp = trim($entrega_arr[3]);
			
			$data .=  '<TD>'.$entrega_direccion.'</TD>';
			$data .=  '<TD>'.$entrega_localidad.'</TD>';
			$data .=  '<TD>'.$entrega_provincia.'</TD>';
			$data .=  '<TD>'.$entrega_cp.'</TD>';
			
			$data .=  '<TD>'.$listado_canjes[$i]['movil'].'</TD>';			
			$data .=  '<TD>'.$listado_canjes[$i]['email'].'</TD>';
			$data .=  '<TD class=\'date\'>'.$listado_canjes[$i]['fecha'].'</TD>';			
			$data .=  '<TD>'.$listado_canjes[$i]['idpremio'].'</TD>';			
			$data .=  '<TD>'.$listado_canjes[$i]['premio'].'</TD>';
			$data .=  '<TD>'.$listado_canjes[$i]['observaciones'].'</TD>';
			$data .=  '<TD>'.$listado_canjes[$i]['estado'].'</TD>';
		$data .=  '</TR>';
	}
	$data .=  '</TABLE>';
	echo $data; 
}
else
{
	$data = '<html><head><title>CANJES</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2">';	
	$data .=  '<TR>';
	$data .=  '<TD BGCOLOR="#CCCCCC"><strong>NO SE ENCONTRARON CANJES</TD>';
	$data .=  '</TR>';
	$data .=  '</TABLE>';
	echo $data; 
}
?>