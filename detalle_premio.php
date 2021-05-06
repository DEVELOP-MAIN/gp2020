<?php
header('Content-type: text/html; charset=utf-8');
require_once 'admin/php/class/class.premio.php';
require_once 'admin/php/class/class.canje.php';
require_once 'admin/php/class/class.socio.php';
require_once 'admin/php/class/class.phpmailer.php';
require_once 'php/seguridad.php';
require_once 'php/seguridad_email.php';

if(!isset($_SESSION)) {session_start();}
if(!isset($_REQUEST['idp']) || $_REQUEST['idp']=='') header('location:php/logout.php');

$pre = new premio();
if(!$pre->select($_REQUEST['idp'])) header('location:php/logout.php');
if($pre->estado!='P') header('location:home.php');
$stock_real = $pre->getStockReal($_REQUEST['idp']);
//si no hay stock -> lo bajo
if($stock_real == 0){
	$pre->cambiaPublicado($_REQUEST['idp'],'N');
	header('location:home.php');
}

$tipo = $pre->getTipo();

//chequeo que ademas el premio este en mi grupo (si soy distribuidor o vendedor)
if($_SESSION['QLMSF_rango']=='distribuidor' || $_SESSION['QLMSF_rango']=='vendedor' || $_SESSION['QLMSF_rango']=='repositor'){

	if($_SESSION['QLMSF_idgrupo']==4)
		$grupo = '2'; 
	else 
		$grupo = $_SESSION['QLMSF_idgrupo'];

	if(!$pre->estaEnGrupo($_REQUEST['idp'], $grupo)) 
		header('location:home.php');
}
$canje_realizado = false;
$mensaje = '';
$mensaje_recorda = '';

//gestion de canje (veo que no sea refresh)
if(isset($_POST['idp']) && is_numeric($_POST['idp'])){
	$cli = new socio();
	$canje_realizado = true;
	if($pre->select($_POST['idp'])){
		$saldo = $cli->actualizaSaldo($_SESSION['QLMSF_idcliente']);

		if($saldo >= $pre->getValor()){
			$stock = $pre->getStockReal($_POST['idp']);
			if($stock>0){
				$can = new canje();
				$tipopremio = $pre->getTipo();

				$can->setIdcliente($_SESSION['QLMSF_idcliente']);
				$can->setIdpremio($_POST['idp']);
				$can->setFecha(date('Y-m-d'));
				$can->setValor($pre->getValor());

				if($tipopremio=='fisico'){
					$lugar = $_POST['canje_direccion'].' - '.$_POST['canje_localidad'].' - '.$_POST['canje_provincia'];
					$can->setLugar_entrega($lugar);
					$can->setObservaciones('En el momento del canje el stock del producto era de: '.$stock );
					$mensaje = 'El canje fue realizado con &#233;xito, puedes verlo en <a href="mis_canjes.php">Mis Canjes</a>';

					//inserto el canje
					$can->setEstado('solicitado');
					$can->insert();
				} else {
					//inserto el canje
					$can->setEstado('efectivizado');
					$can->insert();
					
					//enviar el email de aviso
					sendAviso($pre->getNombre(), $pre->getDetalle());
										
					//sendEmailCodigo('digital',$pre->getNombre(), $pre->getDetalle());
					
					/*$codigo = $pre->dameCodigo($_POST['idp'], $_SESSION['QLMSF_idcliente']);
					if($codigo != ''){
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
					else $mensaje = 'Nos quedamos sin stock de c&#243;digos para este premio, el canje no fue ingresado';*/
					$mensaje = 'El canje fue realizado con &#233;xito, puedes verlo en <a href="mis_canjes.php">Mis Canjes</a>';
				}

				//recalcular el saldo en session
				$saldo = $cli->actualizaSaldo($_SESSION['QLMSF_idcliente']);
			}
			else $mensaje = 'Nos quedamos sin stock de este premio, el canje no fue ingresado';
		}
		else $mensaje = 'Detectamos que no tienes saldo suficiente para realizar este canje';
	}
	else $mensaje = 'Hubo un problema con la disponibilidad del premio';
}

function sendAviso($premio, $descripcion) {
	$cuerpo = "Se ha generado una nueva solicitud de canje, solicitada por: ".$_SESSION['QLMSF_apellido'].", de id:".$_SESSION['QLMSF_idcliente'].", solicita el premio:".$premio." (".$descripcion.")";
	$headers = 'From: noreply@gp2020.com.ar';
	$destino = DESTINO_AVISOS;	
	mail($destino,"nueva solicitud de canje",$cuerpo,$headers);
	return true;
}

function sendEmailCodigo($tipo, $premio, $descripcion){
	$pathfile = 'mails/codigo_premio.html';
	if($tipo=='mixto') $pathfile = 'mails/codigo_premio_mixto.html';
	
	$fh = fopen($pathfile, 'r');
	$cuerpo = file_get_contents($pathfile);
	$cuerpo = str_replace('<#NOMBRE#>', $_SESSION['QLMSF_nombre'], $cuerpo);
	//$cuerpo = str_replace('<#CODIGO#>', $codigo, $cuerpo);
	$cuerpo = str_replace('<#DESCRIPCION#>', $descripcion, $cuerpo);
	$cuerpo = str_replace('<#PREMIO#>', $premio, $cuerpo);

	$mail = new PHPMailer();

	$mail->isSMTP();
	$mail->Host     	= MAIL_HOST;
	$mail->SMTPAuth		= MAIL_SMTP_AUTH;
	$mail->Username		= MAIL_USERNAME;
	$mail->Password 	= MAIL_PASSWORD;
	$mail->SMTPSecure	= MAIL_SMTP_SECURE;
	$mail->Port     	= MAIL_PORT;

	$mail->SetFrom('mercadoidealq@gmail.com', 'Mercado Ideal');
	$mail->AddReplyTo('mercadoidealq@gmail.com', 'Mercado Ideal');
	$mail->AddAddress($_SESSION['QLMSF_email']);
	if($tipo=='mixto') $mail->AddAddress('info@mercadoideal.com.ar', 'Mercado Ideal');
	$mail->Subject = 'Tu código para Mercado Ideal';

	$mail->isHTML(true);
	$mail->Body = $cuerpo;
	$mail->MsgHTML($cuerpo);

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
			<div class="gp-franja-volver">
  			<div class="container text-right">
					<a href="#" onClick="javascript:history.back(-1);">&#x25c4; Volver</a>
				</div>
			</div>

			<section class="gp-detalle">
				<div class="container">
					<div class="row">
						<div class="col-lg-6">
							<div class="gp-foto">
								<?php
								if($pre->getImagen()!='')
									echo "<img src='archivos/".$pre->getImagen()."' width=\"100%\" alt='".$pre->getNombre()."'/>";
								else
									echo "<img src='archivos/no-foto.png' alt='".$pre->getNombre()."'/>";
								?>
							</div>
						</div>

						<div class="col-lg-6 gp-datos">
							<span class="gp-premio-cate"><?php echo $pre->getCategoria();?></span>
							<span class="gp-premio-tipo"><?php echo $pre->getTipo();?></span>
							<h1><?php echo $pre->getNombre();?></h1>
							<h2><?php echo $pre->getValor();?> millas</h2>
							<?php 
							//if($_SESSION['QLMSF_rango']=='distribuidor' || $_SESSION['QLMSF_rango']=='vendedor' || $_SESSION['QLMSF_rango']=='repositor'){
								if(!$canje_realizado){
									?>
									<a href="#" class="btn btn-primary gp-btn-canjear" data-toggle="modal" data-target="#gp-confirma">Canjear Premio</a>
									<?php
								} else {
									echo '<span class="btn btn-primary gp-btn-canjear">'.$mensaje.'</span>';
								}
							//}	
							?>
							<p>
								<?php 
								$vigencia_desde = $pre->getVigencia_desde();
								if($vigencia_desde!=''){ 
									$v = preg_split('/-/',$vigencia_desde);
									$vd = 'del '.$v[2].$v[1].$v[0];
								}
								else	$vd = '';
								$vigencia_hasta = $pre->getVigencia_hasta();
								if($vigencia_hasta!=''){ 
									$v = preg_split('/-/',$vigencia_hasta);
									$vh = 'al '.$v[2].$v[1].$v[0];
								}
								else	$vh = '';
								?>
								<strong>Vigencia: <?php echo $vd;?> <?php echo $vh;?></strong>
								<?php echo nl2br($pre->getDetalle());?>
							</p>
							<p>
								<strong>Sucursales</strong>
								<?php echo nl2br($pre->getSucursales());?>
							</p>
						</div>
					</div>
					<!-- /.row -->
				</div><!-- /.container -->
			</section>
		</main>
		<!--Modal Confirmar-->
		<form name="frm_canje" id="frm_canje" method="POST" action="detalle_premio.php">
			<input type="hidden" name="idp" value="<?php echo $pre->getIdpremio();?>">
			<div class="modal fade" id="gp-confirma" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel">Confirmar Canje</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<?php if($tipo=='fisico'){?>
						<div class="modal-body text-center" id="fisico">
							<p><strong>¡Ya casi está!</strong>
								El premio te será entregado mediante la red logística de Peñaflor.</p>
							<a href="#" onclick="confirmarCanje();" class="btn btn-primary">Confirmar</a>
							<a href="#" class="btn btn-primary" data-dismiss="modal">Cancelar</a>
						</div>
						<?php } else {?>
						<div class="modal-body text-center" id="giftCard">
							<p>Se enviará la Gift-Card a tu casilla de email <strong><?php echo $_SESSION['QLMSF_email'];?></strong>. 
								Si esta no es tu casilla podés cambiarla desde "<a href="datos_personales.php">Datos Personales</a>".</p>
							<a href="#" onclick="confirmarCanje();" class="btn btn-primary">Confirmar</a>
							<a href="#" class="btn btn-primary" data-dismiss="modal">Cancelar</a>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
		</form>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
		<script src="assets/dist/js/bootstrap.bundle.js"></script>

		<?php include_once('pie.php');?>

		<script>

		var pressed = false;

		function confirmarCanje() {
			if(!pressed) {
				document.getElementById('frm_canje').submit();
				pressed = true;
			}
				
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
