<?php
require_once 'admin/php/class/class.login.php';
require_once 'admin/php/class/class.socio.php';
require_once 'admin/php/class/class.phpmailer.php';

if(!isset($_SESSION)) {session_start();}
$mensaje = '';

//verifico que me pasen las variables
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(isset($_POST['u']) && isset($_POST['p'])){
		//verifico el login del socio
		$log = new login();
		if($log->loggerFront($_POST['u'], $_POST['p'])){
			header('location:home.php');
		} 
		else $mensaje = 'Codigo y/o clave incorrectos';
	}
} else {
	if(isset($_REQUEST['sc']) && $_REQUEST['sc']!=''){
		//verifico el login del administrador como este socio
		$log = new login();
		if($log->loggerFrontAdmin($_REQUEST['sc'])){
			header('location:home.php');
		} 
		else $mensaje = 'Clave no encontrada';
	}
}

//verifico que me pasen las variables
$enviado = false;
$vengoderecupero = false;
if(isset($_POST['email_recupero']) && $_POST['email_recupero']!='') {	
	$vengoderecupero = true;
	if(filter_var($_POST['email_recupero'], FILTER_VALIDATE_EMAIL)) {
		//verifico el login del socio
		$clnt = new socio();
		
		if($clnt->select_X_email($_POST['email_recupero']))	{
			//mandar el email con la clave al email del socio
			$nombre = $clnt->getNombre();
			$email = $clnt->getEmail();
			$clave = $clnt->getClave();
			if($email != ''){
				sendEmailClave($nombre, $email, $clave);
				$enviado = true;
			}
		}
	}	
}

function sendEmailClave($nombre, $email, $clave){
	$pathfile = 'mails/recupero.html';
	$fh = fopen($pathfile, 'r');		
	$cuerpo = file_get_contents($pathfile);	
	$cuerpo = str_replace('<#NOMBRE#>', $nombre, $cuerpo);	
	$cuerpo = str_replace('<#CLAVE#>', $clave, $cuerpo);	
	
	$mail = new PHPMailer();
	
	$mail->isSMTP(); 
	$mail->Host     	= MAIL_HOST; 
	$mail->SMTPAuth		= MAIL_SMTP_AUTH;
	$mail->Username		= MAIL_USERNAME;
	$mail->Password 	= MAIL_PASSWORD;
	$mail->SMTPSecure = MAIL_SMTP_SECURE;
	$mail->Port     	= MAIL_PORT;

	$mail->SetFrom('mercadoideal@aper.net', 'GP2020');
	$mail->AddReplyTo('mercadoideal@aper.net', 'GP2020');	
	$mail->AddAddress($email);		
	$mail->Subject = 'Recuperacion de clave de acceso a GP2020';

	$mail->isHTML(true);   
	$mail->Body   = $cuerpo;
	$mail->MsgHTML($cuerpo);

	//echo $cuerpo;
	//exit;
	$mail->send();
	
	return true;
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
	<body style="background:#e30613">
	  <!-- Page Content -->
    <div class="container">
			<div class="row">
				<div class="col-lg-4"></div>
        <div class="col-lg-4">
					<div class="lgLogo">
						<img src="img/logo_gp2020.svg" alt="GP2020 Peñaflor" width="140" />
          </div>
					<div class="lgrecLogin">
        		<h2>Ingresar</h2>
            <form name="sentMessage" id="loginForm" method="POST" action="index.php" novalidate>
							<div class="control-group form-group">
								<div class="controls">
									<label>C&#243;digo:</label>
                  <input type="text" class="form-control" name="u" id="u" required data-validation-required-message="Por favor ingrese su codigod de usuario.">
                  <p class="help-block"></p>
                </div>
              </div>
              <div class="control-group form-group">
								<div class="controls">
									<label>Clave:</label>
                  <input type="password" class="form-control" name="p" id="p" required data-validation-required-message="Por favor ingrese su clave de acceso.">
                </div>
              </div>
             	<?php if($mensaje!='') {echo '<div id="success">'.$mensaje.'</div>';}?>
							<!-- For success/fail messages -->
              <button type="button" onclick="sendData('loginForm');" class="gp-btn-rojo-borde btn-login">Entrar</button>
						</form>
          </div>
	  <div class="lgClave"><a style="cursor: pointer;" onclick="$('#recupero').show(); window.scrollTo(0,document.body.scrollHeight);">&iquest;Olvidaste tu clave?</a></div>
	  
		<hr>
	  	<?php if($vengoderecupero && $enviado){ echo '<label style="color: white">'.('El email fue enviado, revisa tu casilla').'</label>'; } ?>
		<?php if($vengoderecupero && !$enviado){ echo '<label style="color: white">'.('Este email no corresponde con un usuario').'</label>'; }?>					
		<form name="recupero" id="recupero" method="POST" action="index.php" novalidate <?php if(!$vengoderecupero || $enviado) echo 'style="display:none;"';?>>
			<div class="control-group form-group">
				<div class="controls">
					<label style="color: white;">Ingresa tu e-mail para obtener tu clave:</label>
					<input type="email" class="form-control" name="email_recupero" id="email_recupero">
					<p class="help-block"></p>
				</div>
			</div>
			<button type="button" onclick="sendData('recupero');" class="btnLoguin">Obtener clave</button>
			<br><br>
		</form>							
        </div>
        <div class="col-lg-4"></div>
			</div>
      <!--div class="row">
				<div class="col-lg-4"></div>
				<div class="col-lg-4 lgrecRegistro">
					&iquest;A&uacute;n no est&aacute;s participando del programa?
					<button type="button" onclick="javascript:document.location='crear_cuenta.html'" class="btnReg">Crear una cuenta</button>
				</div>
				<div class="col-lg-4"></div>
			</div-->
		</div>

		<!-- js placed at the end of the document so the pages load faster -->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.js"></script>
		<script type="text/javascript" src="validacion/js/formValidation.js"></script>
		<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
		<link rel="stylesheet" href="validacion/css/formValidation.css"/>
		<script>
		$(document).ready(function() {
			$('#loginForm').formValidation({
				message: 'No es valido',
				fields: {
					u: {
						message: '<?php echo 'Ingrese su codigo de usuario'?>',
						validators: {
							notEmpty: {message: '<?php echo 'Ingrese su codigo de usuario'?>'}
						}
					},
					p: {
						message: '<?php echo 'Ingrese su clave de acceso'?>',
						validators: {
							notEmpty: {message: '<?php echo 'Ingrese su clave de acceso'?>'}
						}
					}
				}
			});
			$('#recupero').formValidation({
				message: 'No es valido',
				fields: {
					email_recupero: {
						message: '<?php echo 'Ingrese su e-mail para obtener tu clave'?>',
						validators: {
							notEmpty: {message: '<?php echo 'Ingrese su e-mail para obtener tu clave'?>'},
							emailAddress: {message: '<?php echo 'Ingrese su e-mail para obtener tu clave'?>'}
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
