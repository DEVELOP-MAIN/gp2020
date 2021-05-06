<?php
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}

$ls = new listado(0, 10000);

$novedades = $ls->getNoticiasFront();
$nro = count($novedades);
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
						<?php 
						if($nro>0){
							for($i=0;$i<$nro;$i++) {
						?>
						<div class="col-lg-4">
							<a href="novedades_detalle.php?n=<?php echo $novedades[$i]['idnoticia'];?>" class="gp-caja-nota">
								<div class="foto">
									<?php
									if($novedades[$i]['imagen'] != '')
										echo '<img src="archivos/'.$novedades[$i]['imagen'].'" width="100%" alt="'.$novedades[$i]['titulo'].'"/>';
									else
										echo '<img src="img/no-foto.jpg" width="100%" alt="'.$novedades[$i]['titulo'].'"/>';
									?>
								</div>
								<h3><?php echo $novedades[$i]['titulo'];?></h3>
								<p>
									<?php
									//limito el cuerpo de la novedad a 200 caracteres
									if($novedades[$i]['cuerpo'] != ''){
										if(strlen($novedades[$i]['cuerpo'])>200)
											$cuerpo = myTruncate($novedades[$i]['cuerpo'],200);
										else
											$cuerpo = $novedades[$i]['cuerpo'];
									}
									else	$cuerpo = '';
									echo nl2br($cuerpo);
									?>
								</p>
							</a>
						</div>
						<?php 
							}
						} else echo 'A&#218;N NO SE HAN CARGAGO NOVEDADES';
						?>
					</div>
					<!-- /.row -->
				</div><!-- /.container -->
			</section>
		</main>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="assets/dist/js/bootstrap.bundle.js"></script>

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
