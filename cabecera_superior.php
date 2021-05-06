<header class="gp-header">
	<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-gp2020">
		<a class="navbar-brand" href="home.php"><img src="img/logo_gp2020.svg" alt="GP 2020"/></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="home.php">INICIO</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="catalogo.php">CATÁLOGO</a>
				</li>
				<li class="nav-item"> <a class="nav-link" href="el_programa.php">EL PROGRAMA</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php if($_SESSION['QLMSF_idgrupo']==2 || $_SESSION['QLMSF_idgrupo']==4) echo 'ranking_directa'; else echo 'ranking_distri';?>.php">MI PERFORMANCE</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="novedades.php">NOVEDADES</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="contacto.php">CONTACTO</a>
				</li>
			</ul>
		</div>
	</nav>
</header>