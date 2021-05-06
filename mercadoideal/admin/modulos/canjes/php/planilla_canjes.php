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
$listado_canjes = $ls->getCanjesPlanilla($_GET['b'],$_GET['e'],$_GET['d'],$_GET['h'],$_GET['t']);
$nro = count($listado_canjes);
if($nro>0) 
{
	$data = '
	<html>
		<head>
			<style type="text/css"><!-- .num {mso-number-format:General;}.text {mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style>
			<title>CANJES</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="15" align="center"><font face="arial" size="2"><strong>LISTADO DE CANJES</strong></td>
				</tr>';
	$data .= '<tr>';
	$data .= '<td bgcolor="#CCCCCC"><strong>Nº PEDIDO</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>VALOR Pts</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>CUIT</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>COD. UNICO</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>DESTINATARIO</strong></td>';
	
	$data .= '<td bgcolor="#CCCCCC"><strong>ENTREGA DIRECCION</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>ENTREGA LOCALIDAD</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>ENTREGA PROVINCIA</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>ENTREGA CP</strong></td>';
	
	$data .= '<td bgcolor="#CCCCCC"><strong>TELEFONO</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>EMAIL</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>FECHA PEDIDO</strong></td>';	
	$data .= '<td bgcolor="#CCCCCC"><strong>Nº PRODUCTO</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>PRODUCTO</strong></td>';	
	$data .= '<td bgcolor="#CCCCCC"><strong>OBSERVACIONES</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>ESTADO</strong></td>';
	$data .= '</tr>';

	for($i=0;$i<$nro;$i++)
	{
		$data .=  '<tr valign="top">';
			$data .=  '<td>'.$listado_canjes[$i]['idcanje'].'</td>';
			$data .=  '<td>'.$listado_canjes[$i]['valor'].'</td>';
			$data .=  '<td>'.$listado_canjes[$i]['cuit'].'</td>';
			$data .=  '<td>'.$listado_canjes[$i]['codigo_unico'].'</td>';
			$data .=  '<td>'.$listado_canjes[$i]['cliente'].'</td>';
			
			$entrega_direccion = '';
			$entrega_localidad = '';
			$entrega_provincia = '';
			$entrega_cp = '';
			$entrega_arr = preg_split('/-/', $listado_canjes[$i]['lugar_entrega']);			
			if(isset($entrega_arr[0])) $entrega_direccion = trim($entrega_arr[0]);
			if(isset($entrega_arr[1])) $entrega_localidad = trim($entrega_arr[1]);
			if(isset($entrega_arr[2])) $entrega_provincia = trim($entrega_arr[2]);
			if(isset($entrega_arr[3])) $entrega_cp = trim($entrega_arr[3]);
			
			$data .=  '<td>'.$entrega_direccion.'</td>';
			$data .=  '<td>'.$entrega_localidad.'</td>';
			$data .=  '<td>'.$entrega_provincia.'</td>';
			$data .=  '<td>'.$entrega_cp.'</td>';
			
			$data .=  '<td>'.$listado_canjes[$i]['movil'].'</td>';			
			$data .=  '<td>'.$listado_canjes[$i]['email'].'</td>';
			$data .=  '<td class=\'date\'>'.$listado_canjes[$i]['fecha'].'</td>';			
			$data .=  '<td>'.$listado_canjes[$i]['idpremio'].'</td>';			
			$data .=  '<td>'.$listado_canjes[$i]['premio'].'</td>';
			$data .=  '<td>'.$listado_canjes[$i]['observaciones'].'</td>';
			$data .=  '<td>'.$listado_canjes[$i]['estado'].'</td>';
		$data .=  '</tr>';
	}
	$data .=  '
			</table>
		</body>
	</html>';
	echo $data; 
}
else
{
	$data = '
	<html>
		<head>
			<title>CANJES</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">
				<tr>
					<td bgcolor="#CCCCCC"><strong>NO SE ENCONTRARON CANJES</td>
				</tr>
			</table>
		</body>
	</html>';
	echo $data; 
}
?>