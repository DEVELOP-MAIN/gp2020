<?php
//prueba fija
if(!isset($_SESSION)) {session_start();}
//$_SESSION['QLMSF_idioma'] = "zh_CN";

require_once 'traduccion.php';
require_once 'admin/php/class/class.mensaje.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';

if(!isset($_SESSION)) {session_start();}
if(isset($_REQUEST['ok'])) $ok = $_REQUEST['ok']; else  $ok = false;

//incio el objeto
$mnsj = new mensaje();

//Me fijo si vengo de POST
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$asunto 		= validaVars($_POST['asunto']);
	$mensaje	= validaVars($_POST['mensaje']);
	
	$mnsj->setMensaje('Asunto: '.$asunto.'<br />'.$mensaje);
	$mnsj->setEstado('no leido');
	$mnsj->setIdcliente($_SESSION['QLMSF_idcliente']);
	
	if($mnsj->insert()) header('location:contacto.php?ok=true');
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
	<link href="fonts/fonts-esp.css?nocache=0511" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=0511" rel="stylesheet">
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
				<div class="tt-Canjes"><h1><?php echo _('Contacto');?></h1></div>
				<div class="separa"></div>
			</div>
		<div class="mis-datos">
			<div class="col-lg-12 mi-contacto">
			
			<div class="col-sm-6 mi-nros">
				<h3><strong>Tel&eacute;fono:</strong> <a href="tel:5491127464507">+54 9 11 27464507</a></h3>
				<h3><strong>WhatsApp:</strong> <a href="tel:5491127464507">+54 9 11 27464507</a></h3>
				<h3><strong>WeChat ID:</strong> <a href="#">mercadoideal</a></h3>
			</div>	
			<div class="col-sm-6">
				<img src="images/qr-contacto.jpg" width="80%">
			</div>
			
				<!--div class="col-lg-12" style="clear:both; width:100%; margin-bottom:25px;">
					<span class="codigo"><?php echo _('Env&iacute;enos su consulta, un operador le respondera en breve')?>.</span>
				</div>
				<form id="ContactForm" action="contacto.php" method="POST" onSubmit="return false;">
					<div class="col-sm-6 col-lg-6 col-md-6">
						<ul class="campos">
						 <li class="control-group form-group controls"><input id="asunto" name="asunto" type="text" PlaceHolder="Asunto" class="form-control"></li>
						 <li class="control-group form-group controls"><textarea id="mensaje" name="mensaje" cols="" rows="5" PlaceHolder="Su Mensaje" class="form-control"></textarea></li>
						</ul>
						<a href="#" onclick="sendData('ContactForm');" class="formulario"><?php echo _('Enviar');?></a>
						<div style="clear:both; width:100%; height:1px"></div> 
					</div>
				</form>
				<div class="col-sm-6 col-lg-6 col-md-6">
					<div class="alerta-mod" style="display:<?php if($ok) echo 'block'; else echo 'none';?>;"><?php echo _('Su mensaje fue enviado correctamente');?></div>
					<div style="clear:both; width:100%; height:1px"></div> 
				</div-->
				
			</div>
			<div style="clear:both; width:100%; height:1px;"></div>    
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
		$('#ContactForm').formValidation({
			message: 'No es valido',
			fields: {
				mensaje: {
					message: '<?php echo _('Ingrese un mensaje');?>',
					validators: {
							notEmpty: {message: '<?php echo _('Ingrese un mensaje');?>'}
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
