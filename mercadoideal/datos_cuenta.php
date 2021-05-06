<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';

if(!isset($_SESSION)) {session_start();}
if(isset($_REQUEST['c'])) $idcliente = base64_decode($_REQUEST['c']); else  $idcliente = '';

//incio el objeto
$clnt = new cliente();

//Me fijo si vengo de POST
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$clnt->select($_POST['idc']);
	
	$razon_social 		= validaVars($_POST['razon_social']);
	$nombre 			= validaVars($_POST['nombre']);
	$apellido 			= validaVars($_POST['apellido']);
	$clave 				= validaVars($_POST['clave']);
	$email 				= validaVars($_POST['email']);
	$email_personal		= validaVars($_POST['email_personal']);
	$cuit 				= validaVars($_POST['cuit']);
	$domicilio 			= validaVars($_POST['domicilio']);
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
	$clnt->setEmail_personal($email_personal);
	$clnt->setCuit($cuit);
	$clnt->setDomicilio($domicilio);
	$clnt->setDomicilio_provincia($domicilio_provincia);
	$clnt->setDomicilio_localidad($domicilio_localidad);
	$clnt->setDomicilio_cp($domicilio_cp);
	$clnt->setTel_movil($tel_movil);
	$clnt->setTel_otro($tel_otro);
	$clnt->setAcepta_basesycond('1');
	
	if($clnt->update($_POST['idc'])) {
		//actualizo variables de session
		$_SESSION['QLMSF_email']					= $email;		
		$_SESSION['QLMSF_domicilio']				= $domicilio;		
		$_SESSION['QLMSF_provincia']				= $domicilio_provincia;		
		$_SESSION['QLMSF_localidad']				= $domicilio_localidad;		
		$_SESSION['QLMSF_cp']						= $domicilio_cp;		
		$_SESSION['QLMSF_nombre']					= $nombre;
		$_SESSION['QLMSF_apellido']					= $apellido;
		$_SESSION['QLMSF_razon_social']				= $razon_social;		
		header('location:home.php');
	}	
}

//traigo los datos que existen del supermercado para poner en el formulario
if($clnt->select($idcliente))
{
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
	<title>Mercado Ideal</title>
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/js/fancybox/jquery.fancybox.css" rel="stylesheet" />
	
	<!-- Custom CSS -->
	<link href="fonts/fonts-esp.css?nocache=0706" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=0706" rel="stylesheet">
	
</head>

<body style="background: url(images/fd_login.png) #FFF; padding:40px 0 0 0">
	<div class="container">
	<div class="lgidioma">
         <?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=="zh_CN") {?>
			<a href="cambiar_idioma.php?l=ar_ES" title="Cambiar a idioma español"><img src="images/btn_esp_off.png" alt="Idioma español"/></a>
			<a href="cambiar_idioma.php?l=zh_CN"><img src="images/btn_chino.png" alt=""/></a>
		 <?php } else {?>	
		 	<a href="cambiar_idioma.php?l=ar_ES"><img src="images/btn_esp.png" alt="Idioma español"/></a>		 
			<a href="cambiar_idioma.php?l=zh_CN" title="Cambiar a idioma chino"><img src="images/btn_chino_off.png" alt=""/></a>
		 <?php }?>	
		 </div>
		<div class="row" style="display:block">
			<div class="col-lg-2"></div>
      <div class="col-lg-8 lgrecLogin">
				<h2><?php echo _('Confirmar datos de tu cuenta');?></h2>
        <h4><?php echo _('A continuaci&oacute;n le pedimos que complete o actualice la siguiente informaci&oacute;n de su negocio');?>:</h4>
        <form id="DatosForm" action="datos_cuenta.php" method="POST" onSubmit="return false;">
					<input type="hidden" id="idc" name="idc" value="<?php echo $idcliente;?>">
          <div class="col-lg-9">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Raz&oacute;n Social');?>:</label>
                <input type="text" class="form-control" id="razon_social" name="razon_social" value="<?php echo $razon_social;?>">
              </div>
            </div>
          </div>
          <div class="col-lg-3">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Clave');?>:</label>
                <input type="text" class="form-control" id="clave" name="clave" value="<?php echo $clave;?>">
              </div>
            </div>
          </div>
          <div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Nombre');?>:</label>
								<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>">
							</div>
						</div>
          </div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Apellido');?>:</label>
								<input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $apellido;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('CUIT');?>:</label>
								<input type="text" class="form-control" id="cuit" name="cuit" value="<?php echo $cuit;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Email');?>:</label>
								<input type="text" class="form-control" id="email" name="email" value="<?php echo $email;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Tel&#233;fono m&#243;vil');?>:</label>
								<input type="text" class="form-control" id="tel_movil" name="tel_movil" value="<?php echo $tel_movil;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Tel&#233;fono fijo');?>:</label>
								<input type="text" class="form-control" id="tel_otro" name="tel_otro" value="<?php echo $tel_otro;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Domicilio');?>:</label> <?php echo _('Es importante que este bien completa la dirección del Punto de Venta');?>.
								<input type="text" class="form-control" id="domicilio" name="domicilio" value="<?php echo $domicilio;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Provincia');?>:</label>
								<input type="text" class="form-control" id="domicilio_provincia" name="domicilio_provincia" value="<?php echo $domicilio_provincia;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('Localidad');?>:</label>
								<input type="text" class="form-control" id="domicilio_localidad" name="domicilio_localidad" value="<?php echo $domicilio_localidad;?>">
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="control-group form-group">
							<div class="controls">
								<label><?php echo _('C&#243;digo Postal');?>:</label>
								<input type="text" class="form-control" id="domicilio_cp" name="domicilio_cp" value="<?php echo $domicilio_cp;?>">
							</div>
						</div>
					</div>
					<div id="success"></div>
					<!-- For success/fail messages -->
					<div class="col-lg-12 lineaTop">
                    	<span class="bases"><input type="checkbox" id="bases" name="bases"><a data-fancybox-type="iframe" class="various" href="bases_condiciones.html"><?php echo _('Acepto las Bases y Condiciones');?></a>
							
						</span>
						<button type="button" onclick="sendData('DatosForm');" class="btnContinuar"><?php echo _('Continuar');?></button>
					</div>
				</form>
			</div>
      <div class="col-lg-2"></div>
		</div>
	</div>
  <!-- js placed at the end of the document so the pages load faster -->
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="validacion/js/formValidation.js"></script>
	<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
	<link rel="stylesheet" href="validacion/css/formValidation.css"/>
	<script src="assets/js/fancybox/jquery.fancybox.js"></script>
	<script>
	$(document).ready(function() {
		
		$('.various').fancybox({
			maxWidth	: 615,
			maxHeight	: 600,
			fitToView	: false,
			width		: '100%',
			height		: '90%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
		
		
		$('#DatosForm').formValidation({
			message: 'No es valido',
			fields: {
				razon_social: {
					message: '<?php echo 'Ingres&#225; la razon social';?>',
					validators: {
							notEmpty: {message: '<?php echo 'Ingres&#225; tu razon social'?>'}
					}
				},
				cuit: {
					message: '<?php echo 'Ingres&#225; el CUIT';?>',
					validators: {
							notEmpty: {message: '<?php echo 'Ingres&#225; tu CUIT'?>'}
					}
				},
				nombre: {
					message: '<?php echo 'Ingres&#225; el nombre';?>',
					validators: {
							notEmpty: {message: '<?php echo 'Ingres&#225; tu nombre'?>'}
					}
				},
				apellido: {
					message: '<?php echo 'Ingres&#225; el apellido'?>',
					validators: {
							notEmpty: {message: '<?php echo 'Ingres&#225; tu apellido'?>'}
					}
				},
				email: {
					message: '<?php echo 'Ingres&#225; el email'?>',
					validators: {
						notEmpty: {message: '<?php echo 'Ingres&#225; el email'?>'},
						emailAddress: {message: '<?php echo 'No es una cuenta de email valida'?>'}
					}
				},
				clave: {
					message: '<?php echo 'Indic&#225; la clave'?>',
					validators: {
							notEmpty: {message: '<?php echo 'Indic&#225; la clave'?>'}
					}
				},
				domicilio: {
					message: '<?php echo 'Indic&#225; el domicilio del supermercado'?>',
					validators: {
							notEmpty: {message: '<?php echo 'Indic&#225; el domicilio del supermercado'?>'}
					}
				},
				domicilio_provincia: {
					message: '<?php echo 'Indic&#225; la provincia del supermercado'?>',
					validators: {
							notEmpty: {message: '<?php echo 'Indic&#225; la provincia del supermercado'?>'}
					}
				},
				domicilio_localidad: {
					message: '<?php echo 'Indic&#225; la localidad del supermercado'?>',
					validators: {
							notEmpty: {message: '<?php echo 'Indic&#225; la localidad del supermercado'?>'}
					}
				},
				domicilio_cp: {
					message: '<?php echo 'Indic&#225; el codigo postal del supermercado'?>',
					validators: {
							notEmpty: {message: '<?php echo 'Indic&#225; el codigo postal del supermercado'?>'}
					}
				},
				bases: {
					message: '<?php echo 'Debes aceptar las Bases y Condiciones'?>',
					validators: {
							notEmpty: {message: '<?php echo 'Debes aceptar las Bases y Condiciones'?>'}
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
