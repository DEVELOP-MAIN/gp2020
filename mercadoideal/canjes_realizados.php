<?php
require_once 'traduccion.php';
require_once 'admin/php/class/class.cliente.php';
require_once 'admin/php/class/class.canje.php';
require_once 'admin/php/minixml/minixml.inc.php';
require_once 'admin/php/generales.php';

if(!isset($_SESSION)) {session_start();}
$idcliente = $_SESSION['QLMSF_idcliente'];

$clnt = new cliente();	
$canjes = array();
if(!$clnt->select($idcliente)) 
{
	header('location:index.php');
	exit;
}
else 
{
	$canjes = $clnt->getCanjes();
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
				<div class="tt-Canjes">
					<h1><i class="fa fa-gift"></i><?php echo _('Canjes Realizados');?></h1>
        </div>
				<div class="separa"></div>
			</div>   
		<div class="mis-canjes">
			<?php
			$nro = count($canjes);
			if($nro > 0)
			{	
				$estado = 'estado';
				$cnj = new canje();
				for($i=0;$i<$nro;$i++) 
				{
					if($canjes[$i]['fecha']!='')
					{ 
						$fe1 = preg_split('/ /',$canjes[$i]['fecha']);
						$fe2 = preg_split('/-/',$fe1[0]);
						$fecha_canje	= $fe2[2].'-'.$fe2[1].'-'.$fe2[0];
					}
					else $fecha_canje	= '';
					
					switch($canjes[$i]['estado'])
					{
						case 'solicitado'			: $puntos	= 'puntos'; $estado	= 'estado'; $direccion	= 'direccion'; break;
						case 'efectivizado'	: $puntos	= 'puntos_entregado'; $estado	= 'estado entregado'; $direccion	= 'direccion entregado_'; break;
						case 'en proceso'		: $puntos	= 'puntos_entregado'; $estado	= 'estado entregado'; $direccion	= 'direccion entregado_'; break;
						case 'anulado'				: $puntos	= 'puntos_cancelado'; $estado	= 'estado cancelado'; $direccion	= 'direccion cancelado_'; break;
						case 'devuelto'			: $puntos	= 'puntos_devuelto'; $estado	= 'estado devuelto'; $direccion	= 'direccion devuelto_'; break;
					}
					?>
					<!--caja premio-->
					<div class="col-sm-6 col-lg-3 col-md-3">
						<div class="caja-premio">
							<div class="foto"><img src="archivos/<?php echo $canjes[$i]['fotopremio'];?>" alt="<?php echo $canjes[$i]['premio'];?>"/></div>
							<div class="nombre"><?php if(strlen($canjes[$i]['premio'])>70) echo substr($canjes[$i]['premio'], 0, 70).'...'; else echo $canjes[$i]['premio'];?></div>
							<div class="<?php echo $puntos;?>"><?php echo $canjes[$i]['valor'];?><span> pts.</span></div>
							<div class="<?php echo $estado;?>"><?php echo _('Estado:');?> <?php echo $canjes[$i]['estado'];?> <?php if($canjes[$i]['estado'] != 'anulado') echo $fecha_canje;?></div>
							
							<?php if(($canjes[$i]['tipo'] == 'fisico' || $canjes[$i]['tipo'] == 'mixto') && $canjes[$i]['estado'] != 'anulado') {?>
							<div class="<?php echo $direccion;?>"><?php echo _('Dirección de entrega');?>: <?php if($canjes[$i]['estado'] != 'anulado') echo $canjes[$i]['lugar_entrega'];?></div>
								<?php if($canjes[$i]['seguimiento']!="") {?>
									<div class="<?php echo $direccion;?>"><a class="ls-modal seguimiento" href="https://www1.oca.com.ar/OEPTrackingWeb/detalleenviore.asp?numero=<?php echo $canjes[$i]['seguimiento']?>"><i class="fa fa-truck" aria-hidden="true"></i> <?php echo _('Seguimiento del envio');?></a></div>
								<?php }?>
							<?php }?>
							<?php if($canjes[$i]['tipo'] == 'digital' && $canjes[$i]['estado'] != 'anulado' && $canjes[$i]['estado'] != 'devuelto') {?>
								<div class="<?php echo $direccion;?>"><?php echo _('voucher enviado a');?> <?php echo $_SESSION['QLMSF_email'];?></div>
							<?php }?>
							<?php if($canjes[$i]['tipo'] == 'chances' && $canjes[$i]['estado'] != 'anulado' && $canjes[$i]['estado'] != 'devuelto') {?>
								<div class="<?php echo $direccion;?>">¡<?php echo _('Sumaste');?> <?php echo $canjes[$i]['chances'];?> <?php echo _('chances para el sorteo');?>!</div>
							<?php }?>
						</div>
					</div>
					<?php
						}
					}
					else
					{	
					?>
					<div class="alerta-mod"><?php echo _('A&#250;n no has realizado canjes');?></div>
					<?php }?>
					<div style="clear:both; width:100%; height:1px"></div>
				</div>
             </div>  
		</div>
	<!-- /.container -->

	<?php include_once("pie.php")?>
	
<div id="myModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo _('Seguimiento del envio');?></h4>
            </div>
            <div class="modal-body">
                <iframe name="iframeoca" id="iframeoca" src="javscript: void(0);" sandbox height="450" width="100%" style="border:none;"></iframe>
            </div>            
    </div>
</div>
	<script>
	$('.ls-modal').on('click', function(e){
		var urlvar = $(this).attr('href');
		document.getElementById('iframeoca').src = urlvar;
		e.preventDefault();
		$('#myModal').modal('show');
	});
	
	 
	</script>
</body>
</html>