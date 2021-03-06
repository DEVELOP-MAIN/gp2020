<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/class/class.phpmailer.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
$ls = new listado(0, 16);

//inicio variables
$fil_min	= 0;
$fil_max	= '';
$fil_cam	= '';
$fil_bus	= '';
$fil_exe	= false;

//recupero del post de buscador
if(isset($_POST['fil_min'])) 	{$fil_min = $_POST['fil_min']; $fil_exe = true;}
if(isset($_POST['fil_max']))	{$fil_max = $_POST['fil_max']; $fil_exe = true;}
if(isset($_POST['fil_cam'])) 	{$fil_cam = $_POST['fil_cam']; $fil_exe = true;}
if(isset($_POST['fil_bus'])) 	{$fil_bus = $_POST['fil_bus']; $fil_exe = true;}

//traigo puntos maximos
$maximo 					= $ls->getValorMaximo();
$campanias 		= $ls->getCampanias('', false);
$nro_c							= count($campanias);
$premios 				= $ls->getPremiosHome($fil_min, $fil_max, $fil_cam, $fil_bus);
$nro_p							= count($premios);
$premios_pop	= $ls->getPremiosHomePopulares();
$nro_pop					= count($premios_pop);
$premios_alc		= $ls->getPremiosHomeAlcance($_SESSION['QLMSF_puntos']);
$nro_alc					= count($premios_alc);
$premios_new	= $ls->getPremiosHomeNuevos();
$nro_new				= count($premios_new);
$premios_des	= $ls->getPremiosHomeDestacados();
$nro_des					= count($premios_des);
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
	<!-- Custom JS -->
	<script src="js/jquery.js"></script>
	<!-- Custom CSS -->
	<link href="fonts/fonts-esp.css?nocache=2307" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=2307" rel="stylesheet">
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
			<div class="col-md-9">
				<div class="carousel-holder">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
							<li data-target="#carousel-example-generic" data-slide-to="1"></li>
							<li data-target="#carousel-example-generic" data-slide-to="2"></li>
						</ol>
						<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { ?>
						<div class="carousel-inner">
							<div class="item active">							    
								<img class="slide-image" src="images/banners 800x300-01ch.png?nocache=0706" alt="Seguinos en WeChat"/>
							</div>
							<div class="item">									 
								<a href="novedades_detalle.php?n=2"><img class="slide-image" src="images/banners 800x300-02ch.png?nocache=0706" alt="Mercado Ideal te lleva a China"/></a>
							</div>
							<div class="item">								
								<a href="tips.php"><img class="slide-image" src="images/banners 800x300-03ch.png?nocache=0706" alt="Mercado Ideal te ayuda a vos y a tu comercio"/></a>
							</div>
						</div>
						<?php } else {?>
						<div class="carousel-inner">
							<div class="item active">
								<img class="slide-image" src="images/banners 800x300-01.png?nocache=0706" alt="Seguinos en WeChat"/>                                
							</div>
							<div class="item">
								<a href="novedades_detalle.php?n=2"><img class="slide-image" src="images/banners 800x300-02.png?nocache=0706" alt="Mercado Ideal te lleva a China"/></a>
							</div>
							<div class="item">
								<a href="tips.php"><img class="slide-image" src="images/banners 800x300-03.png?nocache=0706" alt="Mercado Ideal te ayuda a vos y a tu comercio"/></a>
							</div>
						</div>
						<?php }?>
						<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left"></span>
						</a>
						<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right"></span>
						</a>
					</div>
				</div>
      </div>
      <div class="col-md-3">
				<div class="wechat"><img src="images/banner_wechat_bi.jpg" alt="Seguinos en WeChat"/></div>
				<div class="wechat-mb"><img src="images/banner_wechat_mb_bi.png?nocache=0706" alt="Seguinos en WeChat"/></div>
      </div>
		</div>

    <!--Premios-->
    <div class="row">
			<div class="col-lg-12 ttBusca">
				
        <!--Buscador-->
				<form name="sentMessage" id="contactForm" method="POST" action="home.php" novalidate>
					<div class="buscador">
						<h1><?php echo _('Premios');?></h1>
            <div class="separa"></div>						
						<?php 
						if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { 
							echo '??????';
						} else {
							echo 'Entre';
						}
						?>
						<input type="number" class="entre" name="fil_min" id="fil_min" value="<?php echo $fil_min?>" step="50">
						&#160;<?php echo _('y');?>&#160;
						<input type="number" class="entre" name="fil_max" id="fil_max" value="<?php echo $fil_max?>" step="50">
						
            &#160;<?php echo _('puntos');?>
            <div class="separa"></div>
            <select id="fil_cam" name="fil_cam" class="categorias">
							<option value="">
							<?php 
							if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { 
								echo '????????????';
							} else {
								echo 'todas las categor??as';
							}
							?>							
							</option>
							<?php
							for($i=0;$i<$nro_c;$i++) 
							{
								if($fil_cam==$campanias[$i]['idcampania'])
								{	
									if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
										echo '<option value="'.$campanias[$i]['idcampania'].'" selected>'.$campanias[$i]['nombre_ch'].'</option>';
									else
										echo '<option value="'.$campanias[$i]['idcampania'].'" selected>'.$campanias[$i]['nombre'].'</option>';
								}
								else
								{	
									if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
										echo '<option value="'.$campanias[$i]['idcampania'].'">'.$campanias[$i]['nombre_ch'].'</option>';
									else
										echo '<option value="'.$campanias[$i]['idcampania'].'">'.$campanias[$i]['nombre'].'</option>';
								}	
							}
							?>
            </select>
						<div class="separa"></div>
						<input type="text" id="fil_bus" name="fil_bus" class="textbuscar" placeholder="<?php echo _('Ingrese una palabra');?>" value="<?php echo $fil_bus?>">
						<div class="separa"></div>
						<input class="btnbuscar" type="submit" value="<?php echo _('BUSCAR');?>">
						<div class="separa"></div>
					</div>
				</form>
			</div>
    </div>

    <!-- muestro resultados de b??squeda o en su defecto destacados, novedades, populares y a tu alcance -->

		<?php if($fil_exe) {?>
		<!--Resultados-->
    <div class="row">
			<div class="col-lg-12 subtHome">
				<h3><?php echo _('Resultados de la b&uacute;squeda');?></h3>&#160;
        <a href="premios.php"><i class="fa fa-arrow-right" aria-hidden="true"></i> <?php echo _('Ver todo');?></a>
      </div>
      <div class="premios-home">
				<?php for($i=0;$i<$nro_p;$i++) {?>
				<!--caja premio-->
				<div class="col-sm-3 col-lg-3 col-md-3">
					<a href="premio_detalle.php?idp=<?php echo $premios[$i]['idpremio']?>" class="caja-premio">
						<div class="foto">
							<?php
							if($premios[$i]['imagen']!='')
								echo '<img src="archivos/'.$premios[$i]['imagen'].'" alt="'.$premios[$i]['nombre'].'"/>';
							else
								echo '<img src="archivos/no-foto.png?nocache=0706" alt="'.$premios[$i]['nombre'].'"/>';
							?>
						</div>
						<div class="nombre">
							<?php
							if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
								if($premios[$i]['nombre_ch']!='') echo $premios[$i]['nombre_ch']; else echo $premios[$i]['nombre'];
							else
								echo $premios[$i]['nombre'];
							?>
						</div>
						<div class="puntos"><?php echo $premios[$i]['valor'];?><span> pts.</span></div>
					</a>
				</div>
				<?php }?>
        <!--Fin cajas-->
			</div>
    </div>
		<?php } else {?>
		<!--Premios destacados-->
    <div class="rb-destacado">
			<div class="row">
				<div class="subtHome2">
                    <?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') {?>
				<img src="images/tt_destacados_ch.png?nocache=0706" width="200" height="55"/>	
				
			<?php } else {?>	
				<img src="images/tt_destacados.png?nocache=0706" width="291" height="55" alt="Destacados"/>	
			<?php }?>	   
				</div>
				<div class="premios-home">
					<?php for($i=0;$i<$nro_des;$i++) {?>
					<!--caja premio-->
					<div class="col-sm-3 col-lg-3 col-md-3">
						<a href="premio_detalle.php?idp=<?php echo $premios_des[$i]['idpremio']?>" class="caja-premio">
							<div class="foto">
								<?php
								if($premios_des[$i]['imagen']!='')
									echo '<img src="archivos/'.$premios_des[$i]['imagen'].'" alt="'.$premios_des[$i]['nombre'].'"/>';
								else
									echo '<img src="archivos/no-foto.png?nocache=0706" alt="'.$premios_des[$i]['nombre'].'"/>';
								?>
							</div>
							<div class="nombre">
								<?php
								if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
									if($premios_des[$i]['nombre_ch']!='') echo $premios_des[$i]['nombre_ch']; else  echo $premios_des[$i]['nombre'];
								else
									echo $premios_des[$i]['nombre'];
								?>
							</div>
							<div class="puntos"><?php echo $premios_des[$i]['valor'];?><span> pts.</span></div>
						</a>
					</div>
					<?php }?>
					<!--Fin cajas-->
				</div>
				<div class="rb-vertodos"><a href="premios.php"><i class="fa fa-arrow-right" aria-hidden="true"></i> <?php echo _('Ver todo');?></a></div>
			</div>
    </div>
    <div class="limpiar rb-espacio"></div>
    
		<!--Ultimos agregados-->
    <div class="row">
			<div class="col-lg-12 subtHome">            
			<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') {?>
				<img src="images/tt_agregados_ch.png?nocache=0706" width="252" height="55" alt=""/>		
			<?php } else {?>	
				<img src="images/tt_agregados.png?nocache=0706" width="362" height="55"/>
			<?php }?>	            			
      </div>
      <div class="premios-home">
				<?php for($i=0;$i<$nro_new;$i++) {?>
				<!--caja premio-->
				<div class="col-sm-3 col-lg-3 col-md-3">
					<a href="premio_detalle.php?idp=<?php echo $premios_new[$i]['idpremio']?>" class="caja-premio2">
						<div class="foto">
							<?php
							if($premios_new[$i]['imagen']!='')
								echo '<img src="archivos/'.$premios_new[$i]['imagen'].'" alt="'.$premios_new[$i]['nombre'].'"/>';
							else
								echo '<img src="archivos/no-foto.png?nocache=0706" alt="'.$premios_new[$i]['nombre'].'"/>';
							?>
						</div>
						<div class="nombre2">
							<?php
							if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
								if($premios_new[$i]['nombre_ch']!='') echo $premios_new[$i]['nombre_ch']; else  echo $premios_new[$i]['nombre'];
							else
								echo $premios_new[$i]['nombre'];
							?>
						</div>
						<div class="puntos2"><?php echo $premios_new[$i]['valor'];?><span> pts.</span></div>
					</a>
				</div>
				<?php }?>
        <!--Fin cajas-->
			</div>
			<div class="rb-vertodos"><a href="premios.php"><i class="fa fa-arrow-right" aria-hidden="true"></i> <?php echo _('Ver todo');?></a></div>
    </div>
    <div class="limpiar rb-espacio"></div>
    
		<!--Mas populares-->
    <div class="row">
			<div class="col-lg-12 subtHome">
			<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') {?>
				<img src="images/tt_populares_ch.png?nocache=0706" width="240" height="49" alt=""/>		
			<?php } else {?>	
				<img src="images/tt_populares.png?nocache=0706" width="330" height="55"/>
			<?php }?>	            
	  </div>
			<div class="premios-home">
				<?php for($i=0;$i<$nro_pop;$i++) {?>
				<!--caja premio-->
				<div class="col-sm-3 col-lg-3 col-md-3">
					<a href="premio_detalle.php?idp=<?php echo $premios_pop[$i]['idpremio']?>" class="caja-premio">
						<div class="foto">
							<?php
							if($premios_pop[$i]['imagen']!='')
								echo '<img src="archivos/'.$premios_pop[$i]['imagen'].'" alt="'.$premios_pop[$i]['nombre'].'"/>';
							else
								echo '<img src="archivos/no-foto.png?nocache=0706" alt="'.$premios_pop[$i]['nombre'].'"/>';
							?>
						</div>
						<div class="nombre">
							<?php
							if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
								if($premios_pop[$i]['nombre_ch']!='') echo $premios_pop[$i]['nombre_ch']; else  echo $premios_pop[$i]['nombre'];
							else
								echo $premios_pop[$i]['nombre'];
							?>
						</div>
						<div class="puntos"><?php echo $premios_pop[$i]['valor'];?><span> pts.</span></div>
					</a>
				</div>
				<?php }?>
				<!--Fin cajas-->
      </div>
      <div class="rb-vertodos"><a href="premios.php"><i class="fa fa-arrow-right" aria-hidden="true"></i> <?php echo _('Ver todo');?></a></div>
    </div>
    <div class="limpiar rb-espacio"></div>
    
		<!--A tu alcance-->
    <div class="row">
			<div class="col-lg-12 subtHome">
				<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') {?>
				<img src="images/tt_a-tu-alcance_ch.png?nocache=0706" width="240" height="49" alt=""/>
				<?php } else {?>	
				<img src="images/tt_a-tu-alcance.png?nocache=0706" width="308" height="54" alt=""/>
				<?php }?>
      </div>
			<div class="premios-home">
				<?php for($i=0;$i<$nro_alc;$i++) {?>
				<!--caja premio-->
				<div class="col-sm-3 col-lg-3 col-md-3">
					<a href="premio_detalle.php?idp=<?php echo $premios_alc[$i]['idpremio']?>" class="caja-premio">
						<div class="foto">
							<?php
							if($premios_alc[$i]['imagen']!='')
								echo '<img src="archivos/'.$premios_alc[$i]['imagen'].'" alt="'.$premios_alc[$i]['nombre'].'"/>';
							else
								echo '<img src="archivos/no-foto.png?nocache=0706" alt="'.$premios_alc[$i]['nombre'].'"/>';
							?>
						</div>
						<div class="nombre">
							<?php
							if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
								if($premios_alc[$i]['nombre_ch']!='') echo $premios_alc[$i]['nombre_ch']; else  echo $premios_alc[$i]['nombre'];
							else
								echo $premios_alc[$i]['nombre'];
							?>
						</div>
						<div class="puntos"><?php echo $premios_alc[$i]['valor'];?><span> pts.</span></div>
					</a>
				</div>
				<?php }?>
				<!--Fin cajas-->
			</div>
      <div class="rb-vertodos"><a href="premios.php"><i class="fa fa-arrow-right" aria-hidden="true"></i> <?php echo _('Ver todo');?></a></div>
    </div>
    <div class="limpiar rb-espacio"></div>
    <?php } ?>
	</div>
	<!-- /.container -->
	<?php include_once('pie.php');?>
</body>
</html>