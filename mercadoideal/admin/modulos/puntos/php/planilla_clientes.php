<?php
//error_reporting(0);
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
$listado_clientes = $ls->getClientesSinPaginar('','');
$fin = count($listado_clientes);
if($fin>0) 
{
	$data = '<html><head><style type="text/css"><!-- .num {mso-number-format:General;}.text {	mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style><title>SUPERMERCADOS</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2"><TR><td colspan="12" align="center"><font face="arial" size="2"><strong>LISTADO DE SUPERMERCADOS</strong></td></tr>';
	$data .= '<TR>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ID</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>SUPERMERCADO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA ALTA</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>E-MAIL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>CLAVE</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>DIRECCI&#211;N</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TEL&#201;FONOS</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>ESTADO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>PUNTOS ASIGNADOS DEL PER&#205;ODO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>PUNTOS CANJEADOS DEL PER&#205;ODO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>BALANCE DEL PER&#205;ODO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>BALANCE TOTAL</strong></TD>';
	$data .= '</TR>';
	
	for($i=0;$i<$fin;$i++)
	{
		//armo la fecha de alta
		if($listado_clientes[$i]['fechaalta']!='')
		{
			$flt = preg_split('/-/',$listado_clientes[$i]['fechaalta']);
			$fecha_alta = $flt[2].' '.$nombre_mes_abre[floor($flt[1])].' '.$flt[0];
		}
		else $fecha_alta = '';
		
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
		
		//armo los teléfonos del supermercado
		$telefonos = '';
		if($listado_clientes[$i]['tel_movil']!='') 
			$telefonos = 'M&#243;vil: '.$listado_clientes[$i]['tel_movil'].'<br />'; 
		if($listado_clientes[$i]['tel_otro']!='') 
			$telefonos .= 'Fijo: '.$listado_clientes[$i]['tel_otro'].'<br />';
		
		switch($listado_clientes[$i]['estado'])
		{
			case 'A': $estado = 'activo';break;
			case 'I': $estado = 'inactivo';break;
			case 'P': $estado = 'preinscripto';break;
			default: $estado = '';
		}
		
		//puntos generados por el socio para el periodo considerado
		$puntos_periodo = $ls->getPuntosCliente2($listado_clientes[$i]['idcliente'],$_GET['d'],$_GET['h']);
		
		//puntos canjeados por el socio para el periodo considerado
		$canjes_periodo = $ls->getCanjesClienteTotal2($listado_clientes[$i]['idcliente'],$_GET['d'],$_GET['h']);
		
		//balance para el periodo considerado
		$balance_periodo = $puntos_periodo - $canjes_periodo;
		
		//histórico de puntos generados por el socio
		$puntos_totales = $ls->getPuntosCliente($listado_clientes[$i]['idcliente']);
		
		//histórico de puntos canjeados por el socio
		$canjes_totales = $ls->getCanjesClienteTotal($listado_clientes[$i]['idcliente']);
		
		//balance histórico
		$balance_historico = $puntos_totales - $canjes_totales;
		
		$data .=  '<TR>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['idcliente'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['nombre'].'</TD>';
			$data .=  '<TD valign="top" class=\'text\'>'.$fecha_alta.'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['email'].'</TD>';
			$data .=  '<TD valign="top">'.$listado_clientes[$i]['clave'].'</TD>';
			$data .=  '<TD valign="top">'.$domicilio.'</TD>';
			$data .=  '<TD valign="top">'.$telefonos.'</TD>';
			$data .=  '<TD valign="top">'.$estado.'</TD>';
			$data .=  '<TD valign="top">'.$puntos_periodo.'</TD>';
			$data .=  '<TD valign="top">'.$canjes_periodo.'</TD>';
			$data .=  '<TD valign="top">'.$balance_periodo.'</TD>';
			$data .=  '<TD valign="top">'.$balance_historico.'</TD>';
		$data .=  '</TR>';
	}
	$data .=  '</TABLE>';
	echo $data; 
}
else
{
	$data = '<html><head><title>SUPERMERCADOS</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2">';	
	$data .=  '<TR>';
	$data .=  '<TD BGCOLOR="#CCCCCC"><strong>NO HAY SUPERMERCADOS REGISTRADOS </TD>';
	$data .=  '</TR>';
	$data .=  '</TABLE>';
	echo $data; 
}
?>
