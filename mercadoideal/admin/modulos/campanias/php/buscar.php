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
if(!isset($_GET['b'])) {echoErrorXML(65, "Faltan parametros para este modulo");exit;}

//traigo el listado de campañas existentes
$ls = new listado($pag,$cant);

$listado = $ls->getCampanias($_GET['b']);
$nro_cmpns = count($listado);
if($nro_cmpns==0) 
{
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
}
else
{
?>
	<tables>
	<table id="table-1" cellspacing="0" width="100%">
		<col style="width:90%;"/>		
		<col style="width:5%;text-align='center'"/>	
		<col style="width:5%;text-align='center'"/>	
		<thead>
			<tr bgcolor="#0082A7"> 
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Nombre</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			</tr>
		</thead>
		<tbody>
			<?php
			$class	= 'filaBlanco';
			for($i=0;$i<$nro_cmpns;$i++) 
			{
				//doy formato a la fecha inicial de la campaña
				if($listado[$i]['fecha_inicial'] != '')
				{
					$f = preg_split('/-/',$listado[$i]['fecha_inicial']);
					$fecha_inicial = $f[2].' '.$nombre_mes_abre[floor($f[1])].' '.$f[0];
				}
				else $fecha_inicial = '&#160;';
				//doy formato a la fecha final de la campaña
				if($listado[$i]['fecha_final'] != '')
				{
					$f = preg_split('/-/',$listado[$i]['fecha_final']);
					$fecha_final = $f[2].' '.$nombre_mes_abre[floor($f[1])].' '.$f[0];
				}
				else $fecha_final = '&#160;';
			?>
			<tr>			
				<td valign="middle" class="<?php echo $class?>"><strong><?php if($listado[$i]['nombre']!='') echo $listado[$i]['nombre']; else echo '&#160;';?></strong></td>
				
				<td valign="middle" class="<?php echo $class?>" align="center">
					<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idcampania']?>');">
						<img src="../../img/btn_editar.png" alt="Editar campania" title="Editar campania" width="31" height="31" border="0" />
					</a>
				</td>			
				<td valign="middle" class="<?php echo $class?>" align="center">
					<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idcampania']?>');">
						<img src="../../img/btn_eliminar.png" alt="Eliminar campania" title="Eliminar campania" width="31" height="31" border="0" />
					</a>
				</td>			
			</tr>
			<?php
				if($class=='filaBlanco') $class = 'filaceleste'; else $class = 'filaBlanco';
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