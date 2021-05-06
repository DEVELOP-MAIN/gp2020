<?php
//error_reporting(0);
header('Content-Type: application/vnd.ms-excel');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Disposition: attachment; filename=Premios_al_'.date('d-m-Y').'.xls');

require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.premio.php';
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

$ls = new listado();
$listado_premios = $ls->getPremiosPlanilla($_GET['b'],$_GET['c']);
$nro = count($listado_premios);
if($nro>0) 
{
	$prm 		= new premio();
	$cmpn	= new campania();
	$data = '
	<html>
		<head>
			<style type="text/css"><!-- .num {mso-number-format:General;}.text {mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style>
			<title>PREMIOS</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="8" align="center"><font face="arial" size="2"><strong>LISTADO DE PREMIOS</strong></td>
				</tr>';
	$data .= '<tr>';
	$data .= '<td bgcolor="#CCCCCC"><strong>PREMIO</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>TIPO</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>CAMPA&#209;A</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>STOCK INICIAL</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>STOCK REAL</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>COSTO (PUNTOS)</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>VIGENTE DESDE</strong></td>';
	$data .= '<td bgcolor="#CCCCCC"><strong>VIGENTE HASTA</strong></td>';
	$data .= '</tr>';

	for($i=0;$i<$nro;$i++)
	{
		//recupero el nombre de la campaña
		if($cmpn->select($listado_premios[$i]['idcampania']))				
			$nombre_campania = $cmpn->getNombre();
		else
			$nombre_campania = '';
		//determino el stock real del premio (stock inicial - canjes)
		$stock_real = '';
		$stock_real = $prm->getStockReal($listado_premios[$i]['idpremio']);				
		if($stock_real=='') $stock_real = 0;
		
		$data .=  '<tr valign="top">';
			$data .=  '<td>'.$listado_premios[$i]['nombre'].'</td>';
			$data .=  '<td>'.$listado_premios[$i]['tipo'].'</td>';
			$data .=  '<td>'.$nombre_campania.'</td>';
			$data .=  '<td>'.$listado_premios[$i]['stock_inicial'].'</td>';
			$data .=  '<td>'.$stock_real.'</td>';
			$data .=  '<td>'.$listado_premios[$i]['valor'].'</td>';
			$data .=  '<td class=\'date\'>'.$listado_premios[$i]['vigencia_desde'].'</td>';
			$data .=  '<td class=\'date\'>'.$listado_premios[$i]['vigencia_hasta'].'</td>';
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
			<title>PREMIOS</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">
				<tr>
					<td bgcolor="#CCCCCC"><strong>NO SE ENCONTRARON PREMIOS</td>
				</tr>
			</table>
		</body>
	</html>';
	echo $data; 
}
?>