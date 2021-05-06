<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="home.php"><img src="images/mercado_ideal.png" alt="Mercado Ideal"/></a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="home.php"><?php echo _('INICIO');?></a></li>
				<li><a href="premios.php"><?php echo _('PREMIOS');?></a></li>
				<li><a href="el_programa.php"><?php echo _('EL PROGRAMA');?></a></li>
				<li><a href="novedades.php"><?php echo _('NOVEDADES');?></a></li>
        <li><a href="tips.php"><?php echo _('TIPS');?></a></li>
				<li><a href="contacto.php"><?php echo _('CONTACTO');?></a></li>
			</ul>
		</div>
    <div class="idioma">
			<?php if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']=='zh_CN') {?>
				<a href="cambiar_idioma.php?l=ar_ES" title="Cambiar a idioma español"><img src="images/btn_esp_off.png" alt="Idioma español"/></a>
				<a href="cambiar_idioma.php?l=zh_CN"><img src="images/btn_chino.png" alt=""/></a>
			<?php } else {?>	
				<a href="cambiar_idioma.php?l=ar_ES"><img src="images/btn_esp.png" alt="Idioma español"/></a>		 
				<a href="cambiar_idioma.php?l=zh_CN" title="Cambiar a idioma chino"><img src="images/btn_chino_off.png"  alt=""/></a>
			<?php }?>	
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container -->
</nav>

<div class="fin-nav"></div>

<!-- Datos usuario Desktop-->
<div class="container datos-usuario">
	<div class="nombre"><h1><?php echo $_SESSION['QLMSF_nombre']?> <?php echo $_SESSION['QLMSF_apellido']?></h1><span class="cod-unico"><?php echo $_SESSION['QLMSF_codigo_unico']?></span></div>
	<div class="puntos"><h1><?php echo $_SESSION['QLMSF_puntos']?></h1><?php echo _('puntos');?></div>
	<div class="ranking">
		<?php echo _('Ranking:');?>
		<?php 
		if($_SESSION['QLMSF_ranking'] <= 50) 
		{
		?>
		<br /><?php echo _('Est&aacute;s en el puesto');?><br /><span><?php echo $_SESSION['QLMSF_ranking']?></span>
		<?php 
		} 
		else 
		{
			if($_SESSION['QLMSF_ranking'] > 50 && $_SESSION['QLMSF_ranking'] <= 100) echo '<h3>'._('Est&aacute;s entre las posiciones 51 y 100').'</h3>';
			if($_SESSION['QLMSF_ranking'] > 100 && $_SESSION['QLMSF_ranking'] <= 200) echo '<h3>'._('Est&aacute;s entre las posiciones 101 y 200').'</h3>';
			if($_SESSION['QLMSF_ranking'] > 200 && $_SESSION['QLMSF_ranking'] <= 300) echo '<h3>'._('Est&aacute;s entre las posiciones 201 y 300').'</h3>';
			if($_SESSION['QLMSF_ranking'] > 300 && $_SESSION['QLMSF_ranking'] <= 500) echo '<h3>'._('Est&aacute;s entre las posiciones 301 y 500').'</h3>';
			if($_SESSION['QLMSF_ranking'] > 500 && $_SESSION['QLMSF_ranking'] <= 1000) echo '<h3>'._('Est&aacute;s entre las posiciones 501 y 1000').'</h3>';
			if($_SESSION['QLMSF_ranking'] > 1000 && $_SESSION['QLMSF_ranking'] <= 2000) echo '<h3>'._('Est&aacute;s entre las posiciones 1001 y 2000').'</h3>';
			if($_SESSION['QLMSF_ranking'] > 2000)  echo '<h3>'._('Est&aacute;s en una posici&oacute;n mayor a 2000').'</h3>';
		}
		?>	
	</div>
	<a href="datos_personales.php" class="links-acceso">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-user fa-stack-1x fa-inverse"></i>
		</span><br />
		<p><?php echo _('Datos personales');?></p>
	</a>
	<a href="canjes_realizados.php" class="links-acceso">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-gift fa-stack-1x fa-inverse"></i>
		</span><br />
		<p><?php echo _('Canjes realizados');?></p>
	</a>
	<a href="balance_puntos.php" class="links-acceso">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-bar-chart fa-stack-1x fa-inverse"></i>
		</span><br />
		<p><?php echo _('Balance de puntos');?></p>
	</a>
	<div class="salir">
		<a href="php/logout.php"><?php echo _('SALIR');?></a>
	</div>
</div>
<!-- Datos usuario Mobile-->
<div class="container datos-usuario-mb">
	<div class="nombre"><h1><?php echo $_SESSION['QLMSF_nombre']?> <?php echo $_SESSION['QLMSF_apellido']?></h1><span class="cod-unico"><?php echo $_SESSION['QLMSF_codigo_unico']?></span></div>
	<div class="puntos"><h1><?php echo $_SESSION['QLMSF_puntos']?></h1><?php echo _('puntos');?></div>
	<div class="salir"><a href="php/logout.php"><?php echo _('SALIR');?></a></div>
	<div class="ranking"><?php echo _('Ranking: Est&aacute;s en el puesto');?> <strong><?php echo $_SESSION['QLMSF_ranking']?></strong></div>
	<a href="datos_personales.php" class="links-acceso">
		<span class="fa-stack fa-lg">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-user fa-stack-1x fa-inverse"></i>
		</span><br />
		<p><?php echo _('Datos personales');?></p>
	</a>
	<a href="canjes_realizados.php" class="links-acceso">
		<span class="fa-stack fa-lg">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-gift fa-stack-1x fa-inverse"></i>
		</span><br />
		<p><?php echo _('Canjes realizados');?></p>
	</a>
	<a href="balance_puntos.php" class="links-acceso">
		<span class="fa-stack fa-lg">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-bar-chart fa-stack-1x fa-inverse"></i>
		</span><br />
		<p><?php echo _('Balance de puntos');?></p>
	</a>
</div>