<?php
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
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

			<section class="gp-balance">
				<div class="container">
					<h2>Contacto</h2>
					<div class="row gp-contacto">
						<div class="col-lg-5">
							<img src="img/icon_instagram.svg" alt="Instagram" width="42"/>
							<h4>Contactanos por Instagram<br>
								<a href="https://www.instagram.com/incentivo.GP2020/" target="_blank">@incentivo.GP2020</a>
							</h4>
						</div>
						<div class="col-lg-1"></div>
						<div class="col-lg-5">
							<p><strong>¡No te quedes rezagado!</strong><br />
								Seguinos para enterarte de todas las novedades.
								<br>
								<br>
								<br>
								<br>
								<br>
							</p>
						</div>
					</div>
					<!-- /.row -->
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
