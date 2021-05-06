<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.login.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/class/class.phpmailer.php';

if(!isset($_SESSION)) {session_start();}
$mensaje = '';

//verifico que me pasen las variables
if(isset($_POST['u']) && isset($_POST['p'])) 
{	
	//verifico el login del socio
	$log = new login();
	if($log->loggerFront($_POST['u'], $_POST['p'])) 
	{
		if($_SESSION['QLMSF_estado']=='C' || $_SESSION['QLMSF_estado']=='S') {
			if(isset($_SESSION['url_redirect']) && $_SESSION['url_redirect']!="") {
				header('location:'.$_SESSION['url_redirect']);
			} else {	
				header('location:home.php');
			}
		} else
			header('location:datos_cuenta.php?c='.base64_encode($_SESSION["QLMSF_idcliente"]));
	} 
	else $mensaje = _('Usuario y/o clave incorrectos');
}

//verifico que me pasen las variables
$enviado = false;
$vengoderecupero = false;
if(isset($_POST['email_recupero']) && $_POST['email_recupero']!='') {	
	$vengoderecupero = true;
	if(filter_var($_POST['email_recupero'], FILTER_VALIDATE_EMAIL)) {
		//verifico el login del cliente
		$clnt = new cliente();		
		if($clnt->selectXDni($_POST['email_recupero'])) 
		{
			//mandar el email con la clave al email del cliente
			$nombre = $clnt->getNombre();
			$email = $clnt->getEmail();
			$clave = $clnt->getClave();
			if($email != '') 
			{
				sendEmailClave($nombre, $email, $clave);
				$enviado = true;
			}
		}
	}	
}

function sendEmailClave($nombre, $email, $clave) 
{
	$pathfile = 'mails/recupero.html';
	$fh = fopen($pathfile, 'r');		
	$cuerpo = file_get_contents($pathfile);	
	$cuerpo = str_replace('<#NOMBRE#>', $nombre, $cuerpo);	
	$cuerpo = str_replace('<#CLAVE#>', $clave, $cuerpo);	
	
	$mail = new PHPMailer();
	
	$mail->isSMTP(); 
	$mail->Host     = MAIL_HOST; 
	$mail->SMTPAuth	= MAIL_SMTP_AUTH;
	$mail->Username	= MAIL_USERNAME;
	$mail->Password = MAIL_PASSWORD;
	$mail->SMTPSecure = MAIL_SMTP_SECURE;
	$mail->Port     = MAIL_PORT;
			
	$mail->SetFrom('mercadoideal@aper.net', 'Mercado Ideal');
	$mail->AddReplyTo('mercadoideal@aper.net', 'Mercado Ideal');	
	$mail->AddAddress($email);		
	$mail->Subject = 'Recuperacion de clave de acceso a Mercado Ideal';
	
	$mail->isHTML(true);   
	$mail->Body   = $cuerpo;
	$mail->MsgHTML($cuerpo);
	
	//echo $cuerpo;
	//exit;
	$mail->send();
	
	return true;
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
	<!-- Custom CSS -->
	<link href="fonts/fonts-esp.css?nocache=0706" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=0706" rel="stylesheet">
	<!-- Custom JS -->
	<script src="js/jquery.js"></script>
</head>

<body style="background: url(images/fd_login.png) #FFF; padding:40px 0 0 0">
	<!-- Page Content -->
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
		<div class="row">
			<div class="col-lg-4"></div>
			<div class="col-lg-4 lgrecLogin">
				<?php 
				if(isset($_GET["a"]) && $_GET["a"]) echo "<h2 style='color:GREEN'>"._('Tu cuenta ha sido activada')."</h2>";
				?>
				<h2><?php echo _('Ingresar');?></h2>
				<form name="sentMessage" id="loginForm" method="POST" action="index.php" novalidate>
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('Email');?>:</label>
							<input type="email" class="form-control" name="u" id="u">
							<p class="help-block"></p>
						</div>
					</div>
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('Clave');?>:</label>
							<input type="password" class="form-control" name="p" id="p">
						</div>
					</div>
					<div id="success"></div>
					<!-- For success/fail messages -->
					<button type="button" onclick="sendData('loginForm');" class="btnLoguin"><?php echo _('Entrar')?></button>
        </form>
        <?php if($mensaje!='') {
				echo '<div class="lgError">'.$mensaje.'</div>';
				}?>
				<div class="lgClave"><a href="#" onclick="$('#recupero').show();">
				<?php 
				if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') { 
					echo '忘记密码';
				} else {
					echo '&iquest;Olvidaste tu clave?';
				}				
				?>
				</a></div>
					
				<?php if($vengoderecupero && $enviado) echo '<label>'._('El email fue enviado, revisa tu casilla').'</label>';?>
				<?php if($vengoderecupero && !$enviado) echo '<label>'._('Este email no corresponde con un usuario').'</label>';?>					
				<form name="recupero" id="recupero" method="POST" action="index.php" novalidate <?php if(!$vengoderecupero || $enviado) echo 'style="display:none;"';?>>
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('Ingresa tu e-mail para obtener tu clave');?>:</label>
							<input type="email" class="form-control" name="email_recupero" id="email_recupero">
							<p class="help-block"></p>
						</div>
					</div>
					<button type="button" onclick="sendData('recupero');" class="btnLoguin"><?php echo _('Obtener clave');?></button>
				</form>						
      </div>
			<div class="col-lg-4"></div>
		</div>
    <div class="row">
			<div class="col-lg-4"></div>
			<div class="col-lg-4 lgrecRegistro">
				<?php echo _('&iquest;A&uacute;n no est&aacute;s participando del programa?')?>
				<button type="button" onclick="javascript:document.location='crear_cuenta.php'" class="btnReg"><?php echo _('Crear una cuenta');?></button>
			</div>
			<div class="col-lg-4"></div>
    </div>
  </div>
   
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="validacion/js/formValidation.js"></script>
	<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
	<link rel="stylesheet" href="validacion/css/formValidation.css"/>
	<script>
	$(document).ready(function() {
		$('#loginForm').formValidation({
			message: 'No es valido',
			fields: {
				u: {
					message: '<?php echo _('Ingres&#225; el email')?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingres&#225; el email')?>'},
						emailAddress: {message: '<?php echo _('No es una cuenta de email valida')?>'}
					}
				},
				p: {
					message: '<?php echo _('Indic&#225; la clave')?>',
					validators: {
							notEmpty: {message: '<?php echo _('Indic&#225; la clave')?>'}
					}
				}
			}
		});
		$('#recupero').formValidation({
			message: 'No es valido',
			fields: {
				email_recupero: {
					message: '<?php echo _('Ingresa tu e-mail para obtener tu clave')?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingresa tu e-mail para obtener tu clave')?>'},
						emailAddress: {message: '<?php echo _('Ingresa tu e-mail para obtener tu clave')?>'}
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
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-97492828-1', 'auto');
	  ga('send', 'pageview');
	</script>
</body>
</html>
