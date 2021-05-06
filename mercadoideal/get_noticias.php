<?php
require_once 'admin/php/class/class.listado.php';
require_once 'admin/php/generales.php';
require_once 'php/seguridad.php';

if(!isset($_SESSION)) {session_start();}

if(!isset($_SESSION['pag_muro'])) $_SESSION['pag_muro'] = 1; else $_SESSION['pag_muro']++;

//Si es la primera vez que se llama la función traeMasNoticias(esprimeravez), se vuelve la página a 1
if(isset($_REQUEST['epv'])) $epv = $_REQUEST['epv']; else $epv = 'si';
if(isset($_REQUEST['tipo'])) $tipo = $_REQUEST['tipo']; else $tipo = 'N';
if($epv == 'si') $_SESSION['pag_muro'] = 1;

$pag = $_SESSION['pag_muro'];
$ls = new listado($pag, 8);
$noticias = $ls->getNoticias('',$tipo);
$nro = count($noticias);
$_SESSION['total_pags'] = $ls->getTotalPaginas();
if($_SESSION['pag_muro'] <= $_SESSION['total_pags']) $traermas = 1; else $traermas = 0;
if($traermas==1)
{	
	for($i=0;$i<$nro;$i++) 
	{
		//doy formato a la fecha de la noticia
		if($noticias[$i]['fecha_alta']!='')
		{ 
			$f = preg_split('/-/',$noticias[$i]['fecha_alta']);
			$fnov = $f[2].' de '.$nombre_mes[floor($f[1])].', '.$f[0];
		}
		else $fnov = '&#160;';
		
		//determino el path de la imagen de la noticia
		if($noticias[$i]['imagen']!='')
			$img = 'archivos/'.$noticias[$i]['imagen'];
		else 
			$img = 'images/img_default.jpg?nochace=0607';
		
		if($noticias[$i]['estado']!='D') {
			
			echo '<div class="col-sm-6 col-lg-3 col-md-4">';
				echo '<div class="box_noticia">';
					echo '<div class="box_img">';
						echo '<span>'.$fnov.'</span>';
						echo '<img src="'.$img.'" class="img-responsive img" width="100%" height="auto">';
					echo '</div>';
					echo '<h3 class="title_novedad">'.$noticias[$i]['titulo'].'</h3>';
					echo '<div class="content_texto"><p>'.myTruncate($noticias[$i]['cuerpo'], 150).'</p></div>';
						if($noticias[$i]['tipo']=="N")
							echo '<a href="novedades_detalle.php?n='.$noticias[$i]['idnoticia'].'" class="pie_novedad">Leer m&#225;s</a>';
						else
							echo '<a href="tips_detalle.php?n='.$noticias[$i]['idnoticia'].'" class="pie_novedad">Leer m&#225;s</a>';
					echo '<div class="clearfix"></div>';    
				echo '</div>';
			echo '</div>';
		
		} else {		
		
			echo '<!--Destacada-->';
			   echo '<div class="col-sm-6">';
					echo '<div class="box_noticia_dest">';
						echo '<div class="box_img">';
							echo '<span>'.$fnov.'</span>';
							echo '<img src="'.$img.'" class="img-responsive img" width="100%" height="auto">';
						echo '</div>';
						echo '<h3 class="title_novedad">'.$noticias[$i]['titulo'].'</h3>';
						echo '<div class="content_texto">';
							echo '<p>'.myTruncate($noticias[$i]['cuerpo'], 160).'</p>';
						echo '</div>';
						echo '<a href="novedades_detalle.php?n='.$noticias[$i]['idnoticia'].'" class="pie_novedad">Leer m&aacute;s</a>';
						echo '<div class="clearfix"></div>    ';
					echo '</div>';
				echo '</div>';
			echo '<!--Fin destacada-->';
		}	
	}
}