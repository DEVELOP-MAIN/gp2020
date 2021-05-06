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

$ls = new listado();
$listado_clientes = $ls->getClientesSinPaginar($_GET['b'],$_GET['st']);
$fin = count($listado_clientes);
if($fin>0) 
{
	$data = '
	<html>
		<head>
			<style type="text/css"><!-- .num {mso-number-format:General;}.text {	mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style>
			<title>SUPERMERCADOS</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="18" align="center"><font face="arial" size="2"><strong>LISTADO DE SUPERMERCADOS</strong></td>
				</tr>';
	$data .= '<TR>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ID</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>COD UNICO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>COD CLIENTE</strong></TD>';	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA LAST INGRESO</strong></TD>';	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>HORA LAST INGRESO</strong></TD>';	
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>GRUPO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>SUPERMERCADO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA ALTA</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>E-MAIL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>CLAVE</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>DIRECCI&#211;N</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>LOCALIDAD</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>PROVINCIA</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>C&#211;DIGO POSTAL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TEL. FIJO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TEL. M&#211;VIL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ESTADO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>PUNTOS GENERADOS</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>PUNTOS CANJEADOS</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>BALANCE</strong></TD>';
	$data .= '</TR>';
	
	echo $data;

	for($i=0;$i<$fin;$i++)
	{
		//armo la fecha de alta
		if($listado_clientes[$i]['fechaalta']!='')
		{
			$flt = preg_split('/-/',$listado_clientes[$i]['fechaalta']);
			$fecha_alta = $flt[2].' '.$nombre_mes_abre[floor($flt[1])].' '.$flt[0];
		}
		else $fecha_alta = '';
		
		//armo la fecha de laslog
		if($listado_clientes[$i]['lastlog']!='')
		{
			$fll = preg_split('/ /',$listado_clientes[$i]['lastlog']);
			$fecha_ll = $fll[0];
			$hora_ll = $fll[1];
		}
		else {
			$fecha_ll = '';
			$hora_ll = '';
		}	
		
		//armo la dirección del supermercado
		$domicilio = '';
		if($listado_clientes[$i]['domicilio']!='') 
			$domicilio = $listado_clientes[$i]['domicilio']; 
		if($listado_clientes[$i]['localidad']!='') 
			$domicilio .= ' - '.$listado_clientes[$i]['localidad'];
		if($listado_clientes[$i]['provincia']!='') 
			$domicilio .= ' - '.$listado_clientes[$i]['provincia'];
		if($listado_clientes[$i]['domicilio_cp']!='') 
			$domicilio .= ' ('.$listado_clientes[$i]['domicilio_cp'].')';
		
		/*armo los teléfonos del supermercado
		$telefonos = '';
		if($listado_clientes[$i]['tel_movil']!='') 
			$telefonos = 'M&#243;vil: '.$listado_clientes[$i]['tel_movil'].'<br />'; 
		if($listado_clientes[$i]['tel_otro']!='') 
			$telefonos .= 'Fijo: '.$listado_clientes[$i]['tel_otro'].'<br />';
		*/
		
		//seteo en cero puntos o canjes		
		$puntos = 0;				
		$canjes = 0;
		
		switch($listado_clientes[$i]['estado'])
		{
			case 'A': 
				$estado = 'activo';
				//puntos generados por el socio
				$puntos = $ls->getPuntosCliente($listado_clientes[$i]['idcliente']);		
				//puntos canjeados por el socio
				$canjes = $ls->getCanjesClienteTotal($listado_clientes[$i]['idcliente']);
				break;
			case 'I': $estado = 'inactivo';break;
			case 'P': $estado = 'preinscripto';break;
			case 'C': 
				$estado = 'confirmado';				
				//puntos generados por el socio
				$puntos = $ls->getPuntosCliente($listado_clientes[$i]['idcliente']);		
				//puntos canjeados por el socio
				$canjes = $ls->getCanjesClienteTotal($listado_clientes[$i]['idcliente']);				
				break;
			default: $estado = '';
		}
				
		
		//balance
		$balance = $puntos - $canjes;
		
		$data =  '<TR>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['idcliente'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['codigo_unico'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['codigo_cliente'].'</TD>';
			$data .=  '<TD valign="top">'.$fecha_ll.'</TD>';
			$data .=  '<TD valign="top">'.$hora_ll.'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['grupo'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['nombre'].'</TD>';
			$data .=  '<TD valign="top" class=\'text\'>'.$fecha_alta.'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['email'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['clave'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['domicilio'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['localidad'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['provincia'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['domicilio_cp'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['tel_otro'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['tel_movil'].'</TD>';
			$data .=  '<TD valign="top">'.$estado.'</TD>';
			$data .=  '<TD valign="top">'.$puntos.'</TD>';
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
}
else
{
	$data = '
	<html>
		<head>
			<title>SUPERMERCADOS</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
			<table border="1" cellspacing="2" cellpadding="2">';	
	$data .=  '<tr>';
	$data .=  '<td bgcolor="#CCCCCC"><strong>NO HAY SUPERMERCADOS REGISTRADOS </td>';
	$data .=  '</tr>';
	$data .=  '
			</table>
		</body>
	</html>';
	echo $data; 
}
?>