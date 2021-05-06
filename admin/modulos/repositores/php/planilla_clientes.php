<?php
@set_time_limit(300);
@error_reporting(0);

header('Content-Type: application/vnd.ms-excel');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Disposition: attachment; filename=Socios_al_'.date('d-m-Y').'.xls');

require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

if($_GET['ej']!=''){
	$f = preg_split('/,/',$_GET['ej']);
	$ej = $f[0];
}
else $ej = '';

if($_GET['jf']!=''){
	$f = preg_split('/,/',$_GET['jf']);
	$jf = $f[0];
}
else $jf = '';

if($_GET['gr']!=''){
	$f = preg_split('/,/',$_GET['gr']);
	$gr = $f[0];
}
else $gr = '';

$ls = new listado();
$listado_clientes = $ls->getClientesSinPaginar($_GET['b'],'2',$_GET['rg'],$ej,$jf,$gr);
$fin = count($listado_clientes);
if($fin>0){
	$data = '
	<html>
		<head>
			<style type="text/css"><!-- .num {mso-number-format:General;}.text {	mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style>
			<title>USUARIOS VENDEDORES</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="16" align="center"><font face="arial" size="2"><strong>LISTADO DE USUARIOS VENDEDORES</strong></td>
				</tr>';
	$data .= '<TR>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ID</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TIPO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>CODIGO</strong></TD>';	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>REGION</strong></TD>';	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>RANGO</strong></TD>';	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>EJECUTIVO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>JEFE</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>GERENTE</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>USUARIO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>E-MAIL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>CLAVE</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>DIRECCI&#211;N</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TELEFONO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>MILLAS GENERADAS</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>MILLAS CANJEADAS</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>BALANCE</strong></TD>';
	$data .= '</TR>';
	echo $data;

	for($i=0;$i<$fin;$i++){
		//seteo en cero millas y canjes
		$millas = 0;
		$canjes = 0;
		//millas generadas por el socio
		$millas = $ls->getPuntosCliente($listado_clientes[$i]['idsocio']);		
		//millas canjeadas por el socio
		$canjes = $ls->getCanjesClienteTotal($listado_clientes[$i]['idsocio']);
		//balance
		$balance = $millas - $canjes;

		$data =  '<TR>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['idsocio'].'</TD>';
			$data .=  '<TD valign="top">VENDEDORES</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['codigo'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['region'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['rango'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['ejecutivo'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['jefe'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['gerente'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['apellido'].','.$listado_clientes[$i]['nombre'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['email'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['clave'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['direccion'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['telefono'].'</TD>';
			$data .=  '<TD valign="top">'.$millas.'</TD>';
			$data .=  '<TD valign="top">'.$canjes.'</TD>';
			$data .=  '<TD valign="top">'.$balance.'</TD>';
		$data .=  '</TR>';
		echo $data;
	}
	$data =  '
			</table>
		</body>
	</html>';
	echo $data; 
}	else {
	$data = '
	<html>
		<head>
			<title>USUARIOS VENDEDORES</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">';	
	$data .=  '<tr>';
	$data .=  '	<td bgcolor="#CCCCCC"><strong>NO HAY USUARIOS DISTRIBUIDORES VENDEDORES</td>';
	$data .=  '</tr>';
	$data .=  '
			</table>
		</body>
	</html>';
	echo $data; 
}
?>