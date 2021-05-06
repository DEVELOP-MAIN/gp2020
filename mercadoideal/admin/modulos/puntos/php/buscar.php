<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 20; else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['d'])||!isset($_GET['h'])||!isset($_GET['p'])) {echoErrorXML(65, 'Faltan parametros para este modulo');exit;}

//traigo el listado de puntos asignados a los supermercados adheridos
$ls = new listado($pag,$cant);
$listado = $ls->getPuntosAsignados($_GET['d'],$_GET['h'],$_GET['p']);
$fin = count($listado);
if($fin == 0) 
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
		<col style="width:10%;"/>
		<col style="width:10%;"/>
		<col style="width:25%;"/>
		<col style="width:5%;"/>
		<col style="width:15%;"/>
		<col style="width:35%;"/>
		<thead>
			<tr bgcolor="#0082A7"> 
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Fecha carga</td>
				<td bgcolor="#c5c5c5" class="textoBlanco12">Fecha asignaci&#243;n</td>
				<td bgcolor="#c5c5c5" class="textoBlanco12">Supermercado</td>
				<td bgcolor="#c5c5c5" class="textoBlanco12">Puntos</td>
				<td bgcolor="#c5c5c5" class="textoBlanco12">Motivo</td>
				<td bgcolor="#c5c5c5" class="textoBlanco12">Observaciones</td>
			</tr>
		</thead>
		<tbody>
			<?php
			$class 	= 'filaBlanco';
			for($i=0;$i<$fin;$i++) 
			{
				//doy formato a la fecha de carga de los puntos
				if($listado[$i]['fecha_carga']!='' && $listado[$i]['fecha_carga']!='0000-00-00')
				{ 
					$f = preg_split('/-/',$listado[$i]['fecha_carga']);
					$fecha_carga = $f[2].' '.$nombre_mes_abre[floor($f[1])].' '.$f[0];
				}
				else $fecha_carga = '&#160;';
				//doy formato a la fecha de asignación de los puntos
				if($listado[$i]['fecha']!='' && $listado[$i]['fecha']!='0000-00-00')
				{ 
					$f = preg_split('/-/',$listado[$i]['fecha']);
					$fecha_asignacion = $f[2].' '.$nombre_mes_abre[floor($f[1])].' '.$f[0];
				}
				else $fecha_asignacion = '&#160;';
			?>
			<tr>			
				<td valign="top" class="<?php echo $class?>"><?php echo $fecha_carga;?></td>
				<td valign="top" class="<?php echo $class?>"><?php echo $fecha_asignacion;?></td>
				<td valign="top" class="<?php echo $class?>"><?php echo $listado[$i]['nombre'];?></td>
				<td valign="top" class="<?php echo $class?>"><?php echo $listado[$i]['puntos'];?></td>
				<td valign="top" class="<?php echo $class?>"><?php echo $listado[$i]['motivo'];?></td>
				<td valign="top" class="<?php echo $class?>"><?php echo $listado[$i]['observaciones'];?></td>
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