<?php
require_once 'admin/php/class/class.listado.php';
require_once 'php/seguridad.php';
require_once 'php/seguridad_email.php';

if(!isset($_SESSION)) {session_start();}

$ls = new listado(0, 10000);

//inicio variables
$fil_min	= '';
$fil_max	= '';
$fil_cat	= '';
$fil_tp		=	'';
$fil_bus	= '';

//recupero del post de buscador
if(isset($_POST['fil_min'])) 	{$fil_min = $_POST['fil_min'];}
if(isset($_POST['fil_max']))	{$fil_max = $_POST['fil_max'];}
if(isset($_POST['fil_cat']))	{$fil_cat = $_POST['fil_cat'];}
if(isset($_POST['fil_tp'])) 	{$fil_tp 	= $_POST['fil_tp'];}
if(isset($_POST['fil_bus'])) 	{$fil_bus = $_POST['fil_bus'];}

$categorias 	= $ls->getCampaniasCombo();
$nro_c				= count($categorias);
$premios 			= $ls->getPremiosHome($fil_min, $fil_max, $fil_cat, $fil_tp, $fil_bus);
$nro_p				= count($premios);
$premios_pop	= $ls->getPremiosHomePopulares();
$nro_pop			= count($premios_pop);
$premios_alc	= $ls->getPremiosHomeAlcance($_SESSION['QLMSF_puntos']);
$nro_alc			= count($premios_alc);
$premios_new	= $ls->getPremiosHomeNuevos();
$nro_new			= count($premios_new);
$premios_des	= $ls->getPremiosHomeDestacados();
$nro_des			= count($premios_des);
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Aper.net">
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

			<div class="container-lg gp-franja1">&#160;</div>

			<section class="container-lg">
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarousel" data-slide-to="1"></li>
						<li data-target="#myCarousel" data-slide-to="2"></li>
                        <li data-target="#myCarousel" data-slide-to="3"></li>
                        <li data-target="#myCarousel" data-slide-to="4"></li>
                        <li data-target="#myCarousel" data-slide-to="5"></li>
                        <li data-target="#myCarousel" data-slide-to="6"></li>
                        <li data-target="#myCarousel" data-slide-to="7"></li>
					</ol>
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img src="img/slides/BANNERS_GP2020.png" width="850" height="340" alt="GP 2020 Peñaflor" class="gp-slide-dk"/>
							<img src="img/slides/BANNERS_GP2020.png" width="100%" alt="GP 2020 Peñaflor" class="gp-slide-mb"/>   
						</div>
						<div class="carousel-item">
							<img src="img/slides/BANNERS_GP2020_3.png" width="850" height="340" alt="GP 2020 Peñaflor" class="gp-slide-dk"/>
							<img src="img/slides/BANNERS_GP2020_3.png" width="100%" alt="GP 2020 Peñaflor" class="gp-slide-mb"/>  
						</div>
						<div class="carousel-item">
							<img src="img/slides/BANNERS_GP2020_4.png" width="850" height="340" alt="GP 2020 Peñaflor" class="gp-slide-dk"/>
							<img src="img/slides/BANNERS_GP2020_4.png" width="100%" alt="GP 2020 Peñaflor" class="gp-slide-mb"/>  
						</div>
                        <div class="carousel-item">
							<img src="img/slides/CASCOS.PNG" width="850" height="340" alt="GP 2020 Peñaflor" class="gp-slide-dk"/>
							<img src="img/slides/CASCOS.PNG" width="100%" alt="GP 2020 Peñaflor" class="gp-slide-mb"/>  
						</div>
                         <div class="carousel-item">
							<img src="img/slides/GANADORES-julio-1.png" width="850" height="340" alt="GP 2020 Peñaflor" class="gp-slide-dk"/>
							<img src="img/slides/GANADORES-julio-1.png" width="100%" alt="GP 2020 Peñaflor" class="gp-slide-mb"/>  
						</div>
                         <div class="carousel-item">
							<img src="img/slides/GANADORES-julio-2.png" width="850" height="340" alt="GP 2020 Peñaflor" class="gp-slide-dk"/>
							<img src="img/slides/GANADORES-julio-2.png" width="100%" alt="GP 2020 Peñaflor" class="gp-slide-mb"/>  
						</div>
                         <div class="carousel-item">
							<img src="img/slides/INSTAGRAM.PNG" width="850" height="340" alt="GP 2020 Peñaflor" class="gp-slide-dk"/>
							<img src="img/slides/INSTAGRAM.PNG" width="100%" alt="GP 2020 Peñaflor" class="gp-slide-mb"/>  
						</div>
					</div>
					<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
				<div class="gp-banner-destacado">
					<h2>¡No te quedes rezagado!</h2>
					<p>Seguinos para enterarte las todas las novedades<br><img src="img/icon_instagram.svg" alt="Instagram" width="18"/><a href="https://www.instagram.com/incentivo.GP2020/" target="_blank">@incentivo.GP2020</a></p>
				</div>
			</section>
  
			<section class="container-lg gp-buscar">
				<div class="container">
					<div class="row">
						<form name="sentMessage" id="contactForm" method="POST" action="catalogo.php" novalidate class="form-inline">
							<div class="form-group col-lg-2">
								<h3>Buscar en catálogo</h3>
							</div>
							<div class="form-group col-lg-4">
								<label for="puntosdesde">Entre</label>
								<input class="form-control gp-pts" type="text" name="fil_min" id="fil_min" value="<?php echo $fil_min?>">
								<label for="puntoshasta">y</label>
								<input class="form-control gp-pts" type="text" name="fil_max" id="fil_max" value="<?php echo $fil_max?>">
								<span>millas</span>
							</div>
							<div class="form-group col-lg-4">
								<input class="form-control gp-busca" type="text" name="fil_bus" id="fil_bus" value="<?php echo $fil_bus?>" placeholder="Ingrese palabra">
							</div>
							<div class="form-group col-lg-2"></div>
							<div class="form-group col-lg-2 gp-mgtop10 gp-mb-oculta"></div>
							<div class="form-group col-lg-4 gp-mgtop10">   
								<select class="form-control gp-tipo" name="fil_tp" id="fil_tp">
									<option value="" <?php if($fil_tp == '') echo 'selected = "selected"';?>>Tipo de premio</option>
									<option value="giftcard virtual y sucursales" <?php if($fil_tp == 'giftcard virtual y sucursales') echo 'selected = "selected"';?>>Online y sucursal</option>
									<option value="giftcard solo sucursales" <?php if($fil_tp == 'giftcard solo sucursales') echo 'selected = "selected"';?>>Sólo sucursal</option>
								</select>
							</div>
							<div class="form-group col-lg-4 gp-mgtop10">
								<select class="form-control gp-select" name="fil_cat" id="fil_cat">
									<option value="" <?php if($fil_cat == '') echo 'selected="selected"';?>>Todas las categorías</option>
									<?php
									for($i=0;$i<$nro_c;$i++){
										if($fil_cat == $categorias[$i]['idcampania'])
											echo '<option value="'.$categorias[$i]['idcampania'].'" selected="selected">'.$categorias[$i]['nombre'].'</option>';
										else
											echo '<option value="'.$categorias[$i]['idcampania'].'">'.$categorias[$i]['nombre'].'</option>';
									}
									?>
								</select>
							</div>
							<div class="form-group col-lg-2 gp-mgtop10">  
								<button type="submit" class="btn btn-primary gp-btn-rojo-borde">Buscar</button>
							</div>
						</form>
					</div>
				</div>
			</section>

			<div class="gp-franja-lineas">&#160;</div>

			<section class="gp-destacados gp-premios">
				<div class="container">
					<h2>Destacados</h2>
					<div class="row">
						<?php 
						if($nro_des>0){
							for($i=0;$i<$nro_des;$i++) {?>
						<div class="col-lg-3">
							<a href="detalle_premio.php?idp=<?php echo $premios_des[$i]['idpremio'];?>" class="gp-caja-premio">
								<span><?php echo $premios_des[$i]['categoria'];?></span>
								<div class="foto"><img src="archivos/<?php echo $premios_des[$i]['imagen'];?>" width="100%" alt="<?php echo $premios_des[$i]['nombre'];?>"/></div>
								<p><?php echo $premios_des[$i]['nombre'];?></p>
								<h3><?php echo $premios_des[$i]['valor'];?> millas</h3>
							</a>
						</div>
						<?php 
							}
						} else echo 'A&#218;N NO SE HAN CARGAGO PREMIOS DESTACADOS';
						?>
					</div>
					<!-- /.row -->
				</div><!-- /.container -->
			</section>

			<section class="gp-populares gp-premios">
				<div class="container">
					<h2>Últimos agregados</h2>
					<div class="row">
						<?php 
						if($nro_new>0){
							for($i=0;$i<$nro_new;$i++) {?>
						<div class="col-lg-3">
							<a href="detalle_premio.php?idp=<?php echo $premios_new[$i]['idpremio'];?>" class="gp-caja-premio">
								<span><?php echo $premios_new[$i]['categoria'];?></span>
								<div class="foto"><img src="archivos/<?php echo $premios_new[$i]['imagen'];?>" width="100%" alt="<?php echo $premios_new[$i]['nombre'];?>"/></div>
								<p><?php echo $premios_new[$i]['nombre'];?></p>
								<h3><?php echo $premios_new[$i]['valor'];?> millas</h3>
							</a>
						</div>
						<?php 
							}
						} else echo 'A&#218;N NO SE HAN CARGAGO PREMIOS';
						?>
					</div>
					<!-- /.row -->
				</div><!-- /.container -->
			</section>
  
		 	 <section class="gp-franja-banner">
             <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                  <img src="img/banner-home1.png" alt="GP2020"/>
                  </div>
                  <div class="carousel-item">
                    <img src="img/banner-home2.png" alt="GP2020"/>
                  </div>
                   <div class="carousel-item">
                    <img src="img/banner-home3.png" alt="GP2020"/>
                  </div>
                   <div class="carousel-item">
                    <img src="img/banner-home4.png" alt="GP2020"/>
                  </div>
                </div>
              </div>
             </section>
 
			<section class="gp-populares gp-premios">
				<div class="container">
					<h2 style="width:13.5rem;">Más populares</h2>
					<div class="row">
						<?php 
						if($nro_pop>0){
							for($i=0;$i<$nro_pop;$i++) {?>
						<div class="col-lg-3">
							<a href="detalle_premio.php?idp=<?php echo $premios_pop[$i]['idpremio'];?>" class="gp-caja-premio">
								<span><?php echo $premios_pop[$i]['categoria'];?></span>
								<div class="foto"><img src="archivos/<?php echo $premios_pop[$i]['imagen'];?>" width="100%" alt="<?php echo $premios_pop[$i]['nombre'];?>"/></div>
								<p><?php echo $premios_pop[$i]['nombre'];?></p>
								<h3><?php echo $premios_pop[$i]['valor'];?> millas</h3>
							</a>
						</div>
						<?php 
							}
						} else echo 'A&#218;N NO HAY PREMIOS M&#193;S ELEGIDOS';
						?>
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
