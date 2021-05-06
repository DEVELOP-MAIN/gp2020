<?php
require_once 'admin/php/class/class.socio.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];
if(isset($_REQUEST['ok'])) $ok = $_REQUEST['ok']; else  $ok = false;

//incio el objeto
$clnt = new socio();

//Me fijo si vengo de POST
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	//selecciono
	$clnt->select($idcliente);
	$email 			= validaVars($_POST['email']);
	$direccion	= validaVars($_POST['direccion']);
	$localidad	= validaVars($_POST['localidad']);
	$provincia	= validaVars($_POST['provincia']);
	$telefono 	= validaVars($_POST['telefono']);
	$nuevaclave	= validaVars2($_POST['nueva_clave']);

	$clnt->setEmail($email);
	$clnt->setDireccion($direccion);
	$clnt->setLocalidad($localidad);
	$clnt->setProvincia($provincia);
	$clnt->setTelefono($telefono);
	if($nuevaclave != '') $clnt->setClave($nuevaclave);

	$clnt->update($_SESSION['QLMSF_idcliente']);
	//porque hace un header?
	if($clnt->update($_SESSION['QLMSF_idcliente'])) {
		//actualizo variables de session
		$_SESSION['QLMSF_email']			= $email;
		$_SESSION['QLMSF_domicilio']	= $direccion;
		$_SESSION['QLMSF_localidad']	= $localidad;
		$_SESSION['QLMSF_provincia']	= $provincia;
		$_SESSION['QLMSF_telefono']		= $telefono;
		$ok = true;
	}	
}

//traigo los datos que existen del socio logueado para poner en el formulario
if($clnt->select($idcliente)){
 	$rango 			= $clnt->getRango();
if($rango=='NULL') $rango = '';
$region 		= $clnt->getRegion();
if($region=='NULL') $region = '';
$ejecutivo 	= $clnt->getEjecutivo();
if($ejecutivo=='NULL') $ejecutivo = '';
$jefe 			= $clnt->getJefe();
if($jefe=='NULL') $jefe = '';
$gerente 		= $clnt->getGerente();
if($gerente=='NULL') $gerente = '';
$nombre 		= $clnt->getNombre();
if($nombre=='NULL') $nombre = '';
$apellido 	= $clnt->getApellido();
if($apellido=='NULL') $apellido = '';
$email 			= $clnt->getEmail();
if($email=='NULL') $email = '';
$direccion	= $clnt->getDireccion();
if($direccion=='NULL') $direccion = '';
$localidad	= $clnt->getLocalidad();
if($localidad=='NULL') $localidad = '';
$provincia	= $clnt->getProvincia();
if($provincia=='NULL') $provincia = '';
$telefono 	= $clnt->getTelefono();
if($telefono=='NULL') $telefono = '';

} else {
 	$rango 			= '';
	$region 		= '';
	$ejecutivo 	= '';
	$jefe 			= '';
	$gerente 		= '';
	$nombre 		= '';
	$apellido 	= '';
	$email 			= '';
	$direccion	= '';
	$localidad	= '';
	$provincia	= '';
	$telefono 	= '';
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

			<section class="gp-balance">
				<div class="container">
					<h2 style="width:16rem">Datos personales</h2>
					<div class="row" style="padding-top:30px">
						<div class="col-lg-1"></div> 
						<div class="col-lg-5">
							<strong><?php echo $nombre.' '.$apellido;?></strong><br />
							Rango: <strong><?php echo $rango;?></strong><br />
							Regi&#243;n: <strong><?php echo $region;?></strong>
						</div>
						<div class="col-lg-5">
							Ejecutivo: <?php echo $ejecutivo;?><br />
							Jefe: <?php echo $jefe;?><br />
							Gerente: <?php echo $gerente;?>
						</div>
					</div>
					<form id="DatosForm" action="datos_personales.php" method="POST" onSubmit="return false;">
					<div class="row" style="padding-top:30px;">
						<div class="col-lg-1"></div> 
						<div class="col-lg-10">
							<div class="gp-franja-lineas"></div>
							<h3 style="float:left">Datos de contacto</h3>
						</div> 
						<div class="col-lg-1"></div> 
						<div class="col-lg-1"></div> 
						<div class="col-lg-5">
							<label >Email</label>
							<input class="form-control" type="email" id="email" name="email" <?php if($email=='') echo 'placeholder="Necesitamos que ingreses una cuenta de email"'; else echo 'value="'.$email.'"';?>>
							<div class="row">
								<div class="col-lg-12">
									<label>Dirección de contacto (Calle, Nro. Dpto.)</label>
									<input class="form-control" type="text" id="direccion" name="direccion" value="<?php echo $direccion;?>">
								</div>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="row">
								<div class="col-lg-6">
									<label>&#191;Quer&#233;s cambiar tu clave?</label>
									<input class="form-control" type="text" id="nueva_clave" name="nueva_clave" value="">
								</div>
								<div class="col-lg-6">
									<label>Teléfono</label>
									<input class="form-control" type="text" id="telefono" name="telefono" value="<?php echo $telefono;?>">
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<label>Localidad</label>
									<input class="form-control" type="text" id="localidad" name="localidad" value="<?php echo $localidad;?>">
								</div>
								<div class="col-lg-6">
									<label>Provincia</label>
									<input class="form-control" type="text" id="provincia" name="provincia" value="<?php echo $provincia;?>">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-1"></div>
						<div class="col-lg-10">
							<div class="row gp-btn-datos">
								<div class="col-lg-4"></div>
								<div class="col-lg-4">
									<a href="#" onclick="sendData('DatosForm');" class="gp-btn-rojo-borde">MODIFICAR MIS DATOS</a>
								</div>
							</div>
						</div>
						<div class="col-lg-1"></div>
					</div>
					</form>
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
		<!-- js placed at the end of the document so the pages load faster -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="validacion/js/formValidation.js"></script>
		<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
		<link rel="stylesheet" href="validacion/css/formValidation.css"/>
		<script>
		$(document).ready(function() {
			$('#DatosForm').formValidation({
				message: '<?php echo 'No es valido';?>',
				fields: {
					email: {
						message: '<?php echo 'Ingrese su email'?>',
						validators: {
							notEmpty: {message: '<?php echo '<strong>INGRESE UNA CUENTA DE EMAIL</strong>'?>'},
							emailAddress: {message: '<?php echo '<strong>NO ES UNA CUENTA DE EMAIL VALIDA</strong>'?>'}
						}
					}
				}
			});
		});

		function sendData(formulario) {
			$('#'+formulario).formValidation('validate');
			if($('#'+formulario).data('formValidation').isValid())
				document.getElementById(formulario).submit();
		}
		</script>
	</body>
</html>
