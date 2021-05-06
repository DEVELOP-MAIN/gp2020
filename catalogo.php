<?php
require_once 'admin/php/class/class.listado.php';
require_once 'php/seguridad.php';

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

$categorias = $ls->getCampaniasCombo();
$nro_c			= count($categorias);
$premios 		= $ls->getPremiosHome($fil_min, $fil_max, $fil_cat, $fil_tp, $fil_bus);
$nro_p			= count($premios);
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
		if($_SESSION['QLMSF_rango']=='distribuidor' || $_SESSION['QLMSF_rango']=='vendedor' || $_SESSION['QLMSF_rango']=='repsitor') 
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

			<section class="gp-catalogo gp-premios">
				<div class="container">
					<h2><?php echo $nro_p;?> <span>premios disponibles</span></h2>
					<div class="row">
						<?php 
						if($nro_p>0){
							for($i=0;$i<$nro_p;$i++) {
						?>
						<div class="col-lg-3">
							<a href="detalle_premio.php?idp=<?php echo $premios[$i]['idpremio'];?>" class="gp-caja-premio">
								<span><?php echo $premios[$i]['categoria'];?></span>
								<div class="foto">
									<img src="archivos/<?php echo $premios[$i]['imagen'];?>" width="100%" alt="<?php echo $premios[$i]['nombre'];?>"/>
								</div>
								<p><?php echo $premios[$i]['nombre'];?></p>
								<h3><?php echo $premios[$i]['valor'];?> millas</h3>
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
			<?php //echo $ls->getPaginacionFront();?>
			<!--section-- class="gp-paginado text-center">
				<nav aria-label="Page navigation example">
					<ul class="pagination">
						<li class="page-item">
							<a class="page-link" href="#" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item"><a class="page-link" href="#">1</a></li>
						<li class="page-item"><a class="page-link" href="#">2</a></li>
						<li class="page-item"><a class="page-link" href="#">3</a></li>
						<li class="page-item">
							<a class="page-link" href="#" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</section-->
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
