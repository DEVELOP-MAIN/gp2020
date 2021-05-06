<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';

if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];
if(isset($_REQUEST['ok'])) $ok = $_REQUEST['ok']; else  $ok = false;

//incio el objeto
$clnt = new cliente();

//Me fijo si vengo de POST
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	//selecciono
	$clnt->select($idcliente);
	
	$razon_social 					= validaVars($_POST['razon_social']);
	$nombre 									= validaVars($_POST['nombre']);
	$apellido 									= validaVars($_POST['apellido']);
	$clave 											= validaVars($_POST['clave']);
	$email 											= validaVars($_POST['email']);
	$cuit 												= validaVars($_POST['cuit']);
	$domicilio 								= validaVars($_POST['domicilio']);
	$domicilio_provincia = validaVars($_POST['domicilio_provincia']);
	$domicilio_localidad = validaVars($_POST['domicilio_localidad']);
	$domicilio_cp 					= validaVars($_POST['domicilio_cp']);
	$tel_movil 								= validaVars($_POST['tel_movil']);
	$tel_otro 								= validaVars($_POST['tel_otro']);
	
	$clnt->setEstado('C');
	$clnt->setRazon_social($razon_social);
	$clnt->setNombre($nombre);
	$clnt->setApellido($apellido);
	$clnt->setClave($clave);
	$clnt->setEmail($email);
	$clnt->setCuit($cuit);
	$clnt->setDomicilio($domicilio);
	$clnt->setDomicilio_provincia($domicilio_provincia);
	$clnt->setDomicilio_localidad($domicilio_localidad);
	$clnt->setDomicilio_cp($domicilio_cp);
	$clnt->setTel_movil($tel_movil);
	$clnt->setTel_otro($tel_otro);
	$clnt->setAcepta_basesycond('1');
	
	//porque hace un header?
	if($clnt->update($_SESSION['QLMSF_idcliente'])) {
		//actualizo variables de session
		$_SESSION['QLMSF_email']					= $email;		
		$_SESSION['QLMSF_domicilio']				= $domicilio;		
		$_SESSION['QLMSF_provincia']				= $domicilio_provincia;		
		$_SESSION['QLMSF_localidad']				= $domicilio_localidad;		
		$_SESSION['QLMSF_cp']						= $domicilio_cp;		
		$_SESSION['QLMSF_nombre']					= $nombre;
		$_SESSION['QLMSF_apellido']					= $apellido;
		$_SESSION['QLMSF_razon_social']				= $razon_social;		
		$ok = true;
	}	
}

//traigo los datos que existen del supermercado para poner en el formulario
if($clnt->select($idcliente))
{
 	$codigo_cliente 			= $clnt->getCodigo_cliente();
	$codigo_unico 				= $clnt->getCodigo_unico();
	$razon_social 					= $clnt->getRazon_social();
	$nombre 									= $clnt->getNombre();
	$apellido 									= $clnt->getApellido();
	$clave 											= $clnt->getClave();
	$email 											= $clnt->getEmail();
	$cuit 												= $clnt->getCuit();
	$domicilio 								= $clnt->getDomicilio();
	$domicilio_provincia	= $clnt->getDomicilio_provincia();
	$domicilio_localidad = $clnt->getDomicilio_localidad();
	$domicilio_cp 					= $clnt->getDomicilio_cp();
	$tel_movil 								= $clnt->getTel_movil();
	$tel_otro 								= $clnt->getTel_otro();
}
else
{
 	$codigo_cliente 			= '';
	$codigo_unico 				= '';
	$razon_social 					= '';
	$nombre 									= '';
	$apellido 									= '';
	$clave 											= '';
	$email 											= '';
	$cuit 												= '';
	$domicilio 								= '';
	$domicilio_provincia	= '';
	$domicilio_localidad = '';
	$domicilio_cp 					= '';
	$tel_movil 								= '';
	$tel_otro 								= '';
}
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
	<?php include_once('cabecera.php');?>
  <!-- Contenido -->
    
	<div class="container"> 
		<div class="row">
			<div class="col-lg-12 ttBusca">
				<div class="tt-Canjes rb-ttBusca"><h1><i class="fa fa-user"></i> <?php echo _('Datos personales');?></h1></div>
				<div class="separa"></div>
			</div>
    
		<div class="mis-datos">
			<form id="DatosForm" action="datos_personales.php" method="POST" onSubmit="return false;">
				<div class="col-lg-12">
					<div class="cabecera">
                    <p><?php echo _('Aqu&iacute; puede modificar sus datos personales.');?></p>
						<span class="codigo"><strong><?php echo _('C&oacute;digo de Cliente:');?></strong> <?php echo $codigo_cliente;?></span>
						<span class="codigo"><strong><?php echo _('C&oacute;digo &Uacute;nico:');?></strong> <?php echo $codigo_unico;?></span>
						<div class="alerta-mod" style="display:<?php if($ok) echo 'block'; else echo 'none';?>;"><?php echo _('Tus datos se guardaron correctamente');?></div>
					</div>
					<div class="col-sm-12 col-lg-12 col-md-12 rb-form">
							<div class="control-group form-group controls col-sm-8 col-md-8">
								<label><?php echo _('Raz&oacute;n Social:');?></label>
								<input id="razon_social" name="razon_social" value="<?php echo $razon_social;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('CUIT:');?></label>
								<input id="cuit" name="cuit" value="<?php echo $cuit;?>" type="text" class="form-control">
							</div>
								
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('Nombre:');?></label>
								<input id="nombre" name="nombre" value="<?php echo $nombre;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('Apellido:');?></label>
								<input id="apellido" name="apellido" value="<?php echo $apellido;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('Email:');?></label>
								<input id="email" name="email" value="<?php echo $email;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-12 col-md-12">
								<label><?php echo _('Domicilio:');?></label>
								<input id="domicilio" name="domicilio" value="<?php echo $domicilio;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-43 col-md-4">
								<label><?php echo _('Provincia:');?></label>
								<input id="domicilio_provincia" name="domicilio_provincia" value="<?php echo $domicilio_provincia;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('Localidad:');?></label>
								<input id="domicilio_localidad" name="domicilio_localidad" value="<?php echo $domicilio_localidad;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('C&#243;digo Postal:');?></label>
								<input id="domicilio_cp" name="domicilio_cp" value="<?php echo $domicilio_cp;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('Tel&#233;fono fijo:');?></label>
								<input id="tel_otro" name="tel_otro" value="<?php echo $tel_otro;?>" type="text" class="form-control">
							</div>
							<div class="control-group form-group controls col-sm-4 col-md-4">
								<label><?php echo _('Tel&#233;fono m&#243;vil:');?></label>
								<input id="tel_movil" name="tel_movil" value="<?php echo $tel_movil;?>" type="text" class="form-control">
							</div>
                            <div class="col-sm-4 col-md-4">
						<label><?php echo _('Clave:');?></label> (<?php echo _('puede cambiar su clave');?>)
						<input id="clave" name="clave" value="<?php echo $clave;?>" type="password" class="form-control">
					</div>
						<div style="clear:both; width:100%; height:1px"></div> 
					</div>
				</div>
				<div class="guarda-dp">
						<a href="#" onclick="sendData('DatosForm');" class="btn-guardar"><?php echo _('Guardar mis datos');?></a>
				</div>
			</form>
			<div style="clear:both; width:100%; height:1px"></div>    
		</div>
       </div> 
	</div>
  <!-- /.container -->

	<div class="container pie-sitio">
		<!-- Footer -->
		<footer>
			<div class="row">
				<div class="col-lg-12">
					<a href="#"><?php echo _('Bases y Condiciones');?></a> 
					
					<p>©2017</p>
				</div>
			</div>
		</footer>
	</div>
	<!-- /.container -->

	<!-- js placed at the end of the document so the pages load faster -->
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="validacion/js/formValidation.js"></script>
	<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
	<link rel="stylesheet" href="validacion/css/formValidation.css"/>
	<script>
	$(document).ready(function() {
		$('#DatosForm').formValidation({
			message: '<?php echo _('No es valido');?>',
			fields: {
				razon_social: {
					message: '<?php echo _('Ingres&#225; la razon social');?>',
					validators: {
							notEmpty: {message: '<?php echo _('Ingres&#225; tu razon social')?>'}
					}
				},
				cuit: {
					message: '<?php echo _('Ingres&#225; el CUIT');?>',
					validators: {
							notEmpty: {message: '<?php echo _('Ingres&#225; tu CUIT')?>'}
					}
				},
				nombre: {
					message: '<?php echo _('Ingres&#225; el nombre');?>',
					validators: {
							notEmpty: {message: '<?php echo _('Ingres&#225; tu nombre')?>'}
					}
				},
				apellido: {
					message: '<?php echo _('Ingres&#225; el apellido')?>',
					validators: {
							notEmpty: {message: '<?php echo _('Ingres&#225; tu apellido')?>'}
					}
				},
				email: {
					message: '<?php echo _('Ingres&#225; el email')?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingres&#225; el email')?>'},
						emailAddress: {message: '<?php echo _('No es una cuenta de email valida')?>'}
					}
				},
				clave: {
					message: '<?php echo _('Indic&#225; la clave')?>',
					validators: {
							notEmpty: {message: '<?php echo _('Indic&#225; la clave')?>'}
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
