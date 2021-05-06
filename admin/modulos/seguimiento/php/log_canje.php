<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_GET['c']))	{echo 'error al traer datos'; exit;}	

//traigo el historico de movimientos del canje
$ls = new listado();
$historico = $ls->getLogscanje($_GET['c']);
$nro = count($historico);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Panel de control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	text-decoration: none;
}
.titulo {
	border-bottom: solid 1px #CCCCCC;
	font-size: 12px;
	color: #FFF;
}
.operario {
	border-bottom: solid 1px #CCCCCC;
	font-size: 12px;
}
.accion {
	border-bottom: solid 1px #CCCCCC;
	text-transform:uppercase;
	color: #0e5796;
	font-size: 12px;
}
.ttCanje {
	color: #000;
	font-size: 15px;
	padding-top:5px;
	padding-bottom:5px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td width="30%" bgcolor="#ffcc18" class="titulo"><strong>Fecha</strong></td>
			<td width="40%" bgcolor="#ffcc18" class="titulo"><strong>Operador</strong></td>
			<td width="30%" bgcolor="#ffcc18" class="titulo"><strong>Acci&#243;n</strong></td>
		</tr>
		<?php
		for($i=0;$i<$nro;$i++){
			if($historico[$i]['fecha']!=''){ 
				$fe1 = preg_split('/ /',$historico[$i]['fecha']);
				$fe2 = preg_split('/-/',$fe1[0]);
				$fecha_estado = $fe2[2].$nombre_mes_abre[floor($fe2[1])].$fe2[0].' '.$fe1[1];
			}
			else $fecha_estado	= '';
		?>
		<tr>
			<td class="operario"><?php echo $fecha_estado.' '.$hora_estado?></td>
			<td class="operario"><?php echo $historico[$i]['usuario']?></td>
			<td class="accion"><?php echo $historico[$i]['estado']?></td>
		</tr>
		<?php }?>
	</table>
</body>