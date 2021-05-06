<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/xml;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];	
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 10; else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['b']) || !isset($_GET['t']))	{printErrorXML(65, 'Faltan parametros para este modulo');	exit;}

//traigo el listado de usuarios de sistema existentes
$ls = new listado($pag,$cant);
$listado = $ls->getUsuariosSistema($_GET['b'],$_GET['t']);
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
	<col style="width:12%;"/>
	<col style="width:25%;"/>
	<col style="width:25%;"/>
	<col style="width:2%;text-align='center'"/>	
	<col style="width:2%;text-align='center'"/>	
	<thead>
		<tr bgcolor="#c5c5c5"> 
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Tipo de Usuario</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Nombre de Usuario</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Clave</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class = 'filaBlanco';
		for($i=0;$i<$nro;$i++) 
		{
			switch($listado[$i]['tipo'])
			{
				case 'A': $tipo = 'administrador general'; break;
				case 'C': $tipo = 'operador de sups'; break;
				case 'P': $tipo = 'operador de premios'; break;
				case 'D': $tipo = 'delivery'; break;
				case 'V': $tipo = 'visualizador de sups'; break;
				default: $tipo = '';
			}
		?>
		<tr>			
			<td valign="middle" class="<?php echo $class?>"><?php echo $tipo;?></td>			
			<td valign="middle" class="<?php echo $class?>"><?php if($listado[$i]['usuario']!='') echo $listado[$i]['usuario']; else echo '&#160;';?></td>			
			<td valign="middle" class="<?php echo $class?>"><?php if($listado[$i]['clave']!='') echo $listado[$i]['clave']; else echo '&#160;';?></td>			
			<td valign="middle" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idusuario']?>');">
					<img src="../../img/btn_editar.png" alt="Editar usuario" title="Editar usuario" width="31" height="31" border="0" />
				</a>
			</td>			
			<td valign="middle" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idusuario']?>');">
					<img src="../../img/btn_eliminar.png" alt="Eliminar usuario" title="Eliminar usuario" width="31" height="31" border="0" />
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