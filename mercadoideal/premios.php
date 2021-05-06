<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.listado.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
$ls = new listado();

//inicio variables
$fil_min	= 0;
$fil_max	= '';
$fil_cam	= '';
$fil_bus	= '';
$fil_exe	= false;

//recupero del post de buscador
if(isset($_POST['fil_min'])) {$fil_min = $_POST['fil_min'];$fil_exe=true;}
if(isset($_POST['fil_max'])) {$fil_max = $_POST['fil_max'];$fil_exe=true;}
if(isset($_POST['fil_cam'])) {$fil_cam = $_POST['fil_cam'];$fil_exe=true;}
if(isset($_POST['fil_bus'])) {$fil_bus = $_POST['fil_bus'];$fil_exe=true;}

//traigo puntos maximos
$maximo 			= $ls->getValorMaximo();
$campanias	= $ls->getCampanias('', false);
$premios 		= $ls->getPremiosHome($fil_min, $fil_max, $fil_cam, $fil_bus);
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
	<?php include_once('cabecera.php')?>

	<!-- Contenido -->
	<div class="container">

							<!--Premios-->
							<div class="row">
							<div class="col-lg-12 ttBusca">
								<div class="tt-list">
										<h1><?php echo _('Premios');?></h1>
									</div>
							 <!--Buscador-->
			<form name="sentMessage" id="contactForm" method="POST" action="premios.php" novalidate>
				<div class="buscador buscador-list">
				<?php 
						if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { 
							echo '筛选';
						} else {
							echo 'Entre';
						}
						?>&#160;
				<input type="number" class="entre" name="fil_min" id="fil_min" value="<?php echo $fil_min?>" step="50">
				&#160;<?php echo _('y');?>&#160;
				<input type="number" class="entre" name="fil_max" id="fil_max" value="<?php echo $fil_max?>" step="50">
				
								&nbsp;<?php echo _('puntos');?>
								<div class="separa"></div>
								 <select id="fil_cam" name="fil_cam" class="categorias">
								  <option value="">
								  <?php 
									if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { 
										echo '所有类别';
									} else {
										echo 'todas las categorías';
									}
									?>	
								  </option>
								  <?php
									for($i=0;$i<count($campanias);$i++) {
										if($fil_cam==$campanias[$i]["idcampania"])
											if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=="zh_CN")
												echo "<option value='".$campanias[$i]["idcampania"]."' selected>".$campanias[$i]["nombre_ch"]."</option>";
											else
												echo "<option value='".$campanias[$i]["idcampania"]."' selected>".$campanias[$i]["nombre"]."</option>";
										else
											if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=="zh_CN")
												echo "<option value='".$campanias[$i]["idcampania"]."'>".$campanias[$i]["nombre_ch"]."</option>";
											else
												echo "<option value='".$campanias[$i]["idcampania"]."'>".$campanias[$i]["nombre"]."</option>";
									}
									?>
								</select>
								<div class="separa"></div>
									<input type="text" id="fil_bus" name="fil_bus" class="textbuscar" placeholder="<?php echo _('Ingrese una palabra');?>" value="<?php echo $fil_bus?>">
								<div class="separa"></div>
									 <input class="btnbuscar2" type="submit" value="<?php echo _('BUSCAR');?>">
								<div class="separa"></div>
									</div>
			</form>
						</div>
					</div>

							<!--Mas populares-->
							<div class="row">
							<div class="col-lg-12 subtHome">
				<!--Titulo dinamico: Mas populares, A tu alcance, Ultimos agregados, Se encontraron XX premios para tu búsqueda, y si acceden por menú ppal. XX premios-->
								<?php
				if(count($premios)>0)
					echo "<h3>".count($premios)." "._('Premios disponibles')."</h3>";
				else
					echo "<h3>"._('No se hallaron resultados para esta b&uacute;squeda')."</h3>";
				?>
							</div>
							<div class="premios-home">

								 <?php for($i=0;$i<count($premios);$i++) {?>
					<!--caja premio-->
					<div class="col-sm-3 col-lg-3 col-md-3">
						<a href="premio_detalle.php?idp=<?php echo $premios[$i]["idpremio"]?>" class="caja-premio">
							<div class="foto">
							<?php
								if($premios[$i]["imagen"]!="")
									echo "<img src='archivos/".$premios[$i]["imagen"]."' alt='".$premios[$i]["nombre"]."'/>";
								else
									echo "<img src='archivos/no-foto.png' alt='".$premios[$i]["nombre"]."'/>";
							?>
							</div>
							<div class="nombre"><?php
									if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=="zh_CN")
										if($premios[$i]["nombre_ch"]!="") echo $premios[$i]["nombre_ch"]; else echo $premios[$i]["nombre"];
									else
										echo $premios[$i]["nombre"];
								?></div>
							<div class="puntos"><?php echo $premios[$i]["valor"];?><span> pts.</span></div>
						</a>
					</div>
				<?php }?>

							 <!--Fin cajas-->
							 </div>
							</div>


	</div>
	<!-- /.container -->

	<?php include_once("pie.php")?>
	
</body>
</html>