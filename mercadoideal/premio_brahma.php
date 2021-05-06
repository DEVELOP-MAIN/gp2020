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
<link rel="shortcut icon" href="https://www.mercadoideal.com.ar/favicon.ico?nocache=0706?nocache=0706" />

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
    <?php include_once("cabecera.php")?>
    <!-- Contenido -->

    <div class="container">
        <div class="row">
        <div class="col-lg-12">
         <a href="#" onclick="history.back();" class="btn-volver"><i class="fa fa-arrow-left" aria-hidden="true"></i> <?php echo _('Volver');?></a>
		</div>
    </div>

      <div class="detalle-premio">
                <div class="col-lg-6 col-sm-6 foto"><img src="images/<?php echo _('premio_brahma-2.jpg');?>" alt="<?php echo _('Caj&oacute;n de cerveza Brahma (12 botellas de 1L)');?>"/></div>
   				<div class="col-lg-6 col-sm-6 texto">
                	<h3><?php echo _('Caj&oacute;n de cerveza Brahma (12 botellas de 1L)');?></h3>
                  <p><br /><?php echo _('Estamos trabajando para que dentro de poco puedas canjear tus puntos por productos, estate atento!');?></p>                    
                  <div style="clear:both; width:100%; height:25px; border-top: solid 1px #C0C0C0"></div>
                  <a href="#" class="btn-canjear"><?php echo _('PR&Oacute;XIMAMENTE');?></a>
        </div>
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
		if(tipo=="fisico") {
			$("#canje_div_datos").hide();
			$("#canje_div_entrega").show();
		} else {
			if(tipo=="digital") {
				$("#canje_div_datos").hide();
				$("#canje_div_dni").show();
			} else {
				document.getElementById('frm_canje').submit();
			}	
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