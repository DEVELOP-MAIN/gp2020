<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/class/class.concurso.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);
$desde 				= validaVars($_GET['d']);
$hasta 				= validaVars($_GET['h']);
$idconcurso	= validaVars($_GET['c']);

//recupero el nombre del concurso
$cncrs = new concurso();
if($cncrs->select($idconcurso)) $titulo = $cncrs->getTitulo(); else $titulo = 'este concurso';

$ls = new listado();
$listado_participantes = $ls->getParticipantesConcursoSinPaginar($desde='',$hasta='',$idconcurso);
$fin = count($listado_participantes);

//error_reporting(0);
header('Content-Type: application/vnd.ms-excel');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Disposition: attachment; filename=Participantes_en_'.$titulo.'_al_'.date('d-m-Y').'.xls');

if($fin>0) 
{
	$data = '<html><head><style type="text/css"><!-- .num {mso-number-format:General;}.text {	mso-number-format:"\@";/*force text*/}.date {mso-number-format:"Short Date";}--></style><title>PARTICIPANTES</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2"><TR><td colspan="7" align="center"><font face="arial" size="2"><strong>LISTADO DE PARTICIPANTES A '.$titulo.'</strong></td></tr>';
	$data .= '<TR>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>FECHA INSCRIPCI&#211;N</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>RAZ&#211;N SOCIAL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TITULAR</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>EMAIL</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>CUIT</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>DOMICILIO</strong></TD>';
	$data .= '<TD BGCOLOR="#CCCCCC"><strong>TEL&#201;FONOS</strong></TD>';
	$data .= '</TR>';
	$clnt = new cliente();
	for($i=0;$i<$fin;$i++)
	{
		//reseteo las variables del participante
		$razon_social = '';
		$titular = '';
		$email = '';
		$cuit = '';
		$domicilio = '';
		$telefonos = '';
		
		//recupero los datos del supermercado inscripto
		if($clnt->select($listado_participantes[$i]['idcliente']))
		{
			$razon_social = $clnt->getRazon_social();
			$titular = $clnt->getNombre().' '.$clnt->getApellido();
			$email = $clnt->getEmail();
			$cuit = $clnt->getCuit();
			$domicilio = $clnt->getDomicilio().'<br />'.$clnt->getDomicilio_localidad().'<br />'.$clnt->getDomicilio_provincia();
			$telefonos = 'M&#243;vil: '.$clnt->getTel_movil().'<br />Fijo: '.$clnt->getTel_otro();
		}
		
		$data .=  '<TR>';
			$data .=  '<TD valign="top" class=\'date\'>'.$listado_participantes[$i]['fecha'].'</TD>';
			$data .=  '<TD valign="top">'.$razon_social.'</TD>';
			$data .=  '<TD valign="top">'.$titular.'</TD>';
			$data .=  '<TD valign="top">'.$email.'</TD>';
			$data .=  '<TD valign="top">'.$cuit.'</TD>';
			$data .=  '<TD valign="top">'.$domicilio.'</TD>';
			$data .=  '<TD valign="top">'.$telefonos.'</TD>';
		$data .=  '</TR>';
	}
	$data .=  '</TABLE>';
	echo $data; 
}
else
{
	$data = '<html><head><title>PARTICIPANTES</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><table border="1" cellspacing="2" cellpadding="2">';	
	$data .=  '<TR>';
	$data .=  '<TD BGCOLOR="#CCCCCC"><strong>NO HAY PARTICIPANTES REGISTRADOS EN '.$titulo.'</TD>';
	$data .=  '</TR>';
	$data .=  '</TABLE>';
	echo $data; 
}
?>
