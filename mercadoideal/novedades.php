<?php
require_once 'traduccion.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}
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
	<link rel="shortcut icon" href="https://www.mercadoideal.com.ar/favicon.ico?nocache=1406" />
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="assets/js/fancybox/jquery.fancybox.css" rel="stylesheet" />	
	<!-- Custom CSS -->
	<link href="fonts/fonts-esp.css?nocache=1406" rel="stylesheet" type="text/css">
	<link href="css/mercado-ideal.css?nocache=1406" rel="stylesheet">
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
				<div class="tt-Canjes"><h1><?php echo _('Novedades');?></h1></div>
				<div class="separa"></div>
			</div>
		<div class="mis-datos">
			<div class="col-lg-12" style="padding:0; margin:0">
				
            
				<div id="noticias"></div>
				<p id="loading">
					<img src="assets/img/loading.gif" alt="Cargando..." width="60" />
				</p>
				
                
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

	<!-- jQuery -->
	<script src="js/jquery.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
	<script>	
	$(document).ready(function() {
		traeMasNoticias('si');
		var win = $(window);
		// Each time the user scrolls
		win.scroll(function() {
			// End of the document reached?
			if ($(document).height() - win.height() == win.scrollTop()) {
				$('#loading').show();
				traeMasNoticias('no');				
			}
		});
	});
	
	function traeMasNoticias(esprimeravez) {
		// Uncomment this AJAX call to test it				
		$.ajax({
			url: 'get_noticias.php?tipo=N&epv='+esprimeravez,
			dataType: 'html',
			success: function(html) {
				$('#noticias').append(html);
				$('#loading').hide();
			}
		});
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
