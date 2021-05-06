<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/class/class.premio.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';

if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];

$clnt = new cliente();
$movimientos = array();
if(!$clnt->select($idcliente))
{
	header('location:index.php');
	exit;
}
else
{
	$puntos_totales 	= $clnt->dameSumaXConcepto('puntos','puntos','','');
	$puntos_wechat 		= $clnt->dameSumaXConcepto('puntos','puntos','wechat','');
	$puntos_wechat		= $puntos_wechat + $clnt->dameSumaXConcepto('puntos','puntos','desafios','');
	
	$puntos_auditoria	= $clnt->dameSumaXConcepto('puntos','puntos','auditoria','');
	$puntos_volumen		= $clnt->dameSumaXConcepto('puntos','puntos','volumen','');
	$puntos_cobertura	= $clnt->dameSumaXConcepto('puntos','puntos','cobertura','');	
	$puntos_otros 		= $puntos_totales - $puntos_wechat - $puntos_auditoria - $puntos_volumen - $puntos_cobertura;
	$canjes_totales 	= $clnt->dameSumaXConcepto('canjes','valor','','anulado','devuelto');
	$saldo 				= $puntos_totales - $canjes_totales;
	$movimientos 		= $clnt->getMovimientos('desc',10);
	$nro = count($movimientos);
	$_SESSION['QLMSF_puntos'] = $saldo;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Mercado Ideal | Quilmes - Brahma</title>
<link rel="shortcut icon" href="https://www.mercadoideal.com.ar/favicon.ico?nocache=0706" />
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="assets/js/fancybox/jquery.fancybox.css" rel="stylesheet" />	
	<!-- Custom CSS -->
	<link href="fonts/fonts-esp.css?nocache=0706" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=0706" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<?php include_once('cabecera.php');?>
	<!-- Contenido -->
	<div class="container">
		<div class="row">
			<div class="col-lg-12 ttBusca">
				<div class="tt-Canjes"><h1><i class="fa fa-bar-chart"></i> <?php echo _('Balance de puntos');?></h1></div>
				<div class="separa"></div>
			</div>

		<div class="mi-balance">
			<div class="col-lg-12">
				<div class="col-sm-6 col-lg-6 col-md-6">
					<div class="table-responsive">
					<table class="table table-hover table-bordered">
					<tbody>
						<tr><td class="active"><?php echo _('Ingresos por WeChat');?></td><td class="active" align="center"><?php echo $puntos_wechat;?></td></tr>
						<tr><td class="active"><?php echo _('Ingresos por Auditoria Trimestral');?></td><td class="active" align="center"><?php echo $puntos_auditoria;?></td></tr>
						<tr><td class="active"><?php echo _('Ingresos por Cobertura');?></td><td class="active" align="center"><?php echo $puntos_cobertura;?></td></tr>
						<tr><td class="active"><?php echo _('Ingresos por Volumen');?></td><td class="active" align="center"><?php echo $puntos_volumen;?></td></tr>
						<tr><td class="active"><?php echo _('Ingresos Varios');?></td><td class="active" align="center"><?php echo $puntos_otros;?></td></tr>
						<tr><td class="active restatexto"><?php echo _('Canjes');?></td><td class="active restapuntos" align="center">-<?php echo $canjes_totales;?></td></tr>
						<tr><td class="active saldo1"><div align="right"><strong><?php echo _('Saldo');?></strong></div></td><td class="active saldo1" align="center"><strong><?php echo $saldo;?></strong></td></tr>
					</tbody>
					</table>
					</div>
				</div>
				<div class="col-sm-6 col-lg-6 col-md-6 infobalance">
					<h4><?php echo _('MERCADO IDEAL, UN PROGRAMA PARA UN PUNTO DE VENTA IDEAL, COMO EL TUYO!!!');?></h4>
					<a href="el_programa.php" class="botonbalance2"><?php echo _('&iquest;C&oacute;mo sumar puntos?');?></a>
					<div style="clear:both; width:100%; height:1px"></div>
				</div>
				<div class="separa"></div>
			</div>
			<div class="col-lg-12" style="clear:both; width:100%;"><h3><?php echo _('&Uacute;ltimos movimientos');?></h3></div>
			<div class="col-sm-12">
				<div class="table-responsive">
					<table class="table table-hover table-bordered">
						<tbody>
							<tr>
							<th class="resalto" width="22%"><?php echo _('Fecha');?></th>
							<th class="resalto" width="63%"><?php echo _('Concepto');?></td>
							<th class="resalto" width="15%" style="text-align:center"><?php echo _('Puntos');?></th>
							</tr>
							<?php
							if($nro>0)
							{
								$prm = new premio();
								for($i=0;$i<$nro;$i++)
								{
									//doy formato a la fecha del movimiento
									if($movimientos[$i]['fecha']!='')
									{
										$fe1 = preg_split('/ /',$movimientos[$i]['fecha']);
										$fe2 = preg_split('/-/',$fe1[0]);
										$fecha_mov	= $fe2[2].'-'.$fe2[1].'-'.$fe2[0];
									}
									else
										$fecha_mov	= '';

									//si hay premio involucrado, obtengo su nombre
									if($movimientos[$i]['tipo'] == 'canje')
									{
										if($prm->select($movimientos[$i]['idpremio']))
											$premio = $prm->getNombre();
										else
											$premio = '';
									}
							?>
							<tr>
								<td class="active<?php if($movimientos[$i]['tipo'] == 'canje') echo ' restatexto';?>">
									<?php echo $fecha_mov;?>
								</td>
								<td class="active<?php if($movimientos[$i]['tipo'] == 'canje') echo ' restatexto';?>">
									<?php echo $movimientos[$i]['tipo'];?><?php if($movimientos[$i]['tipo'] == 'canje') echo ' - '.$premio;?>
									<?php if($movimientos[$i]['estado']=="devuelto" || $movimientos[$i]['estado']=="anulado") echo " - <span style='color:RED'>".strtoupper($movimientos[$i]['estado'])."</span>"?>									
									<?php if($movimientos[$i]['observaciones']!="") echo " - ".str_replace("Ingreso Manual | ","",$movimientos[$i]['observaciones'])?>
								</td>
								<td class="active<?php if($movimientos[$i]['tipo'] == 'canje') echo ' restapuntos';?>" align="center">
									<?php if($movimientos[$i]['tipo'] == 'canje') echo '-';?><?php echo $movimientos[$i]['numero'];?>
								</td>
							</tr>
							<?php
								}
							}
							?>
							<tr>
								<td class="resalto saldo" colspan="2"><div align="right"><strong><?php echo _('Saldo');?></strong></div></td>
								<td class="resalto saldo" align="center"><strong><?php echo $saldo;?></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<a href="balance_completo.php" class="botonbalance"><?php echo _('Descargar Movimientos Hist&oacute;ricos');?></a>
			</div>
			<div style="clear:both; width:100%; height:1px"></div>
		</div>
       </div>
	</div>
	<!-- /.container -->
	<?php include_once("pie.php")?>
</body>
</html>