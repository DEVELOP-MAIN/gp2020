<?php
require_once 'admin/php/class/class.socio.php';
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';
if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];

//Verifico que soy nivel superior
if($_SESSION['QLMSF_rango']=='distribuidor' || $_SESSION['QLMSF_rango']=='vendedor' || $_SESSION['QLMSF_rango']=='repositor') {
	header('location:home.php');
}

//falta armar un array de mis regiones para luego permitir desplegar solo las mias

//me fijo el mes o viene dado o es el actual
if(isset($_GET['m']) && strpos($_GET['m'], '-') !== false){
	$mes = explode('-', $_GET['m'])[0];
	$anio = explode('-', $_GET['m'])[1];
}
else {
	if(isset($_GET['m']) && is_numeric($_GET['m'])) {$mes = $_GET['m'];} else {$mes = intval(date('m'));}
	$anio = date('Y');
}

//traigo todos los distris con sus puntos del mes y totales
$lst = new listado();
$distris = $lst->getObjetivosSocios(intval($mes),intval($anio),'','', 'distribuidor','so.region, so.apellido ASC');
$distris_reg = $lst->getObjetivosSocios(intval($mes),intval($anio),'','', 'distribuidor','so.region, pu.linea ASC');
$distris_lin = $lst->getObjetivosSocios(intval($mes),intval($anio),'','', 'distribuidor','so.apellido, pu.linea ASC');

//inicializo combo de regiones de distri
$regiones_dis = Array();
$regiones_linea = Array();

//sumo por distri
$indice = -1;
$cliente_anterior = 0;
$acumulo_objetivo = 0;
$acumulo_cumplimiento = 0;

$nro_dis = count($distris);
for($i=0;$i<$nro_dis;$i++) {
	if($distris[$i]['idcliente'] == $cliente_anterior) {
		$acumulo_objetivo = $acumulo_objetivo + intval($distris[$i]['objetivo']);
		$acumulo_cumplimiento = $acumulo_cumplimiento + intval($distris[$i]['avance']);
	} else {
		$indice++;
		$acumulo_objetivo = intval($distris[$i]['objetivo']);
		$acumulo_cumplimiento = intval($distris[$i]['avance']);
		$cliente_anterior = $distris[$i]['idcliente'];
	}
	$regiones_dis[$indice]['region'] = $distris[$i]['region'];
	$regiones_dis[$indice]['idcliente'] = $distris[$i]['idcliente'];
	$regiones_dis[$indice]['apellido'] = $distris[$i]['apellido'];
	$regiones_dis[$indice]['objetivo'] = $acumulo_objetivo;
	$regiones_dis[$indice]['cumplimiento'] = $acumulo_cumplimiento;
}

//sumo por linea
$indice = -1;
$linea_anterior = '';
$region_anterior = '';
$acumulo_objetivo = 0;
$acumulo_cumplimiento = 0;

$nro_dis_rg = count($distris_reg);
for($i=0;$i<$nro_dis_rg;$i++) {
	if($distris_reg[$i]['linea'] == $linea_anterior && $distris_reg[$i]['region'] == $region_anterior) {
		$acumulo_objetivo = $acumulo_objetivo + intval($distris_reg[$i]['objetivo']);
		$acumulo_cumplimiento = $acumulo_cumplimiento + intval($distris_reg[$i]['avance']);
	} else {
		$indice++;
		$acumulo_objetivo = intval($distris_reg[$i]['objetivo']);
		$acumulo_cumplimiento = intval($distris_reg[$i]['avance']);
		$linea_anterior = $distris_reg[$i]['linea'];
		$region_anterior = $distris_reg[$i]['region'];
	}
	$regiones_linea[$indice]['region'] = $distris_reg[$i]['region'];
	$regiones_linea[$indice]['linea'] = $distris_reg[$i]['linea'];	
	$regiones_linea[$indice]['objetivo'] = $acumulo_objetivo;
	$regiones_linea[$indice]['cumplimiento'] = $acumulo_cumplimiento;
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
		<?php include_once('cabecera_superior.php');?>
		<main role="main">
			<?php include_once('barra_mis_datos_superior.php');?>
			<section class="gp-performance-tt">
				<div class="container">
					<h2 style="width:16rem">Mi performance</h2>
					<div class="row" style="padding-top:15px">
						<?php if($_SESSION['QLMSF_idgrupo']==1 || $_SESSION['QLMSF_idgrupo']==3){?>
						<div class="col-lg-5 col-12">
							<span class="gp-menu-tt">Distribuidores</span>
							<a href="ranking_distri.php" class="gp-btn-ranking">RANKING</a>
							<a href="objetivos_distri.php" class="gp-btn-ranking">OBJETIVOS</a>
						</div>
						<?php }?>
						<?php if($_SESSION['QLMSF_idgrupo']==2 || $_SESSION['QLMSF_idgrupo']==3 || $_SESSION['QLMSF_idgrupo']==4){?>
						<div class="col-lg-5 col-12">
							<span class="gp-menu-tt">Directa</span>
							<a href="ranking_directa.php" class="gp-btn-ranking">RANKING</a>
							<a href="objetivos_directa.php" class="gp-btn-ranking">OBJETIVOS</a>
						</div>
						<?php }?>
					</div>
				</div>
			</section>

			<section class="gp-performance-equipos">
				<img src="img/equipos_directa-01.svg" alt="Equipos Directa" style="display:none"/>
				<img src="img/equipos_distri-01.svg" alt="Equipos Distribuidor"/>
			</section>

			<section class="gp-ranking-distri">
				<div class="container">
					<h5 style="width:9.5rem">Distribuidores</h5>
					<h2>Objetivos <?php echo $nombre_mes[$mes]?> <?php echo $anio?></h2>
					<ul class="nav nav-tabs">
						<li id="region_solapa" class="active"><a onclick="showRegion();" href="javascript:void(1);">Por región</a></li>
						<li id="linea_solapa"><a onclick="showLinea();" href="javascript:void(1);">Por línea comercial</a></li>
						<li id="distribuidor_solapa"><a onclick="showDistribuidor();" href="javascript:void(1);">Por distribuidor</a></li>
						<li class="gp-meses2">
						<?php 
								$count_meses = 0;
							?>
							<select onchange="reloadMes(this.value);">
								<option>Meses anteriores</option>
								<?php
								$mactual = intval(date("m"));
								$anio = intval(date("Y"));
								$count_meses++;
								for($i=1; $i <= $mactual; $i++)
									echo '<option value="'.$i.'">'.$nombre_mes[$i].' '.$anio.'</option>';
								?>

								<?php
								$i = 12;
								while($count_meses < 12) {
									
									$count_meses++;
										echo '<option ' . (($_GET['m'] == $i.'-'.(date('Y')-1))?'selected="selected"':'') . ' value="'.$i.'-' . (date('Y') - 1) . '">'.$nombre_mes[$i]. ' ' . (date('Y') - 1) .'</option>';
									$i --;
								}
								?>
							</select>
						</li>
					</ul>

					<!--Objetivos POR REGION-->
					<div id="region_contenido" class="row" id="por-region" style="padding-top:20px; display:block">
						<div class="col-lg-12">
							<?php if(count($regiones_dis)>0) {?>
							<table class="table table-sm">
								<thead class="thead-light">
										<th width="12%">Región</th>
										<th width="30%">Distribuidor</th>
										<th width="10%" style="text-align:center">Objetivo</th>
                                        <th width="10%" style="text-align:center">Avance</th>
										<th width="38%" style="text-align:center">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$region_anterior = '';
									$objetivos_total = 0;
									$cumplimiento_total = 0;
									$nro_rgdis = count($regiones_dis);
									for($i=0;$i<$nro_rgdis;$i++) {
										$region = $regiones_dis[$i]['region'];
										$apellido = $regiones_dis[$i]['apellido'];
										$objetivo = intval($regiones_dis[$i]['objetivo']);
										$cumplimiento = intval($regiones_dis[$i]['cumplimiento']);
										$cumplimiento_por = round(floatval($cumplimiento*100/$objetivo),1);

										//acumulo total
										$objetivos_total = $objetivos_total + $objetivo;
										$cumplimiento_total = $cumplimiento_total + $cumplimiento;

										$ancho = $cumplimiento_por;
										if($ancho>100) $ancho = 100;
										$color = dameColorSegunAvance($cumplimiento_por);
									?>
									<?php if($region_anterior != $region && $i>0) {?>
									<tr>
										<td colspan="5" class="gp-separador"></td>
									</tr>
									<?php }?>
									<tr>
										<td><?php if($region_anterior != $region) {echo "<strong>".$region."</strong>"; $region_anterior = $region;}?></td>
										<td><?php echo strtoupper($apellido)?></td>
										<td align="center"><?php echo $objetivo?></td>
                                        <td align="center"> </td>
										<td align="center">
											<div class="barra">
												<img src="img/barra-<?php echo $color?>.png" width="<?php echo $ancho?>%" height="15" alt="<?php echo $cumplimiento_por?>%"/>
											</div> 
											<span><?php echo $cumplimiento_por?>%</span>
										</td>
									</tr>
									<?php }?>
									<?php 
									$cumplimiento_por = round(floatval($cumplimiento_total*100/$objetivos_total),1);
									$ancho = $cumplimiento_por;
									if($ancho>100) $ancho = 100;
									$color = dameColorSegunAvance($cumplimiento_por);	
									?>
									<tr>
										<td class="gp-totales" colspan="2">TOTAL GENERAL</td>
										<td class="gp-totales" align="center"><?php echo $objetivos_total?></td>
                                        <td class="gp-totales" align="center"> </td>
										<td class="gp-totales" align="center"><div class="barra"><img src="img/barra-<?php echo $color?>.png" width="<?php echo $ancho?>%" height="15" alt="<?php echo $cumplimiento_por?>%"/></div> <span><?php echo $cumplimiento_por?>%</span></td>
									</tr>
								</tbody>
							</table>
							<?php } else {?> 
							<table class="table table-sm">
								<thead class="thead-light">
									<tr>
										<th width="2%"></th>
										<th width="96%">No hay datos para el mes seleccionado</th>
										<th width="2%"></th>
									</tr>
								</thead>
							</table>	
							<?php } ?>
						</div>
					</div>

					<!--Objetivos POR LINEA COMERCIAL-->
					<div id="linea_contenido" class="row" style="padding-top:20px; display:none">
						<div class="col-lg-12">
							<?php if(count($regiones_dis)>0) {?>
							<table class="table table-sm">
								<thead class="thead-light">
									<tr>
										<th width="12%">Región</th>
										<th width="20%">Línea comercial</th>
										<th width="10%" style="text-align:center">Objetivo</th>
                                        <th width="10%" style="text-align:center">Avance</th>
										<th width="38%" style="text-align:center">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$region_anterior = '';
									$objetivos_total = 0;
									$cumplimiento_total = 0;
									$nro_rgln = count($regiones_linea);
									for($i=0;$i<$nro_rgln;$i++) {
										$region = $regiones_linea[$i]['region'];
										$linea = $regiones_linea[$i]['linea'];
										$objetivo = intval($regiones_linea[$i]['objetivo']);
										$cumplimiento = intval($regiones_linea[$i]['cumplimiento']);
										$cumplimiento_por = round(floatval($cumplimiento*100/$objetivo),1);

										//acumulo total
										$objetivos_total = $objetivos_total + $objetivo;
										$cumplimiento_total = $cumplimiento_total + $cumplimiento;

										$ancho = $cumplimiento_por;
										if($ancho>100) $ancho = 100;
										$color = dameColorSegunAvance($cumplimiento_por);
									?>
									<?php if($region_anterior != $region && $i>0) {?>
									<tr>
										<td colspan="5" class="gp-separador"></td>
									</tr>
									<?php } ?>

									<tr>
										<td><?php if($region_anterior != $region) {echo '<strong>'.$region.'</strong>'; $region_anterior = $region;}?></td>
										<td><?php echo strtoupper($linea)?></td>
										<td align="center"><?php echo $objetivo?></td>
                                        <td align="center"> </td>
										<td align="center"><div class="barra"><img src="img/barra-<?php echo $color?>.png" width="<?php echo $ancho?>%" height="15" alt="<?php echo $cumplimiento_por?>%"/></div> <span><?php echo $cumplimiento_por?>%</span></td>
									</tr>
									<?php }?>
									<?php 
									$cumplimiento_por = round(floatval($cumplimiento_total*100/$objetivos_total),1);
									$ancho = $cumplimiento_por;
									if($ancho>100) $ancho = 100;
									$color = dameColorSegunAvance($cumplimiento_por);
									?>
									<tr>
										<td class="gp-totales" colspan="2">TOTAL GENERAL</td>
										<td class="gp-totales" align="center"><?php echo $objetivos_total?></td>
                                        <td class="gp-totales" align="center"> </td>
										<td class="gp-totales" align="center"><div class="barra"><img src="img/barra-<?php echo $color?>.png" width="<?php echo $ancho?>%" height="15" alt="<?php echo $cumplimiento_por?>%"/></div> <span><?php echo $cumplimiento_por?>%</span></td>
									</tr>
								</tbody>
							</table>
							<?php } else {?> 
							<table class="table table-sm">
								<thead class="thead-light">
									<tr>
										<th width="2%"></th>
										<th width="96%">No hay datos para el mes seleccionado</th>
										<th width="2%"></th>
									</tr>
								</thead>
							</table>	
							<?php } ?>
						</div>
					</div>

					<!--Objetivos POR DISTRIBUIDOR-->
					<div id="distribuidor_contenido"  class="row" style="padding-top:20px; display:none">
						<div class="col-lg-12">
							<?php if(count($regiones_dis)>0) {?>
							<table class="table table-sm">
								<thead class="thead-light">
										<th width="27%">Distribuidor</th>
										<th width="15%">Línea comercial</th>
										<th width="10%" style="text-align:center">Objetivo</th>
                                        <th width="10%" style="text-align:center">Avance</th>
										<th width="38%" style="text-align:center">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
								<?php 
								$distri_anterior = '';
								$objetivos_total = 0;
								$cumplimiento_total = 0;
								$nro_dis_lin = count($distris_lin);
								for($i=0;$i<$nro_dis_lin;$i++) {
									$apellido = strtoupper($distris_lin[$i]['apellido']);
									$linea = $distris_lin[$i]['linea'];
									$objetivo = intval($distris_lin[$i]['objetivo']);
									$cumplimiento = intval($distris_lin[$i]['avance']);
									$cumplimiento_por = round(floatval($cumplimiento*100/$objetivo),1);

									//acumulo total
									$objetivos_total = $objetivos_total + $objetivo;
									$cumplimiento_total = $cumplimiento_total + $cumplimiento;

									$ancho = $cumplimiento_por;
									if($ancho>100) $ancho = 100;
									$color = dameColorSegunAvance($cumplimiento_por);
								?>
								<?php if($distri_anterior != $apellido && $i>0) {?>
								<tr>
									<td colspan="5" class="gp-separador"></td>
								</tr>
								<?php } ?>

								<tr>
									<td><?php if($distri_anterior != $apellido) {echo '<strong>'.$apellido.'</strong>'; $distri_anterior = $apellido;}?></td>
									<td><?php echo strtoupper($linea)?></td>
									<td align="center"><?php echo $objetivo?></td>
                                    <td align="center"> </td>
									<td align="center"><div class="barra"><img src="img/barra-<?php echo $color?>.png" width="<?php echo $ancho?>%" height="15" alt="<?php echo $cumplimiento_por?>%"/></div> <span><?php echo $cumplimiento_por?>%</span></td>
								</tr>
								<?php }?>
								<?php 
								$cumplimiento_por = round(floatval($cumplimiento_total*100/$objetivos_total),1);
								$ancho = $cumplimiento_por;
								if($ancho>100) $ancho = 100;
								$color = dameColorSegunAvance($cumplimiento_por);	
								?>  
								<tr>
									<td class="gp-totales" colspan="2">TOTAL GENERAL</td>
									<td class="gp-totales" align="center"><?php echo $objetivos_total?></td>
                                    <td class="gp-totales" align="center"> </td>
									<td class="gp-totales" align="center">
										<div class="barra">
											<img src="img/barra-<?php echo $color?>.png" width="<?php echo $ancho?>%" height="15" alt="<?php echo $cumplimiento_por?>%"/>
										</div> 
										<span><?php echo $cumplimiento_por?>%</span>
									</td>
								</tr>
							</tbody>
							</table>
							<?php } else {?> 
							<table class="table table-sm">
								<thead class="thead-light">
									<tr>
										<th width="2%"></th>
										<th width="96%">No hay datos para el mes seleccionado</th>
										<th width="2%"></th>
									</tr>
								</thead>
							</table>
							<?php } ?>
						</div>
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
		<script>
			function showRegion() {
				document.getElementById('linea_contenido').style.display='none';	
				document.getElementById('distribuidor_contenido').style.display='none';	
				document.getElementById('region_contenido').style.display='flex';	

				document.getElementById('region_solapa').classList.add("active");
				document.getElementById('linea_solapa').classList.remove("active");	
				document.getElementById('distribuidor_solapa').classList.remove("active");	
			}

			function showLinea() {	
				document.getElementById('distribuidor_contenido').style.display='none';	
				document.getElementById('region_contenido').style.display='none';	
				document.getElementById('linea_contenido').style.display='flex';	

				document.getElementById('linea_solapa').classList.add("active");
				document.getElementById('distribuidor_solapa').classList.remove("active");	
				document.getElementById('region_solapa').classList.remove("active");	
			}

			function showDistribuidor() {		
				document.getElementById('region_contenido').style.display='none';	
				document.getElementById('linea_contenido').style.display='none';	
				document.getElementById('distribuidor_contenido').style.display='flex';	

				document.getElementById('distribuidor_solapa').classList.add("active");	
				document.getElementById('linea_solapa').classList.remove("active");	
				document.getElementById('region_solapa').classList.remove("active");	
			}

			function reloadMes(mes) {
				document.location = 'objetivos_distri.php?m='+mes
			}
		</script>
	</body>
</html>
