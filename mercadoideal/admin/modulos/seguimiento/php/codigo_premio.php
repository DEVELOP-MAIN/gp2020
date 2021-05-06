<?php
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
$fecha 			= $cnj->getFecha();
$valor 				= $cnj->getValor();
$codigo 			= $cnj->getObservaciones();
$idcliente 	= $cnj->getIdcliente();
$idpremio	= $cnj->getIdpremio();

//selecciono y valido cliente y premio
if(!$clnt->select($idcliente) || ($clnt->getEstado()!='A' && $clnt->getEstado()!='C'))  {echo '[Error al traer los datos del cliente ó Verifique que el mismo esté activo]'; exit;}
if(!$prm->select($idpremio)) {echo '[Error al traer los datos del premio -> No se puede imprimir]'; exit;}

$cliente	= $clnt->getNombre().' '.$clnt->getApellido();
$email 		= $clnt->getEmail();
$premio	= $prm->getNombre();
$detalle	= $prm->getDetalle();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Impresi&#243;n de ticket de Canje</title>
</head>

<body style=" margin:0; padding:0">
	<div id="content" style="max-width:650px; width:100%;background: #E3E3E3">
		<div id="top" style="max-width:650px; width:100%; height:117px; border-bottom: solid 8px #facf35; background:#20abe2">
			<img src="http://quilmes-dev.aper.net/mails/img/mercadoideal.png"alt="" style="display:block; float:left; max-width:182px; width:50%"/>
			<h1 style="font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; display:block; float:right; color:#FFF; text-align:right; font-size:18px; margin-top:40px; padding-right:2%; width:48%">Código de canje</h1>
		</div>
		<div id="text" style="max-width:600px; width:94%; height:auto; padding-left:3%; padding-right:3%; padding-top:40px; font-size:16px; font-family:Arial, Helvetica, sans-serif; background-color:#e4e4e4; border-bottom:solid 8px #C1C1C1">
			Cliente: <?php echo $cliente;?> [<?php echo $email;?>].
			<div style="float:right;"><a href="#" onClick="window.print();"><img src="../../../img/bt_imprimir.png" width="25" height="25" border="0" align="absmiddle"></a></div>
			<h3 style="display:block; background:#FFF; text-align:center; color:#20abe2; width:100%; padding:8px 0; margin-bottom:0px"><?php echo $premio;?></h3>
			<h1 style="display:block; background:#FFF; text-align:center; color:#20abe2; width:100%; padding:5px 0; margin-top:1px"><?php echo $codigo;?></h1>
			<p style="font-size:11px; color:#636363"><?php echo $detalle;?></p>
		</div>
	</div>
</body>
</html>
