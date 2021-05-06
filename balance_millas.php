<?php
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/class/class.socio.php';
require_once 'admin/php/class/class.premio.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];

$clnt = new socio();
$movimientos = array();
if(!$clnt->select($idcliente)){
	header('location:index.php');
	exit;
} else {
	$puntos_totales = $clnt->dameSumaXConcepto('puntos','puntos','','');
	$puntos_alto = $clnt->dameSumaXConcepto('puntos','puntos','Alto','');
	$puntos_vat69	= $clnt->dameSumaXConcepto('puntos','puntos','Vat 69','');
	$puntos_jwred = $clnt->dameSumaXConcepto('puntos','puntos','JW Red','');
	$puntos_whitehorse = $clnt->dameSumaXConcepto('puntos','puntos','White Horse','');
	$puntos_otros = $puntos_totales - $puntos_alto - $puntos_vat69 - $puntos_jwred - $puntos_whitehorse;

	$canjes_totales = $clnt->dameSumaXConcepto('canjes','valor','','anulado','devuelto');

	$saldo = $puntos_totales - $canjes_totales;

	$_SESSION['QLMSF_puntos'] = $saldo;

	$ls = new listado(0, 10000);
	$movimientos = $ls->getMovimientosSocio($idcliente);
	$nro = count($movimientos);
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

			<section class="gp-balance">
				<div class="container">
					<h2>Mi Balance</h2>
					<div class="row">
						<?php
						if($nro == 0)
							echo 'A&#218;N NO HAY MOVIMIENTOS REGISTRADOS';
						else {
						?>
						<div class="col-lg-1"></div>
						<div class="col-lg-10">
							<h3><small>Saldo al <?php echo date("j/n");?>:</small> <?php echo $saldo;?> millas</h3>
							<table class="table table-sm">
								<thead class="thead-light">
									<tr>
										<th width="12%"><strong>Fecha</strong></th>
										<th width="73%"><strong>Detalle</strong></th>
										<th width="15%" style="text-align:center"><strong>Millas</strong></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$prm = new premio();
									for($i=0;$i<$nro;$i++){
										//doy formato a la fecha del movimiento
										if($movimientos[$i]['fecha']!=''){
											$fe1 = preg_split('/ /',$movimientos[$i]['fecha']);
											$fe2 = preg_split('/-/',$fe1[0]);
											$fecha_mov	= $fe2[2].'/'.$fe2[1].'/'.$fe2[0];
										}
										else $fecha_mov	= '';

										//si hay premio involucrado, obtengo su nombre
										if($movimientos[$i]['tipo'] == 'canje') {
											if($prm->select($movimientos[$i]['idpremio']))
												$premio = $prm->getNombre();
											else
												$premio = '';
										}
									?>
									<tr>
										<td><?php echo $fecha_mov;?></td>
										<td>
											<?php echo $movimientos[$i]['tipo'];?><?php if($movimientos[$i]['tipo'] == 'canje') echo ' '.$premio;?><?php if($movimientos[$i]['estado']=="devuelto" || $movimientos[$i]['estado']=="anulado") echo ' - <span style="color:RED">'.strtoupper($movimientos[$i]['estado']).'</span>';?>
										</td>
										<td align="center"><?php if($movimientos[$i]['tipo'] == 'canje') echo '-';?><?php echo round($movimientos[$i]['numero'], 1);?></td>
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="col-lg-1"></div>
						<?php
						}
						?>
					</div>
					<!-- /.row -->
				</div><!-- /.container -->
			</section>

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
