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

//traigo el listado de premios existentes
$ls = new listado($pag,$cant);

$listado = $ls->getGrupos($_GET['b']);
$nro = count($listado);
if($nro==0) 
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
	<col style="width:5%;"/>
	<col style="width:40%;"/>
	<col style="width:45%;"/>
	<col style="width:3%;text-align='center'"/>	
	<col style="width:3%;text-align='center'"/>	
	<thead>
		<tr bgcolor="#0082A7"> 
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Codigo</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Nombre</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Descripci&#243;n</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class	= 'filaBlanco';
		for($i=0;$i<$nro;$i++) 
		{
		?>
		<tr>			
			<td valign="top" class="<?php echo $class?>"><strong><?php if($listado[$i]['idgrupo']!='') echo $listado[$i]['idgrupo']; else echo '&#160;';?></strong></td>
			<td valign="top" class="<?php echo $class?>"><strong><?php if($listado[$i]['nombre']!='') echo $listado[$i]['nombre']; else echo '&#160;';?></strong></td>
			<td valign="top" class="<?php echo $class?>"><?php if($listado[$i]['descripcion']!='') echo $listado[$i]['descripcion']; else echo '&#160;';?></td>
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idgrupo']?>');">
					<img src="../../img/btn_editar.png" alt="Editar grupo" title="Editar grupo" width="31" height="31" border="0" />
				</a>
			</td>			
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idgrupo']?>');">
					<img src="../../img/btn_eliminar.png" alt="Eliminar grupo" title="Eliminar grupo" width="31" height="31" border="0" />
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