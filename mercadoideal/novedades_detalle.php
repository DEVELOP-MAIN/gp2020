<?php
header('Content-type: text/html; charset=utf-8');
require_once 'traduccion.php';
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/class/class.noticia.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad_notis.php';

if(!isset($_SESSION)) {session_start();}
if(!isset($_REQUEST['n']) || $_REQUEST['n']=='') header('location:logout.php');

$ntc = new noticia();
if($ntc->select($_REQUEST['n']))
{
	$titulo 					= $ntc->getTitulo();
	$cuerpo 			= $ntc->getCuerpo();
	$fecha_alta	= $ntc->getFecha_alta();
	//doy formato a la fecha de la noticia
	if($fecha_alta != '')
	{
		$f = preg_split('/-/',$fecha_alta);
		$fecha_alta = $f[2].' de '.$nombre_mes[floor($f[1])].', '.$f[0];
	}
	$imagen			= $ntc->getImagen();
	$video 				= $ntc->getVideo();
}
else
{
	$titulo 					= '';
	$cuerpo 			= '';
	$fecha_alta	= '';
	$imagen			= '';
	$video 				= '';
}

//Traigo las últimas 5 noticias que no sean ésta
$lst = new listado();
$ultimas5noticias = $lst->getNoticiasFront($_REQUEST['n'],'5','N');
$nro = count($ultimas5noticias);
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
				<div class="tt-Canjes">
					<h1><?php echo _('Novedades');?></h1>
					<div class="btn-volver-det" ><a href="novedades.php"><?php echo _('VOLVER');?></a></div>
				</div>
				<div class="separa"></div>
			</div>
		<div class="mis-datos">
			<div class="col-lg-12 rb-text-left">
				<div class="col-sm-6 col-lg-9 col-md-9">
					<h3 class="tit-nov-detalle"><?php echo $titulo;?></h3>
          <div class="cont-img-det">
						<span><?php echo $fecha_alta;?></span>
						<?php if($imagen!=''){?>
						<img src="archivos/<?php echo $imagen;?>" class="img-responsive img" width="100%" height="auto">
						<?php }?>
          </div>
					<?php if($video!=''){?>
						<?php echo $video;?>
					<?php }?>
					<p class="tex-detalle-nov"><?php echo nl2br($cuerpo);?></p>
					<div class="clearfix"></div>
				</div>
				<div class="col-sm-6 col-lg-3 col-md-3">
					<!--<h3>Listado de Novedades</h3>-->
          <div class="lista-nov">
						<?php
						if($nro>0)
						{
							echo '<ul>';
							for($i=0;$i<$nro;$i++)
							{
								//doy formato a la fecha de la noticia
								if($ultimas5noticias[$i]['fecha_alta']!='')
								{
									$f = preg_split('/-/',$ultimas5noticias[$i]['fecha_alta']);
									$fnov = $f[2].'-'.$nombre_mes_abre[floor($f[1])].'-'.$f[0];
								}
								else $fnov = '&#160;ffff';
								echo '<a href="novedades_detalle.php?n='.$ultimas5noticias[$i]['idnoticia'].'"><li><strong>'.$fnov.'</strong><br/> '.$ultimas5noticias[$i]['titulo'].'</li></a>';
							}
							echo '</ul>';
						}
						?>
					</div>
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
