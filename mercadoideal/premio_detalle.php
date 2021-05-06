<?php
header('Content-type: text/html; charset=utf-8');
require_once 'traduccion.php';
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/class/class.premio.php';
require_once 'admin/php/class/class.canje.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/class/class.phpmailer.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
if(!isset($_REQUEST['idp']) || $_REQUEST['idp']=='') header('location:logout.php');

$pre = new premio();
if(!$pre->select($_REQUEST['idp'])) header('location:logout.php');
if($pre->estado!='P') header('location:home.php');
$stock_real = $pre->getStockReal($_REQUEST['idp']);

//lo bajo
if($stock_real == 0)
{
	$pre->cambiaPublicado($_REQUEST['idp'],'N');
	header('location:home.php');
}

//chequeo que ademas el premio este en mi grupo
if(!$pre->estaEnGrupo($_REQUEST['idp'], $_SESSION['QLMSF_idgrupo'])) header('location:home.php');

$canje_realizado = false;
$mensaje = '';
$mensaje_recorda = '';

//gestion de canje (veo que no sea refresh)
if(isset($_POST['idp']) && is_numeric($_POST['idp']))
{
	//si el premio es de tipo1
	$pre = new premio();
	$cli = new cliente();
	$canje_realizado = true;
	if($pre->select($_POST['idp']))
	{
		$saldo = $cli->actualizaSaldo($_SESSION['QLMSF_idcliente']);
		
		if($saldo >= $pre->getValor())
		{
			$stock = $pre->getStockReal($_POST['idp']);
			if($stock>0)
			{
				$can = new canje();
				$tipopremio = $pre->getTipo();

				$can->setIdcliente($_SESSION['QLMSF_idcliente']);
				$can->setIdpremio($_POST['idp']);
				$can->setFecha(date('Y-m-d'));
				$can->setValor($pre->getValor());

				if($tipopremio=='fisico')
				{
					$lugar = $_POST['canje_direccion'].' - '.$_POST['canje_localidad'].' - '.$_POST['canje_provincia'].' - '.$_POST['canje_cp'];
					$can->setLugar_entrega($lugar);
					$can->setObservaciones('En el momento del canje el stock del producto era de: '.$stock );
					$mensaje = _('El canje fue realizado con &eacute;xito, puedes verlo en la secci&oacute;n <a href="canjes_realizados.php"> Canjes Realizados </a>');

					//inserto el canje
					$can->setEstado('solicitado');
					$can->insert();
				}

				if($tipopremio=='digital')
				{
					$codigo = $pre->dameCodigo($_POST['idp'], $_SESSION['QLMSF_idcliente']);
					if($codigo != '')
					{
						//actualizo el dni del socio
						$cli->actualizaDNI($_POST['canje_dni'], $_SESSION['QLMSF_idcliente']);
						
						//TEMA DEL Codigo
						$can->setLugar_entrega('ingreso el DNI: '.$_POST['canje_dni']);
						$can->setObservaciones($codigo);
						$mensaje = _('El canje fue realizado con &eacute;xito, te enviamos un email con el voucher del premio.');
						$mensaje_recorda = _('Record&aacute; que el canje se activa a las 72hs');

						//inserto el canje
						$can->setEstado('efectivizado');
						$can->insert();
						//enviar el email de confirmacion (si es codigo)						
						sendEmailCodigo('digital',$pre->getNombre(), $codigo, $pre->getDetalle());
					}
					else $mensaje = _('Nos quedamos sin stock de c&oacute;digos para este premio, el canje no fue ingresado');
				}
				
				if($tipopremio=='mixto')
				{
					$codigo = $pre->dameCodigo($_POST['idp'], $_SESSION['QLMSF_idcliente']);
					if($codigo != '')
					{
						$lugar = $_POST['canje_direccion'].' - '.$_POST['canje_localidad'].' - '.$_POST['canje_provincia'].' - '.$_POST['canje_cp'];
						$can->setLugar_entrega($lugar);
						$can->setObservaciones($codigo);
						$mensaje = _('El canje fue realizado con &eacute;xito, puedes verlo en <a href="canjes_realizados.php">Canjes Realizados</a>. <br />Adem&#225;s te enviamos un email con el voucher del premio.');

						//inserto el canje
						$can->setEstado('solicitado');
						$can->insert();
						//enviar el email de confirmacion (si es codigo)
						
						//para el caso de mixto mando direccion de imprenta
						$detalle_mixto = "";
						//$detalle_mixto = "<strong>Para hacer tu pedido, contactate con: | 要兑换奖品，请向印刷厂联系: Mail| 电邮: dongfangimprenta@gmail.com, Teléfono| 电话： 47819688 o QQ：2261849474 </strong><BR>" . $pre->getDetalle();						
						
						$detalle_mixto = $pre->getDetalle()."<BR><BR>";
						$detalle_mixto.= "Para hacer tu pedido, contactate con: / 要兑换奖品，请向印刷厂联系:<BR>";
						$detalle_mixto.= "a. Mail / 电邮: dongfangimprenta@gmail.com<BR>";
						$detalle_mixto.= "b. Contacto / 联系 1- QQ: 2261849474 / Teléfono / 电话:47819688<BR>";
						$detalle_mixto.= "c. Contacto /联系 2- QQ: 1398978137 / Teléfono / 电话:47800778<BR>";
						
						sendEmailCodigo('mixto',$pre->getNombre(), $codigo, $detalle_mixto);
					}
					else $mensaje = _('Nos quedamos sin stock de c&oacute;digos para este premio, el canje no fue ingresado');
				}

				if($tipopremio=='chances')
				{
					//TEMA DEL CHANCES
					$chances = $pre->getChances();
					$can->setLugar_entrega('');
					$can->setObservaciones('Suma de chances para el sorteo: '.$chances.' chances.');
					$mensaje = _('El canje fue realizado con &eacute;xito, ¡ya sumaste las chances para el sorteo!.');

					//inserto el canje
					$can->setEstado('efectivizado');
					$can->insert();
				}

				//recalcular el saldo en session
				$saldo = $cli->actualizaSaldo($_SESSION['QLMSF_idcliente']);
			}
			else $mensaje = _('Nos quedamos sin stock de este premio, el canje no fue ingresado');
		}
		else $mensaje = _('Detectamos que no tienes saldo suficiente para realizar este canje');
	}
	else $mensaje = _('Hubo un problema con la disponibilidad del premio');
}

function sendEmailCodigo($tipo, $premio, $codigo, $descripcion)
{
	$pathfile = 'mails/codigo_premio.html';
	if($tipo=='mixto') $pathfile = 'mails/codigo_premio_mixto.html';
	
	$fh = fopen($pathfile, 'r');
	$cuerpo = file_get_contents($pathfile);
	$cuerpo = str_replace('<#NOMBRE#>', $_SESSION['QLMSF_nombre'], $cuerpo);
	$cuerpo = str_replace('<#CODIGO#>', $codigo, $cuerpo);
	$cuerpo = str_replace('<#DESCRIPCION#>', $descripcion, $cuerpo);
	$cuerpo = str_replace('<#PREMIO#>', $premio, $cuerpo);

	$mail = new PHPMailer();

	$mail->isSMTP();
	$mail->Host     				= MAIL_HOST;
	$mail->SMTPAuth			= MAIL_SMTP_AUTH;
	$mail->Username		= MAIL_USERNAME;
	$mail->Password 		= MAIL_PASSWORD;
	$mail->SMTPSecure	= MAIL_SMTP_SECURE;
	$mail->Port     					= MAIL_PORT;

	$mail->SetFrom('mercadoidealq@gmail.com', 'Mercado Ideal');
	$mail->AddReplyTo('mercadoidealq@gmail.com', 'Mercado Ideal');
	$mail->AddAddress($_SESSION['QLMSF_email']);
	if($tipo=='mixto') $mail->AddAddress('info@mercadoideal.com.ar', 'Mercado Ideal');
	$mail->Subject = 'Tu código para Mercado Ideal';

	$mail->isHTML(true);
	$mail->Body   = $cuerpo;
	$mail->MsgHTML($cuerpo);

	$mail->send();

	return true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Mercado Ideal | Quilmes - Brahma</title>
	<link rel="shortcut icon" href="https://www.mercadoideal.com.ar/favicon.ico?nocache=0706" />

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		
  <!-- Custom CSS -->
	<link href="fonts/fonts-esp.css?nocache=0706" rel="stylesheet" type="text/css">
  <link href="css/mercado-ideal.css?nocache=0706" rel="stylesheet">
	<link href="assets/js/fancybox/jquery.fancybox.css" rel="stylesheet" />	

	<!-- jQuery -->
  <script src="js/jquery.js"></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php include_once('cabecera.php')?>
    <!-- Contenido -->
		<div class="container rb-detpremio">
			<div class="row">
				<div class="col-lg-12">
					<a href="#" onclick="history.back();" class="btn-volver"><i class="fa fa-arrow-left" aria-hidden="true"></i> <?php echo _('Volver');?></a>
				</div>
			</div>
            
        <!--Detalle premio-->
		<div class="row">
      <div class="detalle-premio">
				<div class="col-lg-6 col-sm-6 foto">
					<?php
					if($pre->getImagen()!="")
						echo "<img src='archivos/".$pre->getImagen()."' alt='".$pre->getNombre()."'/>";
					else
						echo "<img src='archivos/no-foto.png' alt='".$pre->getNombre()."'/>";
					?>
				</div>

				<?php if(!$canje_realizado) { ?>
				<form name="frm_canje" id="frm_canje" method="POST" action="premio_detalle.php">
					<input type="hidden" name="idp" value="<?php echo $pre->getIdpremio();?>">
					<input type="hidden" name="tipo" value="<?php echo $pre->getTipo();?>">
					<div id="canje_div_datos"  class="col-lg-6 col-sm-6 texto" style="display:block">
						<h3>
						<?php
						if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
						{	
							if($pre->getNombre_ch()!='') 
								echo  $pre->getNombre_ch(); 
							else 
								echo  $pre->getNombre();
						}
						else
							echo $pre->getNombre();
						?>
						</h3>
						<h4><?php echo $pre->getNombreCampania($_REQUEST['idp']);?></h4>
						<h1><?php echo $pre->getValor()?><span>pts.</span></h1>
						<?php 												
						if($_SESSION['QLMSF_estado']!="S") {
							if($pre->getStockReal($_REQUEST['idp'])>0) 
							{ 
								if($_SESSION['QLMSF_puntos'] >= $pre->getValor()) 
								{ 
								?>
									<a href="#" onclick="confirmarCanje('<?php echo $pre->getTipo();?>');" class="btn-canjear"><?php echo _('CANJEAR PREMIO');?></a>
								<?php 
								} 
								else 
								{
									$resto = $pre->getValor()-$_SESSION['QLMSF_puntos'];
									echo '<h4 class="confirma">'._('Aun te faltan').' '.$resto.' '._('puntos para poder acceder a este premio').'</h4>';
								}
							} else {
								echo '<h4 class="confirma">'._('Moment&aacute;neamente estamos sin stock para este premio').'</h4>';
							}
						} else {
							echo '<h4 class="confirma">'._('Moment&aacute;neamente tu usuario no tiene permitido realizar m&aacute;s canjes, por favor comun&iacute;cate con nosotros a trav&eacute;s de WeChat/WhatsApp para saber m&aacute;s.').'</h4>';
						}	
						?>
					</div>

					<div id="canje_div_entrega" class="col-lg-6 col-sm-6 texto" style="display:none">
						<h3><?php
							if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=="zh_CN")
								if( $pre->getNombre_ch()!="") echo  $pre->getNombre_ch(); else echo  $pre->getNombre();
							else
								echo $pre->getNombre();
						?></h3>
						<h4><?php echo $pre->getNombreCampania($_REQUEST['idp']);?> / <?php echo $pre->getValor()?><span>pts.</span></h4>
						<h4 class="confirma"><?php echo _('Le solicitamos que confirme la dirección de entrega');?></h4>

						<div class="control-group form-group">
							<div class="col-sm-12">
								<label><?php echo _('Dirección:');?></label>
								<input type="text" value="<?php echo $_SESSION['QLMSF_domicilio']?>" class="form-control" name="canje_direccion" id="canje_direccion" required data-validation-required-message="Ingresa tu email.">
								<p class="help-block"></p>
							</div>
						</div>
						<div class="control-group form-group">
							<div class="col-sm-6">
								<label><?php echo _('Provincia:');?></label>
								<input type="text" value="<?php echo $_SESSION['QLMSF_provincia']?>" class="form-control" name="canje_provincia" id="canje_provincia"  required data-validation-required-message="Ingresa tu email.">
								<p class="help-block"></p>
							</div>
              <div class="col-sm-6">
								<label><?php echo _('Localidad:');?></label>
								<input type="text" value="<?php echo $_SESSION['QLMSF_localidad']?>" class="form-control" name="canje_localidad" id="canje_localidad" required data-validation-required-message="Ingresa tu email.">
								<p class="help-block"></p>
							</div>
              <div class="col-sm-6">
								<label><?php echo _('Codigo Postal:');?></label>
								<input type="text" value="<?php echo $_SESSION['QLMSF_cp']?>" class="form-control" name="canje_cp" id="canje_cp" required data-validation-required-message="Ingresa tu email.">
								<p class="help-block"></p>
							</div>
						</div>
						<div class="control-group form-group btn-confirma">
							<div class="col-sm-12">
								<a href="#" class="btn-canjear" onclick="confirmaDireccion();"><?php echo _('CONFIRMAR CANJE');?></a>
							</div>
						</div>
					</div>
					
					<div id="canje_div_dni" class="col-lg-6 col-sm-6 texto" style="display:none">
						<h3>
							<?php
							if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
								if( $pre->getNombre_ch()!='') echo  $pre->getNombre_ch(); else echo  $pre->getNombre();
							else
								echo $pre->getNombre();
							?>
						</h3>
						<h4><?php echo $pre->getNombreCampania($_REQUEST['idp']);?> / <?php echo $pre->getValor()?><span>pts.</span></h4>
						<h4 class="confirma"><?php echo _('Le solicitamos que ingrese o confirme su DNI');?></h4>

						<div class="control-group form-group">
							<div class="col-sm-12">
								<label><?php echo _('DNI:');?></label>
								<input type="text" value="<?php echo $_SESSION['QLMSF_dni']?>" class="form-control" name="canje_dni" id="canje_dni" required data-validation-required-message="Ingresa tu dni.">
								<p class="help-block"></p>
							</div>
						</div>
						<div class="control-group form-group btn-confirma">
							<div class="col-sm-12">
								<a href="#" class="btn-canjear" onclick="confirmaDNI();"><?php echo _('CONFIRMAR CANJE');?></a>
							</div>
						</div>
					</div>
				</form>
			<?php
			} else {
				echo "<div id='canje_div_datos'  class='col-lg-6 col-sm-6 texto'>";
				echo "<h3>".$pre->getNombre()."</h3>";
				echo "<h4>".$pre->getNombreCampania($_REQUEST['idp'])."</h4>";
				echo "<h4 class='confirma'>".$mensaje."</h4>";
				if($mensaje_recorda!="") echo "<h4>".$mensaje_recorda."</h4>";
				echo "</div>";
			}
			?>
			<div style="clear:both; width:100%; height:1px"></div>

       </div>
       </div>
       
       <!--Descripción premio-->
		<div class="row">
       <div class="descripcion-premio">
       <span class="vigencia"><?php echo _('Vigencia: del');?> <?php echo $pre->getVigencia_desde()?> <?php echo _('al');?> <?php echo $pre->getVigencia_hasta()?></span>
			<?php
				if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN')
					if( $pre->getDetalle_ch()!="") echo  nl2br($pre->getDetalle_ch()); else echo  nl2br($pre->getDetalle());
				else
					echo nl2br($pre->getDetalle());
			?>
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

	<!-- Validacion -->
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="validacion/js/formValidation.js"></script>
	<script type="text/javascript" src="validacion/js/framework/bootstrap.js"></script>
	<link rel="stylesheet" href="validacion/css/formValidation.css"/>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script>
	function confirmarCanje(tipo) {
		if(tipo=='fisico' || tipo=='mixto') 
		{
			$('#canje_div_datos').hide();
			$('#canje_div_entrega').show();
		}
		else 
		{
			if(tipo=='digital') 
			{
				$('#canje_div_datos').hide();
				$('#canje_div_dni').show();
			} 
			else 
				document.getElementById('frm_canje').submit();
		}
	}

	function confirmaDireccion() {
		$('#frm_canje').formValidation('validate');
		if($('#frm_canje').data('formValidation').isValid()) {
			document.getElementById('frm_canje').submit();
		}
	}
	
	function confirmaDNI() {
		$('#frm_canje').formValidation('validate');
		if($('#frm_canje').data('formValidation').isValid()) {
			document.getElementById('frm_canje').submit();
		}
	}

	$(document).ready(function() {
		$('#frm_canje').formValidation({
			message: 'No es valido',
			fields: {
				canje_direccion: {
					message: '<?php echo _('Ingrese la dirección');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese la dirección');?>'}
					}
				},
				canje_provincia: {
					message: '<?php echo _('Ingrese la provincia');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese la provincia');?>'}
					}
				},
				canje_localidad: {
					message: '<?php echo _('Ingrese la localidad');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Ingrese la localidad');?>'}
					}
				},
				canje_cp: {
					message: '<?php echo _('Indique el codigo postal');?>',
					validators: {
						notEmpty: {message: '<?php echo _('Indique el codigo postal');?>'}
					}
				}
			}
		});
	});
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