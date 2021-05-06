<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 10; else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['b'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//traigo el listado de novedades existentes
$ls = new listado($pag,$cant);
$listado = $ls->getNoticias($_GET['b']);
$nro = count($listado);
if($nro==0){
?>
<table bgcolor="#c5c5c5" class="textoBlanco12" id="table-0" cellspacing="0" height="60" width="100%">
	<col style="width:98%;font-weight:bold"/>	
	<thead>
		<tr>
			<td align="center" height="60" valign="middle">No se encontraron resultados para su b&#250;squeda</td>
		</tr>
	</thead>
</table>
<?php
} else {
?>
<tables>
<table id="table-1" cellspacing="0" width="100%">
	<col style="width:10%;"/>
	<col style="width:30%;"/>
	<col style="width:10%;"/>
	<col style="width:40%;"/>
	<col style="width:3%; text-align:center"/>
	<col style="width:3%; text-align:center"/>
	<thead>
		<tr bgcolor="#0082A7"> 
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Fecha</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">T&#237;tulo</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Media</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Texto</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class='filaBlanco';
		for($i=0;$i<count($listado);$i++)	{
			//seteo la fecha de alta
			if($listado[$i]['fecha_alta']!=''){ 
				$v = preg_split('/-/',$listado[$i]['fecha_alta']);
				$fecha_alta = $v[2].' '.$nombre_mes_abre[floor($v[1])].' '.$v[0];
			}
			else	$fecha_alta = '&#160;';
			
			//limito el cuerpo de la noticia a 200 caracteres	
			if($listado[$i]['cuerpo'] != ''){	
				if(strlen($listado[$i]['cuerpo'])>200) 
					$cuerpo = myTruncate($listado[$i]['cuerpo'],200);
				else	
					$cuerpo = $listado[$i]['cuerpo'];	
			}
			else	$cuerpo = '';	
		?>
		<tr>
			<td valign="top" class="<?php echo $class;?>"><?php echo $fecha_alta;?></td>
			<td valign="top" class="<?php echo $class;?>"><strong><?php if($listado[$i]['titulo']!='') echo $listado[$i]['titulo']; else echo '&#160;';?></strong></td>
			<td valign="top" class="<?php echo $class;?>">
				<?php if($listado[$i]['imagen']!=''){?>
				<a href="../../../archivos/<?php echo $listado[$i]['imagen'];?>" target="_blank" style="text-decoration: none;">
					<img src="../../../archivos/<?php echo $listado[$i]['imagen'];?>" target="_blank" width="75" border="0">
				</a>
				<?php }?>
				<?php if($listado[$i]['video']!=''){?>				
					<img src="../../img/youtube_logo.png" alt="Ver video de la noticia" title="Ver video de la noticia" border="0" />				
				<?php }?>
			</td>
			<td valign="top" class="<?php echo $class;?>"><?php echo $cuerpo;?></td>
			<td valign="top" class="<?php echo $class;?>" align="center">
				<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idnoticia'];?>');">
					<img src="../../img/btn_editar.png" alt="Editar novedad" title="Editar novedad" width="31" height="31" border="0" />
				</a>
			</td>
			<td valign="top" class="<?php echo $class;?>" align="center">
				<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idnoticia'];?>');">
					<img src="../../img/btn_eliminar.png" alt="Eliminar novedad" title="Eliminar novedad" width="31" height="31" border="0" />
				</a>
			</td>
		</tr>
		<?php
			if($class=='filaBlanco') $class='filaceleste'; else $class='filaBlanco';
		}
		?>
	</tbody>
</table>
<table width="100%" cellspacing="0" border="0" height="30">		
	<tr>
		<td width="100%" align="center" height="30" valign="middle"><?php echo $ls->getPaginacion();?></td>
	</tr>
</table>
</tables>
<?php }?>