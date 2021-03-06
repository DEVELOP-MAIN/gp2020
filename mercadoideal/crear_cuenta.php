<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.login.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/class/class.phpmailer.php';

if(!isset($_SESSION)) {session_start();}
$mensaje = '';
$paso = '1';

//verifico que me pasen las variables
if(isset($_POST['paso']) && $_POST['paso']==1 && isset($_POST['cliente']) && isset($_POST['unico'])) {	
	$cli = new cliente();
	$resultado = $cli->chequeoAltaPaso1($_POST['cliente'], $_POST['unico']);
	
	//echo $resultado;
	
	if($resultado == 1) $paso = '2'; //todo ok
	if($resultado == 2) $paso = '1A'; //no existe esa combinacion
	if($resultado == 3) $paso = '1B'; //ya existe y tiene usuario, debe recuperar la clave	
}

//verifico que me pasen las variables
if(isset($_POST['paso']) && $_POST['paso']==2 && isset($_POST['email']) && isset($_POST['clave'])) {	
	$cli = new cliente();
	$resultado = $cli->chequeoAltaPaso2($_POST['email'], $_POST['clave']);
	if($resultado == 2) $paso = '2A'; //el email ya existe en el programa	
	if($resultado == 1) {
		$paso = '3'; //todo ok, ya actualizó
		envioEmailActivacionCuenta($_POST['email']);
	}		
}

function envioEmailActivacionCuenta($email) 
{
	$pathfile = 'mails/activacion.html';
	$nombre = $_SESSION['QLMS_PA_NOMBRE'].' '.$_SESSION['QLMS_PA_APELLIDO'];
	$url = URLABSOLUTA.'/activacion.php?code='.base64_encode($_SESSION['QLMS_PA_IDCLIENTE']);
	
	$fh = fopen($pathfile, 'r');		
	$cuerpo = file_get_contents($pathfile);	
	$cuerpo = str_replace('<#NOMBRE#>', $nombre, $cuerpo);	
	$cuerpo = str_replace('<#URL#>', $url, $cuerpo);	
	
	$mail = new PHPMailer();
	
	$mail->isSMTP(); 
	$mail->Host     = MAIL_HOST; 
	$mail->Port     = MAIL_PORT;
	$mail->SMTPSecure = MAIL_SMTP_SECURE;
	$mail->SMTPAuth	= MAIL_SMTP_AUTH;
	$mail->Username	= MAIL_USERNAME;
	$mail->Password = MAIL_PASSWORD;
	
	
			
	$mail->AddReplyTo('mercadoidealq@gmail.com', 'Mercado Ideal');
	$mail->SetFrom('mercadoidealq@gmail.com', 'Mercado Ideal');
	$mail->AddAddress($email);		
	$mail->Subject = 'Activa tu cuenta de Mercado Ideal';
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
	<link href="fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<!-- Custom CSS -->
	<link href="fonts/fonts-esp.css?nocache=0706" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=0706" rel="stylesheet">
	<!-- Custom JS -->
	<script src="js/jquery.js"></script>	
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
		<?php if($paso == '1' || $paso=='1A' || $paso=='1B') {?>
		<!--PASO 1-->
		<div class="row" style="display:block">
			<div class="col-lg-4"></div>
			<div class="col-lg-4 lgrecCrear">
				<h2><?php echo _('Crear cuenta');?></h2>
        <?php 
				if($paso=='1A') echo '<h4 style="color:#FFCC18">'._('No encontramos un usuario con los datos proporcionados. Por favor, verifique').'</h4>';
				if($paso=='1B') echo '<h4 style="color:#FFCC18">'._('Ya existe un usuario con dicha combinacion, ingrese con su email y clave').'</h4>';
				if($paso!='1A' && $paso!='1B') echo '<h4>'._('Ingrese los siguientes datos para adherirse al programa').'</h4>';					
				?>
				<form name="NuevaCuentaForm" id="NuevaCuentaForm" method="POST" action="crear_cuenta.php" novalidate>
					<input type="hidden" name="paso" id="paso" value="1">
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('C&oacute;digo de cliente');?>:</label>
							<div id="ayuda1" class="ayuda" style="display:none">								
								<?php echo _('Este es tu código de cliente con la empresa. Chequea en tu factura y lo podrás encontrar, sino revísalo con tu vendedor.');?>
							</div>
							<input type="text" value="<?php if(isset($_POST['cliente'])) echo $_POST['cliente']?>" class="form-control" name="cliente" id="cliente">
							<a href="#" onmouseout="$('#ayuda1').hide();" onmouseover="$('#ayuda1').show();"><span class="glyphicon glyphicon-info-sign" ></span></a>
						</div>
					</div>
					<!--div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('CUIT (sin guiones ni espacios)');?>:</label>
							<div id="ayuda2" class="ayuda" style="display:none">
								<?php echo _('Tu CUIT (Clave unica tributaria) ingresa solo los n&uacute;meros, sin espacios ni guiones.');?>
							</div>
							<input type="text" value="<?php if(isset($_POST['cuit'])) echo $_POST['cuit']?>" class="form-control" name="cuit" id="cuit">
							<a href="#"onmouseout="$('#ayuda2').hide();" onmouseover="$('#ayuda2').show();"><span class="glyphicon glyphicon-info-sign" ></span></a>
						</div>
					</div-->
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('C&oacute;digo &uacute;nico');?>:</label>
							<div id="ayuda3" class="ayuda" style="display:none">								
								<?php echo _('Este es el código único que te entregó el vendedor cuanto te adheriste. Si no lo tenes a mano, nos podes consular por WeChat.');?>

							</div>
							<input type="text" value="<?php if(isset($_POST['unico'])) echo $_POST['unico']?>" class="form-control" name="unico" id="unico">
							<a href="#" onmouseout="$('#ayuda3').hide();" onmouseover="$('#ayuda3').show();"><span class="glyphicon glyphicon-info-sign" ></span></a>
						</div>
					</div>
					<div id="success"></div>
					<!-- For success/fail messages -->
					<button type="button" onclick="sendData('NuevaCuentaForm');" class="btnSiguiente"><?php echo _('Continuar');?></button>
				</form>
      </div>
      <div class="col-lg-4"></div>
		</div>
		<?php } ?>
		
		<?php if($paso=='2'  || $paso=='2A') {?>		
		<!--PASO 2-->			
		<div class="row" style="display:block">
			<div class="col-lg-4"></div>
      <div class="col-lg-4 lgrecCrear">
				<h2>Crear cuenta</h2>
				<?php 
				if($paso=='2A')
					echo '<h4>'._('Ya existe un usuario con este email en el programa').'</h4>';
				else
					echo '<h4>'._('Ingrese su email y genere una clave para su cuenta en el programa').'</h4>';
				?>	
				<h4><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
				<?php echo _('Es importante que ingrese el email que utiliza dado que le llegar&aacute; comunicaci&oacute;n importante y algunos premios. Necesitar&aacute; recordar su email para poder ingresar.');?></h4>
				<form name="NuevaCuentaForm2" id="NuevaCuentaForm2" method="POST" action="crear_cuenta.php" novalidate>	
					<input type="hidden" name="paso" id="paso" value="2">
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('Email');?>:</label>
							<input type="text" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" class="form-control" id="email" name="email">
						</div>
					</div>
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('Clave');?>:</label>
							<input type="password" class="form-control" id="clave" name="clave">
						</div>
					</div>
					<div class="control-group form-group">
						<div class="controls">
							<label><?php echo _('Reingrese la Clave');?>:</label>
              <input type="password" class="form-control" id="reclave" name="reclave">
            </div>
          </div>
          <div id="success"></div>
          <!-- For success/fail messages -->
          <button type="button" onclick="sendData('NuevaCuentaForm2');" class="btnSiguiente"><?php echo _('Continuar');?></button>
        </form>
      </div>
      <div class="col-lg-4"></div>
		</div>
		<?php } ?>
		
		<?php if($paso=='3') {?>		
		<!--PASO 3-->
		<div class="row" style="display:block">
			<div class="col-lg-4"></div>
      <div class="col-lg-4 lgrecCrear">
				<h2><?php echo _('Crear cuenta');?></h2>
				<h3><?php echo _('Gracias por ingresar al programa.');?> 
				<br>
				<?php echo _('Para completar el proceso de registraci&oacute;n ingrese a su casilla de email y siga las instrucciones que le enviamos');?>.</h3>
      </div>
      <div class="col-lg-4"></div>
    </div> 
		<?php } ?>		
	</div>
	
	<!-- js placed at the end of the document so the pages load faster -->
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="validacion/js/formValidation.js"></script>
	<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
	<link rel="stylesheet" href="validacion/css/formValidation.css"/>
	<script>
	$(document).ready(function() {
		$('#NuevaCuentaForm').formValidation({
			message: 'No es valido',
			fields: {
				cliente: {
					message: '<?php echo _('Ingrese el codigo de cliente');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese el codigo de cliente');?>'}
					}
				},
				unico: {
					message: '<?php echo _('Ingrese el codigo unico que le fue entregado');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese el codigo unico que le fue entregado');?>'}
					}
				}
			}
		});
		$('#NuevaCuentaForm2').formValidation({
			message: 'No es valido',
			fields: {
				email: {
					message: '<?php echo _('Ingrese el email');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese el email');?>'},
						emailAddress: {message: '<?php echo _('No es una cuenta de email valida');?>'}
					}
				},
				clave: {
					message: '<?php echo _('Ingrese la clave seleccionada');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese la clave');?>'}
					}
				},
				reclave: {
					message: '<?php echo _('Ingrese nuevamente la clave seleccionada');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese la clave');?>'},
						identical: {field: 'clave', message: '<?php echo _('No coincide con la clave anteriormente ingresada');?>'}
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