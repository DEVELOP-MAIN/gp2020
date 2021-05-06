<?php
require_once 'traduccion.php';
require_once 'php/seguridad.php';
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
	<link href="fonts/fonts-esp.css?nocache=07061" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=07061" rel="stylesheet">
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
	<div class="container rb-detpremio"> 
		<div class="row">
			<div class="col-lg-12 ttBusca">
				<div class="tt-Canjes"><h1><?php echo _('El Programa');?></h1></div>
				<div class="separa"></div>
			</div>
		   
		<div class="mis-datos">
			<div class="col-lg-12">
				<div class="col-lg-12 cab-programa">										
                    <?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<h3>什么是 Mercado Ideal？</h3>
					<?php } else {?>
						<h3>&iquest;Qu&eacute; es Mercado Ideal?</h3>
					<?php }?>
					
					<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<p>Mercado Ideal 是一个面向最佳商户的优质活动。是 Quilmes 公司提供给全国最佳的超市参与的奖品活动。</p>
					<?php } else {?>	
						<p>Mercado Ideal es un programa para un PDV Ideal. Es un programa de premios que Quilmes le brinda a los mejores autoservicios del pa&iacute;s.</p>
					<?php }?>
				</div>
				<div class="col-sm-6 col-lg-6 col-md-6 rb-text-left">
					<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<h4 class="prog-title-min"><strong>在活动内你可享有专属优惠：</strong></h4>
					<?php } else {?>
						<h4 class="prog-title-min"><strong>Dentro del programa ten&eacute;s excelentes beneficios:</strong></h4>
					<?php }?>	
                    
					<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<ul class="listaflecha">
							<li><i class="fa fa-arrow-right" aria-hidden="true"></i>折扣及专属特惠价格</li>
							<li><i class="fa fa-arrow-right" aria-hidden="true"></i>投资提供你店内的设备及素材</strong></li>
							<li><i class="fa fa-arrow-right" aria-hidden="true"></i>透过微信公众号中文沟通提供帮助！</li>
						</ul>
					<?php } else {?>					
						<ul class="listaflecha">
							<li><i class="fa fa-arrow-right" aria-hidden="true"></i> Descuentos y negociaciones de precios especiales</li>
							<li><i class="fa fa-arrow-right" aria-hidden="true"></i> INVERSIONES en equipos y materiales para tu  PDV</strong></li>
							<li><i class="fa fa-arrow-right" aria-hidden="true"></i> Comunicaci&oacute;n directa a trav&eacute;s de WeChat en tu  propio idioma!</li>
						</ul>
					<?php }?>	
                    
					
					<div class="qrbox">
					<div class="qr-img-box">
					<img src="images/Cod-QR.jpg" class="img img-responsive">
					</div>
					<div class="qr-text-box">
					<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<p>进货产品，摆放正确展示及参加每周微信挑战任务都可累积积分。要参加挑战任务，需要先关注我们微信官方公众号</p>
					<?php } else {?>					
						<p>Sumas puntos comprando nuestros productos, exhibi&eacute;ndolos  adecuadamente y completando desaf&iacute;os en WeChat. Para participar de los  desaf&iacute;os, ten&eacute;s que seguir a nuestra cuenta oficial de WeChat!</p>
                    <?php }?>	
					
					<a href="balance_puntos.php" class="btn-qr-box"><?php echo _('Ir a tu balance de puntos');?></a>
					</div>
					<div class="clearfix"></div>
					</div> 
					<p><?php echo _('Los puntos que acumulas los pod&eacute;s canjear por');?> <a href="premios.php" target="_self" class="link-resalto"><?php echo _('premios y beneficios');?></a>  <?php echo _('para vos o tu local a trav&eacute;s de este mismo portal!');?> </p>
				</div>
				<div class="col-sm-6 col-lg-6 col-md-6 rb-text-left">
					<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<h4 class="prog-title-min"><strong>想知道如何累积积分？跟著Mercado Ideal是很简单的</strong></h4>
					<?php } else {?>					
						<h4 class="prog-title-min"><strong>&iquest;Quer&eacute;s saber c&oacute;mo sumar puntos? Con Mercado Ideal es muy  f&aacute;cil ganar</strong></h4>
                    <?php }?>		
					
					<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<ol start="1" type="3" class="listanum">
							<li>比前一年提高你店的进货量<strong class="bulk">每月就可以获得 500分</strong>。旗下所有的啤酒都可以！</li>
							<li>在你店里新摆设一个专属的小容升啤酒冰箱，可以累积一次性的<strong class="bulk">500分。</strong></strong></li>
							<li><strong class="bulk">如同你所知道的，我们每周都有挑战任务。</strong>这是累积分数最快速的方法！！关注我们微信号开始快速累积分数！每周都有不同的挑战任务，啤酒及汽水的。每个任务都可以获得<strong class="bulk">三百分！</strong></li>
							<li>如果参加每周挑战任务，就有机会开通<strong class="bulk">进货特定商品</strong>获得额外积分的机会。关注微信公众号发现是哪些商品并持续累积积分兑换你想要的大奖吧！</li>						
						</ol>
					<?php } else {?>					
						<ol start="1" type="3" class="listanum">
							<li>Aumentando tus compras vs el año pasado vas a poder sumar <strong class="bulk">500 puntos mensuales</strong>. Participan todas nuestras cervezas!</li>
							<li>Cuando te coloquen la heladera especial de calibres peque&ntilde;os de cerveza, sumas por &uacute;nica vez <strong class="bulk">500 puntos.</strong></li>
							<li><strong class="bulk">Como ya sabes, tenemos desaf&iacute;os semanales.</strong> Esta es la variable que m&aacute;s suma!! Segu&iacute;nos en WeChat para turbinar tus puntos!!! Todas las semanas tendr&aacute;s distintos desaf&iacute;os para cumplir, de cervezas y gaseosas. Cada desaf&iacute;o semanal sumar&aacute; <strong class="bulk">300 puntos!</strong></li>
							<li>Si participas de los desaf&iacute;os, se te habilita la posibilidad de sumar puntos adicionales <strong class="bulk">comprando ciertos productos.</strong> Ent&eacute;rate por WeChat cu&aacute;les son y segu&iacute; sumando puntos para canjearlos por los premios que quieras!</li>						
						</ol>
                    <?php }?>		
				</div>
				<div class="col-lg-12 text-center">
					<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<h3 style="color:#d89f42">MERCADO IDEAL，给最优质的梦想商户，就像你的店！</h3>
					<?php } else {?>						
						<h3 style="color:#d89f42">MERCADO IDEAL, UN PROGRAMA PARA UN PUNTO DE VENTA IDEAL,  COMO EL TUYO!!!</h3>
					<?php }?>			
                    
				</div>
			</div>
			<div style="clear:both; width:100%; height:1px"></div>    
		</div>
        </div>  
	</div>
	<!-- /.container -->

	<?php include_once("pie.php")?>
</body>
</html>