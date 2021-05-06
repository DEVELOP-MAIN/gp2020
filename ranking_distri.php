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

if(isset($_GET['st']))
	$_SESSION['st'] = $_GET['st'];

//falta armar un array de mis regiones para luego permitir desplegar solo las mias

//me fijo el mes o viene dado o es el actual

//primero veo que sea un mes con anio
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
$distris = $lst->getSociosMillas('distribuidor', intval($mes), intval($anio));
$distris_total = $lst->getSociosMillas('distribuidor', '', intval($anio));

//inicializo combo de regiones de distri
$regiones = Array();
$regiones['AMBA'] = 0;
$regiones['CENTRO'] = 0;
$regiones['CUYO'] = 0;
$regiones['LITORAL'] = 0;
$regiones['NEA'] = 0;
$regiones['NOA'] = 0;
$regiones['PBA'] = 0;
$regiones['SUR'] = 0;

$regiones_prome = $regiones;
$regiones_canti = $regiones;
$regiones_total = $regiones;
$regiones_total_prome = $regiones;

//sumo por region
$fin = count($distris);
for($i=0;$i<$fin;$i++) {
	$regiones[$distris[$i]['equipo']] = $regiones[$distris[$i]['equipo']] + intval($distris[$i]['suma_individual']);
}

//cuento por region
$fin = count($distris);
for($i=0;$i<$fin;$i++) {
	$regiones_canti[$distris[$i]['equipo']] = $regiones_canti[$distris[$i]['equipo']] + 1;
}

//promedio por region mensual
foreach ($regiones as $region => $puntaje) {
	$regiones_prome[$region] = floatval($puntaje/$regiones_canti[$region]);
}

$end = count($distris_total);
for($i=0;$i<$end;$i++) {
	$regiones_total[$distris_total[$i]['equipo']] = $regiones_total[$distris_total[$i]['equipo']] + intval($distris_total[$i]['suma_individual']);
}

//promedio por region total
foreach ($regiones_total as $region => $puntaje) {
	$regiones_total_prome[$region] = floatval($puntaje/$regiones_canti[$region]);
}


//ordeno por regiones de mayor a menor puntaje
arsort($regiones);
arsort($regiones_total);
arsort($regiones_prome);
arsort($regiones_total_prome);
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
		<style>
                        .div_performance {
                                font-size: 18px;
                                color: black;
                                margin-bottom: 14px;
                                font-weight: 400;
                                font-family: 'Formula1 Display';
                                border-bottom: 4px solid #838c85;
                                padding-bottom: 4px;
                                margin-top: 15px;
                        }

                        .div_performance span {
                                cursor: pointer;
                                padding: 10px;
                                padding-top: 12px;
                        }

                        .div_performance span.active {
                                background: #838c84;
                                color: white;
                                border-radius: 10px 10px 0px 0px;
                        }
                </style>
	</head>
	<body>
		<?php include_once('cabecera_superior.php');?>
		<main role="main">
			<!--barra Mis Datos-->
			<?php include_once('barra_mis_datos_superior.php');?>

			<section class="gp-performance-tt">
				<div class="container">
					<h2 style="width:16rem">Mi performance</h2>
					<div class="div_performance">
                                                <span onclick="location.href='ranking_distri.php'" class="active">Performance Ventas</span> | <span onclick="location.href='ranking_repositor.php'">Performance Ejecución</span>
                                        </div>
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
				<img src="img/equipos_distri-01.svg" alt="Equipos de Distribuidores"/>
			</section>

			<section class="gp-ranking-distri">
				<div class="container">
					<h5 style="width:9.5rem">Distribuidores</h5>
					<h2>Ranking por región</h2>
					<ul class="nav nav-tabs">
						<li id="region_solapa_mes" class="active">
							<a onclick="showRegionMes();" href="javascript:void(1);"><?php echo $nombre_mes[$mes]?> <?php echo $anio?></a>
						</li>
						<li id="region_solapa_total"><a onclick="showRegionTotal();" href="javascript:void(1);">Campeonato</a></li>
						<li class="gp-meses">
							<?php 
							$count_meses = 0;
							?>
							<select onchange="reloadMes(this.value);">
								<option>Meses anteriores</option>
								<?php 
								$mactual = intval(date("m"));
								$count_meses++;
								for($i=1; $i <= $mactual; $i++)
									echo '<option value="'.$i.'">'.$nombre_mes[$i] . ' ' . date('Y') .'</option>';
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

					<!--Ranking por region-->
					<div id="region_contenido_mes" class="row" style="padding-top:20px">
						<div class="col-lg-9">
							<div class="panel-group" id="accordion">
								<?php 
								$i = 0;
								foreach ($regiones_prome as $region => $puntaje) {
									$i++;
									$css = dameCssSegunRegion($region);
									$equipo = dameEquipoSegunRegion($region);
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i?>"><?php echo ($i).'. '.$region?></a>
											<span><?php echo number_format($puntaje,2,".","")?> mi <strong>+</strong></span>
										</h4>
									</div>
									<div id="collapse<?php echo $i?>" class="panel-collapse collapse in">
										<div class="panel-body">
											<table class="table table-sm">
												<tbody>
													<?php 
													for($j=0;$j<count($distris);$j++) {
														if($distris[$j]["equipo"] == $region) {
													?>
													<tr> 
														<td width="40%"><strong><?php echo strtoupper($distris[$j]["socio"])?></strong></td>
														<td width="25%" class="<?php echo $css?>"><?php echo strtoupper($equipo)?></td>
														<td align="right"><strong><?php echo intval($distris[$j]["suma_individual"])?> ml indiv.</strong></td>
														<td align="right"><strong><?php echo intval($distris[$j]["suma"])?> ml total</strong></td>
													</tr>
													<?php 
														}
													} 
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<?php
								}
								?>
								<!--fIN ACCORDION ITEMS-->
							</div>
						</div>
						<div class="col-lg-3">
							<div class="gp-podio">
								<h2>PODIO</h2>
								<img src="img/icon_podio.svg" alt="PODIO"/>
								<ol>
									<?php 
									$i = 0;
									foreach ($regiones_prome as $region => $puntaje) {
										$i++;
										if($i<4) {
											$imagen = dameImgSegunRegion($region);
											$equipo = dameEquipoSegunRegion($region);
									?>	
									<li>
										<h3><?php echo $equipo?></h3>
										<img src="img/<?php echo $imagen?>" alt="<?php echo $equipo?>"/><span><?php echo $region?></span>
									</li>
									<?php 
										}
									}
									?>
								</ol>
							</div>
						</div>
					</div>

					<!--Ranking por region-->
					<div id="region_contenido_total" class="row" style="display:none;padding-top:20px">
						<div class="col-lg-9">
							<div class="panel-group" id="accordion">
								<?php 
								$i = 0;
								foreach ($regiones_total_prome as $region => $puntaje) {
									$i++;
									$css = dameCssSegunRegion($region);
									$equipo = dameEquipoSegunRegion($region);
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapseT<?php echo $i?>"><?php echo ($i).'. '.$region?></a>
											<span><?php echo number_format($puntaje,2,".","")?> mi <strong>+</strong></span>
										</h4>
									</div>
									<div id="collapseT<?php echo $i?>" class="panel-collapse collapse in">
										<div class="panel-body">
											<table class="table table-sm">
												<tbody>
													<?php 
													for($j=0;$j<count($distris_total);$j++) {
														if($distris_total[$j]["equipo"] == $region) {
													?>
													<tr>
														<td width="40%"><strong><?php echo strtoupper($distris_total[$j]["socio"])?></strong></td>
														<td width="25%" class="<?php echo $css?>"><?php echo strtoupper($equipo)?></td>
														<td align="right"><strong><?php echo intval($distris_total[$j]["suma_individual"])?> ml indiv.</strong></td>
														<td align="right"><strong><?php echo intval($distris_total[$j]["suma"])?> ml total</strong></td>
													</tr>
													<?php 
														}
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<?php 
								}
								?>  
								<!--fIN ACCORDION ITEMS-->
							</div>
						</div>
						<div class="col-lg-3">
							<div class="gp-podio">
								<h2>PODIO</h2>
								<img src="img/icon_podio.svg" alt="PODIO"/>
								<ol>
									<?php 
									$i = 0;
									foreach ($regiones_total_prome as $region => $puntaje) {
										$i++;
										if($i<4) {
											$imagen = dameImgSegunRegion($region);
											$equipo = dameEquipoSegunRegion($region);
									?>	
									<li>
										<h3><?php echo $equipo?></h3>
										<img src="img/<?php echo $imagen?>" alt="<?php echo $equipo?>"/><span><?php echo $region?></span>
									</li>
									<?php 
										}
									}
									?>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!--Fin Ranking por Region-->

			<section class="gp-ranking-distri">
				<div class="container">
					<div class="panel-group" id="accordion2">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 style="width:9.5rem">Distribuidores</h5>
								<h2 class="panel-title" style="border-bottom:0; padding:15px 0 0 0; width:16rem">
									<a data-toggle="collapse" data-parent="#accordion2" href="#collapse10" style="width:100%">
										<img src="img/icon_desplegar-01.svg" alt="desplegar"/> Ranking general
									</a>
								</h2>
							</div>
							<div id="collapse10" class="panel-collapse collapse in">
								<div class="panel-body">
									<ul class="nav nav-tabs">
										<li id="socios_solapa_mes" class="active">
											<a onclick="showSociosMes();" href="javascript:void();"><?php echo $nombre_mes[$mes]?> <?php echo $anio?></a>
										</li>
										<li id="socios_solapa_total" ><a onclick="showSociosTotal();" href="javascript:void();">Campeonato</a></li>
										<li class="gp-meses">
											<?php 
											$count_meses = 0;
											?>
											<select onchange="reloadMes(this.value);">
												<option>Meses anteriores</option>
												<?php 
												$mactual = intval(date("m"));
												$count_meses++;
												for($i=1; $i <= $mactual; $i++)
													echo '<option value="'.$i.'">'.$nombre_mes[$i] . ' ' . date('Y') .'</option>';
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

									<div id="socios_contenido_mes" class="row" style="padding-top:20px">
										<?php if(count($distris)>0) {?>	
										<div class="col-lg-9">
											<table class="table table-sm">
												<thead>
													<th width="40%"><strong>Nombre</strong></th>
													<th width="30%">Equipo</th>
													<th align="right" style="text-align:right"><strong>ml indiv.</strong></th>
													<th align="right" style="text-align:right"><strong>ml total.</strong></th>
												</thead>
												<tbody>
													<?php 
													for($j=0;$j<count($distris);$j++) {
														$region = strtoupper($distris[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$css = dameCssSegunRegion($region);
													?>
													<tr> 
														<td width="40%"><strong><?php echo strtoupper($distris[$j]['socio'])?></strong></td>
														<td width="30%" class="<?php echo $css?>"><?php echo $equipo?> / <?php echo $region?></td>
														<td align="right"><strong><?php echo intval($distris[$j]['suma_individual'])?> ml</strong></td>
														<td align="right"><strong><?php echo intval($distris[$j]['suma'])?> ml</strong></td>
													</tr>
													<?php }?>
												</tbody>
											</table>
										</div>
										<div class="col-lg-3">
											<div class="gp-podio">
												<h2>PODIO</h2>
												<img src="img/icon_podio.svg" alt="PODIO"/>
												<ol>
													<?php 
													$i = 0;
													for($j=0;$j<count($distris);$j++) {
														$i++;
														$region = strtoupper($distris[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$imagen = dameImgSegunRegion($region);
														if($i<4) {
													?>  
													<li>
														<h3><?php echo strtoupper($distris[$j]['socio'])?></h3>
														<img src="img/<?php echo $imagen?>" alt="<?php echo $equipo?>"/>
														<span><?php echo $equipo?> / <?php echo $region?></span>
													</li>
													<?php 
														}
													}
													?>
												</ol>
											</div>
										</div>
										<?php } else {?>
										<div class="col-lg-9">
											<table class="table table-sm">
												<thead>
													<th width="2%"></th>
													<th width="96%">No hay datos para el mes seleccionado</th>
													<th width="2%"></th>
												</thead>
											</table>	
										</div>
										<?php }?>	
									</div>

									<div id="socios_contenido_total" class="row" style="display:none;padding-top:20px">
										<?php if(count($distris_total)>0) {?>	
										<div class="col-lg-9">			
											<table class="table table-sm">
												<thead>
													<th width="40%"><strong>Nombre</strong></th>
													<th width="30%">Equipo</th>
													<th align="right" style="text-align:right"><strong>millas indiv.</strong></th>
													<th align="right" style="text-align:right"><strong>millas total.</strong></th>
												</thead>
												<tbody>
													<?php 
													for($j=0;$j<count($distris_total);$j++) {
														$region = strtoupper($distris_total[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$css = dameCssSegunRegion($region);
													?>  
													<tr> 
														<td width="40%"><strong><?php echo strtoupper($distris_total[$j]['socio'])?></strong></td>
														<td width="30%" class="<?php echo $css?>"><?php echo $equipo?> / <?php echo $region?></td>
														<td align="right"><strong><?php echo intval($distris_total[$j]['suma_individual'])?> ml</strong></td>
														<td align="right"><strong><?php echo intval($distris_total[$j]['suma'])?> ml</strong></td>
													</tr>
													<?php }?>
												</tbody>
											</table>
										</div>
										<div class="col-lg-3">
											<div class="gp-podio">
												<h2>PODIO</h2>
												<img src="img/icon_podio.svg" alt="PODIO"/>
												<ol>
													<?php 
													$i = 0;
													for($j=0;$j<count($distris_total);$j++) {
														$i++;
														$region = strtoupper($distris_total[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$imagen = dameImgSegunRegion($region);
														if($i<4) {
													?>  
													<li>
														<h3><?php echo strtoupper($distris_total[$j]['socio'])?></h3>
														<img src="img/<?php echo $imagen?>" alt="<?php echo $equipo?>"/>
														<span><?php echo $equipo?> / <?php echo $region?></span>
													</li>
													<?php 
														}
													}
													?>
												</ol>
											</div>
										</div>
										<?php } else {?>
										<div class="col-lg-9">
											<table class="table table-sm">
												<thead>
													<th width="2%"></th>
													<th width="96%">No hay datos para el mes seleccionado</th>
													<th width="2%"></th>
												</thead>
											</table>
										</div>
										<?php }?>
									</div>
								</div>
							</div>
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
			function showRegionMes() {
				document.getElementById('region_contenido_total').style.display='none';	
				document.getElementById('region_contenido_mes').style.display='flex';	
				document.getElementById('region_solapa_mes').classList.add("active");
				document.getElementById('region_solapa_total').classList.remove("active");	
			}

			function showRegionTotal() {
				document.getElementById('region_contenido_mes').style.display='none';	
				document.getElementById('region_contenido_total').style.display='flex';	
				document.getElementById('region_solapa_mes').classList.remove("active");
				document.getElementById('region_solapa_total').classList.add("active");	
			}

			function showSociosMes() {
				document.getElementById('socios_contenido_total').style.display='none';	
				document.getElementById('socios_contenido_mes').style.display='flex';	
				document.getElementById('socios_solapa_mes').classList.add("active");
				document.getElementById('socios_solapa_total').classList.remove("active");	
			}

			function showSociosTotal() {
				document.getElementById('socios_contenido_mes').style.display='none';	
				document.getElementById('socios_contenido_total').style.display='flex';	
				document.getElementById('socios_solapa_mes').classList.remove("active");
				document.getElementById('socios_solapa_total').classList.add("active");	
			}

			function reloadMes(mes) {
				document.location = 'ranking_distri.php?m='+mes
			}
		</script>
	</body>
</html>
