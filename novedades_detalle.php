<?php
header('Content-type: text/html; charset=utf-8');
require_once 'admin/php/class/class.noticia.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
if(!isset($_REQUEST['n']) || $_REQUEST['n']=='') header('location:php/logout.php');

$ntc = new noticia();
if($ntc->select($_REQUEST['n'])){
	$titulo 		= $ntc->getTitulo();
	$cuerpo 		= $ntc->getCuerpo();
	$fecha_alta	= $ntc->getFecha_alta();
	//doy formato a la fecha de la novedad
	if($fecha_alta != '')	{
		$f = preg_split('/-/',$fecha_alta);
		$fecha_alta = $f[2].' de '.$nombre_mes[floor($f[1])].' de '.$f[0];
	}
	$imagen			= $ntc->getImagen();
	$video 			= $ntc->getVideo();
} else {
	$titulo 		= '';
	$cuerpo 		= '';
	$fecha_alta	= '';
	$imagen			= '';
	$video 			= '';
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Grand Prix 2020 | PEÑAFLOR</title>
    <!--link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/carousel/"-->
    <!-- Bootstrap core CSS -->
		<link href="assets/dist/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/peniaflor.css" rel="stylesheet">
  </head>
  <body>
    <?php 
		if($_SESSION['QLMSF_rango']=='distribuidor' || $_SESSION['QLMSF_rango']=='vendedor' || $_SESSION['QLMSF_rango']=='repositor') 
			include_once('cabecera.php');
		else
			include_once('cabecera_superior.php');
		?>
		<main role="main">
			<!--barra Mis Datos-->
			<?php 
			if($_SESSION['QLMSF_rango']=='distribuidor' || $_SESSION['QLMSF_rango']=='vendedor' || $_SESSION['QLMSF_rango']=='repositor') 
				include_once('barra_mis_datos.php');
			else
				include_once('barra_mis_datos_superior.php');
			?>

			<section class="gp-novedades">
				<div class="container">
					<h2>Novedades</h2>
					<div class="row">
						<div class="col-lg-1"></div>
						<div class="col-lg-10 gp-detalle-nota">
							<a href="novedades.php">&#x25c4; Volver</a>
                            <small><?php echo $fecha_alta;?></small><br />
							<h1><?php echo $titulo;?></h1>
							<div class="row"></div>
							<?php if($video!=''){?>
							<div class="gp-video">
								<div class="video-responsive">
									<iframe src="https://www.youtube.com/embed/<?php echo $video;?>?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
								</div>
							</div>
							<?php }?>
							<?php if($imagen!=''){?>
							<div class="foto">
								<img src="archivos/<?php echo $imagen;?>" width="100%" alt="<?php echo $titulo;?>"/>
							</div>
							<?php }?>
							<?php if($imagen=='' & $video==''){?>
							<div class="foto">
								<img src="img/no-foto.jpg" width="100%" alt="<?php echo $titulo;?>"/>
							</div>
							<?php }?>
							<p><?php echo nl2br($cuerpo);?></p>
						</div>
						<div class="col-lg-1"></div>
					</div><!-- /.row -->
				</div><!-- /.container -->
			</section>
		</main>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
		<script src="assets/dist/js/bootstrap.bundle.js"></script>

		<?php include_once('pie.php');?>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-171840062-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-171840062-1');
		</script>
	</body>
</html>
