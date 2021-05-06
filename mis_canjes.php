<?php
require_once 'admin/php/class/class.socio.php';
require_once 'admin/php/class/class.canje.php';
require_once 'admin/php/class/class.premio.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];

$clnt = new socio();
$canjes = array();
if(!$clnt->select($idcliente)){
	header('location:index.php');
	exit;
} else {
	$canjes = $clnt->getCanjes();
	$nro = count($canjes);
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
		<?php include_once('cabecera.php');?>

		<main role="main">
			<!--barra Mis Datos-->
			<?php include_once('barra_mis_datos.php');?>

			<section class="gp-miscanjes">
				<div class="container">
					<h2>Mis canjes</h2>
					<div class="row">
						<?php
						if($nro == 0) 
							echo 'A&#218;N NO TIENES CANJES REALIZADOS';
						else {
							$estado = 'solicitado';
							$cnj = new canje();
							$prm = new premio();
							for($i=0;$i<$nro;$i++){
								if($canjes[$i]['fecha']!=''){
									$fe1 = preg_split('/ /',$canjes[$i]['fecha']);
									$fe2 = preg_split('/-/',$fe1[0]);
									$fecha_canje	= $fe2[2].'/'.$fe2[1].'/'.$fe2[0];
								}
								else $fecha_canje	= '';

								switch($canjes[$i]['estado']){
									case 'solicitado'		: $estado	= 'solicitado'; break;
									case 'efectivizado'	: $estado	= 'entregado'; break;
									case 'en proceso'		: $estado	= 'entregado'; break;
									case 'anulado'			: $estado	= 'cancelado'; break;
									case 'devuelto'			: $estado	= 'devuelto'; break;
								}

								if($prm->select($canjes[$i]['idpremio']))
									$categoria = $prm->getCategoria();
								else
									$categoria = '';
						?>
						<div class="col-lg-3">
							<div class="gp-caja-premio <?php echo $estado;?>">
								<span><?php echo $estado.' '.$fecha_canje;?></span>
								<div class="foto"><img src="archivos/<?php echo $canjes[$i]['fotopremio'];?>" width="100%" alt="<?php echo $canjes[$i]['premio'];?>"/></div>
								<p><strong><?php echo $categoria;?></strong> <?php echo $canjes[$i]['premio'];?><br><strong><?php echo $canjes[$i]['valor'];?> millas</strong></p>
							</div>
						</div>
						<?php 
							}
						}
						?>
					</div>
				</div>
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
