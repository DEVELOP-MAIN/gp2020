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
					<h2>El programa</h2>
					<div class="row gp-elprograma">
						<div class="col-lg-4">
							<img src="img/el_programa.jpg" width="100%" alt="El programa"/></div> 
						<div class="col-lg-8">
							<h3 style="font-size: 25px; margin-bottom: 0px; ">Incentivo fuerza de venta</h3>
							<h3>Llega la carrea del año, ¿estás  preparado para el desafío?</h3>
							<div class="gp-franja-lineas"></div>
							<p class="text-destacado">¿EL OBJETIVO? DISTRIBUCIÓN DE NUESTRAS  LÍNEAS COMERCIALES<br>
								<br>¿Quiénes  participan?</p>
							<ul class="quienes">
								<li>+30  distribuidores</li>
								<li>8  directas</li>
								<li>+500  vendedores</li>
							</ul>
							<div class="gp-franja-lineas"></div>

							<p>
							<ul>
								<li>Cada VUELTA que das a la pista  es una LÍNEA DE PRODUCTO</li>
								<li>Cada CIRCUITO es un MES</li>
								<li>La TEMPORADA termina al FPIN DEL  AÑO FISCAL</li>
							</ul>

							<p>La  competencia se divide en <strong>DIRECTAS</strong> y <strong>DISTRIBUIDORES</strong>, y los  vendedores de directa y de distribuidor sumarán millas.<br><br>
								<strong>SUMÁS MILLAS POR EL CUMPLIMIENTO DE OBJETIVOS:</strong>
							<ul>
								<li>Si llegás a un alcance entre  85% y 99% sumas el 50% de las millas</li>
								<li>Si llegás al objetivo de  100% sumas el 100% de las millas</li>
								<li>Y si superás el objetivo  tenés millas extras (tope en 120%)</li>
							</ul>

							<img src="img/ejemplo_elprograma.png" width="100%" alt="GP2020"/>

							<h3 style="font-size: 25px; margin-bottom: 0px; ">Incentivo repositores</h3>
							<div class="gp-franja-lineas"></div>
							<p class="text-destacado">¿EL OBJETIVO? LOGRAR LA MEJOR EJECUCIÓN EN CADA PUNTO DE VENTA!<br>
								<br>¿Quiénes  participan?</p>
							<ul class="quienes">
								<li>+25 repositores, de las DIRECTAS y DISTRIBUIDORES</li>
							</ul>
							<div class="gp-franja-lineas"></div>

							<p>
							<ul>
								<li>Cada incentivo tendrá sus propios <strong>OBJETIVOS</strong>.</li>
								<li>Prepara tus neumáticos y salí a romper la pista en busca de la mejor góndola, punteras,
								exhibiciones adicionales y más.</li>
								<li>Las categorías que participan son: vinos, spirits, espumantes y frizantes!
								Cada <strong>VUELTA</strong> que das a la pista es una <strong>EJECUCIÓN REALIZADA</strong>.</li>
								<li>Cada incentivo tiene un premio distinto y será comunicado por su supervisor/ejecutivo.</li>
							</ul>

							<p>Saltamos de la pista a la mejor ejecución. ¿ESTAN LISTOS?<br>
							Ser el mejor es posible, y tener el mejor desempeño en una ejecución TAMBIÉN LO ES!<br><br>
								
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
