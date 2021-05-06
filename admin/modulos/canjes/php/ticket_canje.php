<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.canje.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/class/class.premio.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

if(!isset($_GET['idc']) || $_GET['idc']=='') {echo '[Error en los parametros recibidos]'; exit;}

$idc 		= $_GET['idc'];
$cnj 	= new canje();
$clnt 	= new cliente();
$prm	= new premio();

//valido el canje
if(!$cnj->select($idc)) {echo '[Error en los parametros recibidos}'; exit;} 
if($cnj->getEstado() != 'efectivizado') {echo '[Canje no efectivizado -> No se puede imprimir]'; exit;}

//obtengo datos del canje
$fecha = $cnj->getFecha();
$valor = $cnj->getValor();
$idcliente = $cnj->getIdcliente();
$idpremio = $cnj->getIdpremio();

$fecha_y_hora = $array = explode(' ', $fecha);
$arr_fecha = explode('-', $fecha_y_hora[0]);
$fdia = $arr_fecha[2];
$fmes = $arr_fecha[1];
$fano = $arr_fecha[0];
$fecha = $fdia.'/'.$fmes.'/'.$fano;
$fechavencimiento = date('d/m/Y', mktime(0,0,0,$fmes,$fdia+15,$fano));
//calculo vencimiento

//selecciono y valido cliente y premio
if(!$clnt->select($idcliente) || $clnt->getEstado()!='A')  {echo '[Error al traer los datos del cliente ó Verifique que el mismo esté activo]'; exit;}
if(!$prm->select($idpremio))  {echo '[Error al traer los datos del premio -> No se puede imprimir]'; exit;}

$cliente = $clnt->getNombre().' '.$clnt->getApellido();
$email = $clnt->getEmail();
$premio = $prm->getNombre();
$detalle = $prm->getDetalle();
$lugar_retiro = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Impresi&#243;n de ticket de Canje</title>
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 40px;
	margin-right: 0px;
	margin-bottom: 0px;
	text-align:center;
}
-->
</style>
<link href="../../../css/tickets.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div class="ticket">
		<div class="top">
			<img src="../../../img/ticket_canje.png" width="236" height="46" />
			<span>Ticket de canje de premios</span>
		</div>
		<br/><br/>
		<div class="texto">
			<span class="bentitle">Beneficiario:</span><span class="bendnititle">Email:</span>
			<span class="benname"><?php echo $cliente?></span><span class="bendni"><?php echo $email?></span>
		</div>
		<span class="resalto"><strong>FECHA DE VENCIMIENTO: <?php echo $fechavencimiento?></strong></span>
		<div class="texto2">
			<span class="title">Premio:</span><span class="title2">Local de entrega:</span> 
			<span class="item">
				<?php echo $premio?><br />
				<span class="descr"><?php echo $detalle;?></span>
			</span>
			<span class="item2"><?php echo $lugar_retiro?></span>
			<span class="title">Fecha Canje:</span><span class="title2">Operador:</span> 
			<span class="item"><?php echo $fecha?></span> <span class="item2"><?php echo $_SESSION['APA_usuario']?></span>
		</div>
		<div class="band">
			<div class="recuadro">Firma del operador:</div>
			<div class="img"><img src="barcode.php?code=<?php echo pongoCeros($idc,10)."000";?>&scale=1"></div>	
		</div>
		<br/><br/>
	</div>
	<!--<div style="width:298px; height:421px">
	<img src="img/ticket_canje.jpg" width="298" height="421" />
	</div>-->
</body>
</html>
