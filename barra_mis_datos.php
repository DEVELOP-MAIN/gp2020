<?php 
if($_SESSION['QLMSF_idgrupo']==1){
	switch($_SESSION['QLMSF_region']) {
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
	switch($_SESSION['QLMSF_region']) {
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

$mi_casco = 'cascos/'.$_SESSION['QLMSF_codigo_unico'].'.png';
?>
<section class="gp-misdatos">
	<div class="container-lg">
		<div class="row">
			<div class="col-lg-6">
				<div class="row">
					<div class="col-lg-6" style="padding:0">
						<div class="corredor">
							<div class="gp-casco">
								<img src="<?php if (file_exists($mi_casco)) echo $mi_casco; else echo 'cascos/no-elegido.png';?>" alt="<?php echo $_SESSION['QLMSF_nombre'].' '.$_SESSION['QLMSF_apellido'];?>"/>
							</div>
							<small><?php echo strtoupper($_SESSION['QLMSF_rango']);?></small><br>
							<?php echo $_SESSION['QLMSF_nombre'].' '.$_SESSION['QLMSF_apellido'];?><br>
							<span>equipo <?php echo $equipo;?></span>
						</div>
					</div>
					<div class="col-lg-3 col-4 puntos"><h2><?php echo round($_SESSION['QLMSF_puntos'], 1);?></h2><small>millas</small></div>
					<div class="col-lg-3 col-4 puntos"><h2><?php echo $_SESSION['QLMSF_ranking'];?></h2><small>ranking</small></div>
					<div class="col-4 gp-logoff-mb"><a href="php/logout.php" class="gp-btn-rojo-borde">CERRAR&#160;SESIÓN</a></div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="row gp-mis-accesos">
					<div class="col-lg-3 col-4 gp-icon">
						<a href="datos_personales.php">
							<div><img src="img/icono_misdatos.svg" alt="Datos personales"/></div>
							<small>DATOS</small><small>PERSONALES</small>
						</a>
					</div>
					<div class="col-lg-3 col-4 gp-icon">
						<a href="mis_canjes.php">
							<div><img src="img/icono_miscanjes.svg" alt="Mis canjes"/></div>
							<small>MIS</small><small>CANJES</small>
						</a>
					</div>
					<div class="col-lg-3 col-4 gp-icon">
						<a href="balance_millas.php">
							<div><img src="img/icono_mibalance.svg" alt="Balance de puntos"/></div>
							<small>MI</small><small>BALANCE</small>
						</a>
					</div>
					<div class="col-lg-3 gp-logoff"><a href="php/logout.php" class="gp-btn-rojo-borde">CERRAR&#160;SESIÓN</a></div>
				</div>
			</div>
		</div>
	</div>
</section>