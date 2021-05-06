<?php
//error_reporting(0);
header('Content-Type: application/vnd.ms-excel');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Disposition: attachment; filename=listado_movimientos_al_'.date('d-m-Y').'.xls');

require_once 'traduccion.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/class/class.premio.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';

if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];

$clnt = new cliente();	
if(!$clnt->select($idcliente))
{
	header('location:index.php');
	exit;
}
else
{
	$razon_social = $clnt->getRazon_social();
	$movimientos = array();
	$movimientos = $clnt->getMovimientos('asc','');
	$nro = count($movimientos);
}

if($nro>0) 
{
	$data = '<html><head><title>BALANCE</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head><body><table border="1" cellspacing="2" cellpadding="2"><TR><td colspan="3" align="center"><font face="arial" size="2"><strong>LISTADO DE MOVIMIENTOS DE '.$razon_social.'</strong></td></tr>';
	$data .= '<TR>';
		$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA</strong></TD>';
		$data .= '<TD BGCOLOR="#CCCCCC"><strong>CONCEPTO</strong></TD>';
		$data .= '<TD BGCOLOR="#CCCCCC"><strong>PUNTOS</strong></TD>';
	$data .= '</TR>';

	$entradas	= 0;
	$canjes 			= 0;
	$saldo				= 0;
	$prm = new premio();	
	for($i=0;$i<$nro;$i++) 
	{
		switch($movimientos[$i]['tipo'])
		{
			case 'canje' : $canjes = $canjes + $movimientos[$i]['numero']; break;
			default	: $entradas = $entradas + $movimientos[$i]['numero']; break;
		}
		$saldo = $entradas - $canjes;
		
		//si hay premio involucrado, obtengo su nombre
		if($movimientos[$i]['tipo'] == 'canje')
		{
			if($prm->select($movimientos[$i]['idpremio']))
				$premio = $prm->getNombre();
			else
				$premio = '';
		}
		
		$data .=  '<TR>';
			$data .=  '<TD valign="top">'.$movimientos[$i]['fecha'].'</TD>';
			
			
			if($movimientos[$i]['tipo'] == 'canje') {				
				$data .=  '<TD valign="top">'.$movimientos[$i]['tipo'].'-'.$premio;
				if($movimientos[$i]['estado']=="anulado" || $movimientos[$i]['estado']=="devuelto" )
					$data .=  ' - '.strtoupper($movimientos[$i]['estado']);
				$data .='</TD>';			
				$data .=  '<TD valign="top">-'.$movimientos[$i]['numero'].'</TD>';
			} else {
				$data .=  '<TD valign="top">'.$movimientos[$i]['tipo'].' '.$movimientos[$i]['observaciones'].'</TD>';			
				$data .=  '<TD valign="top">'.$movimientos[$i]['numero'].'</TD>';
			}	
		$data .=  '</TR>';
	}
		$data .=  '<TR>';
			$data .=  '<TD colspan="2" align="right"><strong>SALDO</strong></TD>';
			$data .=  '<TD align="right">'.$saldo.'</TD>';
		$data .=  '</TR>';
	$data .=  '</TABLE>';
	echo $data; 
}
else
{
	$data = '<html><head><title>BALANCE</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head><body><table border="1" cellspacing="2" cellpadding="2">';	
	$data .=  '<TR>';
	$data .=  '<TD BGCOLOR="#CCCCCC"><strong>NO HAY MOVIMIENTOS REGISTRADOS </TD>';
	$data .=  '</TR>';
	$data .=  '</TABLE>';
	echo $data; 
}
?>
