<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/xml;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 10; else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['e'])) {printErrorXML(65, 'Faltan parametros para este modulo');	exit;}

//traigo el listado de mensajes existentes
$ls = new listado($pag,$cant);
$listado = $ls->getMensajes($_GET['e']);
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
	<col style="width:10%;"/>
	<col style="width:8%;"/>
	<col style="width:16%;"/>
	<col style="width:50%;"/>
	<col style="width:3%;text-align='center'"/>	
	<col style="width:3%;text-align='center'"/>	
	<thead>
		<tr bgcolor="#c5c5c5">
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Fecha</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Estado</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Remite</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Mensaje</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class = 'filaBlanco';
		for($i=0;$i<$nro;$i++)
		{
			$fecha 	= '';
			$cliente	= '';
			if($listado[$i]['fecha']!='')
			{
				$f = preg_split('/ /',$listado[$i]['fecha']);
				$f2 = preg_split('/-/',$f[0]);
				$fecha = $f2[2].$nombre_mes_abre[floor($f2[1])].$f2[0].' '.$f[1];
			}
			$clnt = new cliente();
			if($listado[$i]['idcliente'] != '')
			{
				if($clnt->select($listado[$i]['idcliente']))
					$cliente = $clnt->getNombre().'<br />'.$clnt->getEmail().'<br />'.$clnt->getCodigo_unico();
			}
		?>
		<tr>			
			<td valign="top" class="<?php echo $class?>"><?php echo $fecha;?></td>
			<td valign="top" class="<?php echo $class?>">
				<?php
				if($listado[$i]['estado'] == 'respondido') echo '<font color="#000000"><strong>respondido</strong></font>';
				if($listado[$i]['estado'] == 'no leido') echo '<font color="RED"><strong>no le&#237;do</strong></font>';
				if($listado[$i]['estado'] == 'leido') echo '<font color="GREEN"><strong>le&#237;do</strong></font>';
				?>
			</td>
			<td valign="top" class="<?php echo $class?>"><?php echo $cliente?></td>	
			<?php if($listado[$i]['estado']!='respondido') {?>	
			<td valign="top" class="<?php echo $class?>"><?php if($listado[$i]['mensaje']!='') echo nl2br($listado[$i]['mensaje']); else echo '&#160;';?></td>	
			<td align="center" valign="top" class="<?php echo $class?>">
				<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idmensaje']?>');">
					<img src="../../img/btn_editar.png" alt="Ver/Responder" title="Ver/Responder" width="31" height="31" border="0" />
				</a>
			</td>			
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="verificarEliminar('<?php echo $listado[$i]['idmensaje']?>');">
					<img src="../../img/btn_eliminar.png" alt="Eliminar mensaje" title="Eliminar mensaje" width="31" height="31" border="0" />
				</a>
			</td>			
			<?php } else {?>	
			<td colspan="3" valign="top" class="<?php echo $class?>"><?php if($listado[$i]['mensaje']!='') echo nl2br($listado[$i]['mensaje']); else echo '&#160;';?></td>					
			<?php }?>	
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