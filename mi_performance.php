<?php
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}

$ls = new listado(0, 10000);

//primero veo que sea un mes con anio
if(isset($_GET['m']) && strpos($_GET['m'], '-') !== false){
	$mes_actual = explode('-', $_GET['m'])[0];
	$anio_actual = explode('-', $_GET['m'])[1];
}
else if(isset($_GET['m'])){

	//me fijo el mes o viene dado o es el actual
	if(isset($_GET['m']) && is_numeric($_GET['m']))
		$mes_actual = $_GET['m'];
	else {
		$lastmes = $ls->getUltimoMes($_SESSION['QLMSF_idcliente']);
		$mes_actual = intval($lastmes);
	}
	$anio_actual = date('Y');
}else {
	$anio_actual = date('Y');
	$mes_actual = intval(date('m'));
}


$ranking_mes = $ls->getRankingMes($mes_actual,$anio_actual,$_SESSION['QLMSF_idgrupo']);
$nro = count($ranking_mes);
for($i=0;$i<$nro;$i++){
	if($ranking_mes[$i]['idcliente'] == $_SESSION['QLMSF_idcliente']){
		$mis_millas = round($ranking_mes[$i]['suma'], 1);
		$mi_posicion_mes = $ranking_mes[$i]['ranking_mes'];
		$mi_posicion_campeonato = $ranking_mes[$i]['ranking_total'];
	}
}

$lineas_comerciales_mes = $ls->getLineasComercialesMes($_SESSION['QLMSF_idcliente'],$mes_actual,$anio_actual);
$nro_lineas = count($lineas_comerciales_mes);
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

			<section class="gp-performance-tt">
				<div class="container">
					<h2 style="width:16rem">Mi performance</h2>
					<div class="row">
						<div class="col-lg-4">
							<h3>
								<?php echo strtoupper($nombre_mes[$mes_actual]).' '.$anio_actual?>
							</h3>
							<ul>
								<li>Millas sumadas en el mes <strong><?php echo $mis_millas;?></strong></li>
								<li>Posición en el mes <strong><?php echo $mi_posicion_mes;?></strong></li>
								<li>Posición en el campeonato <strong><?php echo $mi_posicion_campeonato;?></strong></li>
							</ul>
						</div>
						<div class="col-lg-4">
							<h3>PODIO DEL MES</h3>
							<ul>
								<?php
								for($i=0;$i<3;$i++){
									if($ranking_mes[$i]['nombre']!='')
										$socio = $ranking_mes[$i]['nombre'].' '.$ranking_mes[$i]['apellido'];
									else
										$socio = $ranking_mes[$i]['apellido'];
									if($ranking_mes[$i]['grupo']==1){
										switch($ranking_mes[$i]['equipo']) {
											case 'AMBA':
												$equipo = 'MC LAREN';
												break;
											case 'CENTRO':
												$equipo = 'FERRARI';
												break;
											case 'CUYO':
												$equipo = 'WILLIAMS';
												break;
											case 'LITORAL':
												$equipo = 'MERCEDES';
												break;
											case 'NEA':
												$equipo = 'HASS';
												break;
											case 'NOA':
												$equipo = 'RED BULL';
												break;
											case 'PBA':
												$equipo = 'ALFA ROMEO';
												break;
											case 'SUR':
												$equipo = 'RENAULT';
												break;
											default: $equipo = '';
										}
									} else {
										switch($ranking_mes[$i]['equipo']) {
											case 'GBA NOROESTE':
												$equipo = 'MC LAREN';
												break;
											case 'CAPITAL2':
												$equipo = 'FERRARI';
												break;
											case 'LITORAL':
												$equipo = 'WILLIAMS';
												break;
											case 'GBA NORTE':
												$equipo = 'MERCEDES';
												break;
											case 'CENTRO':
												$equipo = 'HASS';
												break;
											case 'GBA SUR2':
												$equipo = 'RED BULL';
												break;
											case 'CAPITAL1':
												$equipo = 'ALFA ROMEO';
												break;
											case 'LA PLATA':
												$equipo = 'RENAULT';
												break;
											default: $equipo = '';
										}
									}
								?>	
								<li><strong><?php echo $i + 1;?>.</strong> <?php echo strtoupper($socio);?> / <strong><?php echo $equipo;?></strong></li>
								<?php }?>
							</ul>
						</div>
            <div class="col-lg-4">
							<h3>MESES ANTERIORES</h3>
							<select onchange="reloadMes(this.value);">
								<?php
								$mactual = intval(date('m'));
								$count_meses = 0;
								for($i=$mactual; $i>0; $i--) {
									$count_meses++;
									if($i==$mes_actual)
										echo '<option value="'.$i.'" selected="selected">'.$nombre_mes[$i]. ' ' . date('Y') .'</option>';
									else
										echo '<option value="'.$i.'">'.$nombre_mes[$i]. ' ' . date('Y') .'</option>';
								}
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
						</div>
					</div>
				</div><!-- /.container -->
			</section>

			<section class="gp-performance-equipos">
				<?php if($_SESSION['QLMSF_idgrupo']==1){?>
				<img src="img/equipos_distri-01.svg" alt="Equipos Distribuidor"/>
				<?php } else {?>
				<img src="img/equipos_directa-01.svg" alt="Equipos Directa"/>
				<?php }?>
			</section>

			<section class="gp-performance-objetivos">
				<div class="container">
					<h2>Objetivos</h2>
					<div class="row" style="padding-top:20px">
						<div class="col-lg-12">
							<table class="table table-sm">
								<thead class="thead-light">
									<tr>
										<th width="26%">
											<?php 
											
											if($_SESSION['QLMSF_rango'] == 'repositor') {
												echo 'Incentivo';
											}
											else 
												echo 'Línea comercial';
											
											?>
											
										</th>
										<th width="12%">Objetivo</th>
										<th width="12%">Avance</th>
										<th width="12%">Millas</th>
										<th width="38%" style="text-align:center">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									for($i=0;$i<$nro_lineas;$i++){
										$linea[$i] = $ls->getObjetivosMes($mes_actual,$anio_actual,$_SESSION['QLMSF_idcliente'],$lineas_comerciales_mes[$i]['linea_comercial']);
										$objetivo = $linea[$i][0]['objetivo'];
										$suma = round($linea[$i][0]['suma'], 1);
										$avance = round($linea[$i][0]['avance'], 1);
										$avance_por = round($linea[$i][0]['avance_por'], 1);
										switch($avance_por){
											case ($avance_por >= 0 && $avance_por <= 85):
												$color = 'rojo'; break;
											case ($avance_por >= 85.1 && $avance_por <= 99.9):
												$color = 'amarillo'; break;
											case ($avance_por >= 100):
												$color = 'verde'; break;
										}
									?>
									<tr>
										<td><strong><?php echo strtoupper($lineas_comerciales_mes[$i]['linea_comercial']);?></strong></td>
										<td><?php echo $objetivo;?></td>
										<td><?php echo $avance;?></td>
										<td><?php echo $suma;?></td>
										<td align="center">
											<div class="barra">
												<img src="img/barra-<?php echo $color;?>.png" width="<?php if($avance_por <= 100) echo $avance_por; else echo '100';?>%" height="15" alt="<?php echo $avance_por;?>%"/>
											</div> 
											<span><?php echo $avance_por;?>%</span>
										</td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
						
					</div>
					<div class="row" style="padding-top:20px">  
						<div class="col-3 col-lg-1"><img src="img/icon_meta-01.svg" alt="¿Como sumar puntos?"/></div>
						<div class="col-9 col-lg-6 gp-bajada">
							<h4><strong>¿Cómo se calculan las millas?</strong></h4>
							<?php 
							
							if($_SESSION['QLMSF_rango'] != 'repositor'){
							
							?>
							Cada incentivo tendrá sus propios objetivos, pero la
							META de todos siempre será lograr la mejor ejecución en
							cada punto de venta!<br>
							Asique prepara tus neumáticos y salí a romper la pista en
							busca de la mejor góndola, punteras, exhibiciones
							adicionales y más!<br>
							Cada VUELTA que das a la pista es una EJECUCIÓN
							REALIZADA.<br>
							Cada incentivo tiene premios distintos y serán
							comunicados por su supervisor/ejecutivo.<br>
							<?php 
							}else {
								?>
								Cada incentivo tendrá sus propios objetivos, pero la META de todos siempre será lograr la mejor ejecución en cada punto de venta!<br>
								Asique prepara tus neumáticos y salí a romper la pista en busca de la mejor góndola, punteras, exhibiciones adicionales y más!<br>
								Cada VUELTA que das a la pista es una EJECUCIÓN REALIZADA<br />
								<?php
							}
							?>
						</div>
						<?php
						if($_SESSION['QLMSF_rango'] != 'repositor'){
						?>
						<div class="col-12 col-lg-5 gp-bajada">
							<h4>&nbsp;</h4>
							SUMAS MILLAS POR EL CUMPLIMIENTO DE OBJETIVOS:<br />
							•        Si llegas a un alcance entre 85% y 99% sumas el 50% de las millas<br />
							•        Si llegas al objetivo de 100% sumas el 100% de las millas<br />
							•        Y si superas el objetivo tenes millas extras (tope en 120%) 
						</div>
						<?php 
						}
						?>
					</div>
				</div><!-- /.container -->
			</section>
			<?php 
			//Si soy vendedor entonces muestro el ranking
			if($_SESSION['QLMSF_rango']=='vendedor' || $_SESSION['QLMSF_rango']=='repositor') {
				$vendedores = $ls->getSociosMillas($_SESSION['QLMSF_rango'], intval($mes_actual), intval($anio_actual));
				$vendedores_total = $ls->getSociosMillas($_SESSION['QLMSF_rango'], '', intval($anio_actual));
				$fin = count($vendedores);
				$end = count($vendedores_total);
				
				//inicializo combo de regiones de vendedores
				if($_SESSION['QLMSF_rango']=='vendedor') {
					$regiones = Array();
					$regiones['CAPITAL1'] = 0;
					$regiones['CAPITAL2'] = 0;
					$regiones['CENTRO'] = 0;
					$regiones['GBA NOROESTE'] = 0;
					$regiones['GBA NORTE'] = 0;
					$regiones['GBA SUR2'] = 0;
					$regiones['LA PLATA'] = 0;
					$regiones['LITORAL'] = 0;
				}

				if($_SESSION['QLMSF_rango'] == 'repositor') {
					$regiones = Array();
					$regiones['AMBA'] = 0;
					$regiones['CENTRO'] = 0;
					$regiones['CUYO'] = 0;
					$regiones['LITORAL'] = 0;
					$regiones['NEA'] = 0;
					$regiones['NOA'] = 0;
					$regiones['PBA'] = 0;
					$regiones['SUR'] = 0;
				}

				$regiones_total = $regiones;
				$regiones_canti = $regiones;
				$regiones_prome = $regiones;
				$regiones_total_prome = $regiones;
				
				//sumo por region
				$fin = count($vendedores);
				for($i=0;$i<$fin;$i++) {
					$regiones[$vendedores[$i]['equipo']] = $regiones[$vendedores[$i]['equipo']] + intval($vendedores[$i]['suma']);
				}

				//cuento por region
				$fin = count($vendedores);
				for($i=0;$i<$fin;$i++) {
					$regiones_canti[$vendedores[$i]['equipo']] = $regiones_canti[$vendedores[$i]['equipo']] + 1;
				}

				//promedio por region mensual
				foreach ($regiones as $region => $puntaje) {
					$regiones_prome[$region] = floatval($puntaje/$regiones_canti[$region]);
				}

				$end = count($vendedores_total);
				for($i=0;$i<$end;$i++) {
					$regiones_total[$vendedores_total[$i]['equipo']] = $regiones_total[$vendedores_total[$i]['equipo']] + intval($vendedores_total[$i]['suma']);
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
			
			<!--Ranking por region mensual-->
			<section class="gp-ranking-distri">
				<div class="container">
					<h5 style="width:6rem">Directa</h5>
					<h2>Ranking por región</h2>
					<ul class="nav nav-tabs">
						<li id="region_solapa_mes" class="active">
							<a onclick="showRegionMes();" href="javascript:void(1);"><?php echo $nombre_mes[$mes_actual]?> <?php echo $anio?></a>
						</li>
						<li id="region_solapa_total"><a onclick="showRegionTotal();" href="javascript:void(1);">Campeonato</a></li>
						<li class="gp-meses">
						<select onchange="reloadMes(this.value);">
								<?php
								$mactual = intval(date('m'));
								$count_meses = 0;
								for($i=$mactual; $i>0; $i--) {
									$count_meses++;
									if($i==$mes_actual)
										echo '<option value="'.$i.'" selected="selected">'.$nombre_mes[$i]. ' ' . date('Y') .'</option>';
									else
										echo '<option value="'.$i.'">'.$nombre_mes[$i]. ' ' . date('Y') .'</option>';
								}
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
													$fin = count($vendedores);
													for($j=0;$j<$fin;$j++) {
														if($vendedores[$j]['equipo'] == $region) {
													?>
													<tr> 
														<td width="50%"><strong><?php echo strtoupper($vendedores[$j]['socio'])?></strong></td>
														<td width="35%" class="<?php echo $css?>"><?php echo strtoupper($equipo)?></td>
														<td align="right"><strong><?php echo intval($vendedores[$j]['suma'])?> ml</strong></td>
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
			<!--Ranking por region total-->
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
													$end = count($vendedores_total);
													for($j=0;$j<$end;$j++) {
														if($vendedores_total[$j]['equipo'] == $region) {
													?>
													<tr>
														<td width="50%"><strong><?php echo strtoupper($vendedores_total[$j]['socio'])?></strong></td>
														<td width="35%" class="<?php echo $css?>"><?php echo strtoupper($equipo)?></td>
														<td align="right"><strong><?php echo intval($vendedores_total[$j]['suma'])?> ml</strong></td>
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
								<h5 style="width:6rem">Directa</h5>
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
											<a onclick="showSociosMes();" href="javascript:void();"><?php echo $nombre_mes[$mes_actual]?> <?php echo $anio?></a>
										</li>
										<li id="socios_solapa_total" ><a onclick="showSociosTotal();" href="javascript:void();">Campeonato</a></li>
										<li class="gp-meses">
										<select onchange="reloadMes(this.value);">
											<?php
											$mactual = intval(date('m'));
											$count_meses = 0;
											for($i=$mactual; $i>0; $i--) {
												$count_meses++;
												if($i==$mes_actual)
													echo '<option value="'.$i.'" selected="selected">'.$nombre_mes[$i]. ' ' . date('Y') .'</option>';
												else
													echo '<option value="'.$i.'">'.$nombre_mes[$i]. ' ' . date('Y') .'</option>';
											}
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
										<?php if($fin>0) {?>
										<div class="col-lg-9">
											<table class="table table-sm">
												<thead>
													<th width="50%"><strong>Nombre</strong></th>
													<th width="35%">Equipo</th>
													<th align="right" style="text-align:right"><strong>millas</strong></th>
												</thead>
												<tbody>
													<?php 
													for($j=0;$j<$fin;$j++) {
														$region = strtoupper($vendedores[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$css = dameCssSegunRegion($region);
													?>
													<tr> 
														<td width="50%"><strong><?php echo strtoupper($vendedores[$j]['socio'])?></strong></td>
														<td width="35%" class="<?php echo $css?>"><?php echo $equipo?> / <?php echo $region?></td>
														<td align="right"><strong><?php echo intval($vendedores[$j]['suma'])?> ml</strong></td>
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
													for($j=0;$j<$fin;$j++) {
														$i++;
														$region = strtoupper($vendedores[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$imagen = dameImgSegunRegion($region);
														if($i<4) {
													?>  
													<li>
														<h3><?php echo strtoupper($vendedores[$j]['socio'])?></h3>
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
										<?php if($end>0) {?>	
										<div class="col-lg-9">
											<table class="table table-sm">
												<thead>
													<th width="50%"><strong>Nombre</strong></th>
													<th width="35%">Equipo</th>
													<th align="right" style="text-align:right"><strong>millas</strong></th>
												</thead>
												<tbody>
													<?php 
													for($j=0;$j<$end;$j++) {
														$region = strtoupper($vendedores_total[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$css = dameCssSegunRegion($region);
													?>
													<tr> 
														<td width="50%"><strong><?php echo strtoupper($vendedores_total[$j]['socio'])?></strong></td>
														<td width="35%" class="<?php echo $css?>"><?php echo $equipo?> / <?php echo $region?></td>
														<td align="right"><strong><?php echo intval($vendedores_total[$j]['suma'])?> ml</strong></td>
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
													for($j=0;$j<$end;$j++) {
														$i++;
														$region = strtoupper($vendedores_total[$j]['equipo']);
														$equipo = dameEquipoSegunRegion($region);
														$imagen = dameImgSegunRegion($region);
														if($i<4) {
													?>  
													<li>
														<h3><?php echo strtoupper($vendedores_total[$j]['socio'])?></h3>
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
			
			<?php 
			}
			?>
			
			
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
			function reloadMes(mes) {
				document.location = 'mi_performance.php?m='+mes
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

			function reloadMes(mes) {
				document.location = 'mi_performance.php?m='+mes
			}
		</script>
	</body>
</html>
