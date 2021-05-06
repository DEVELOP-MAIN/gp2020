<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.premio.php';
require_once '../../../php/class/class.campania.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 10; else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['b']) || !isset($_GET['c']) || !isset($_GET['d'])) {echoErrorXML(65, "Faltan parametros para este modulo");exit;}

//traigo el listado de premios existentes
$ls = new listado($pag,$cant);

$listado = $ls->getPremios($_GET['b'],$_GET['c'],$_GET['d']);
$nro_prms = count($listado);
if($nro_prms==0) 
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
	<col style="width:15%;"/>
	<col style="width:8%;"/>
	<col style="width:8%;"/>
	<col style="width:13%;"/>
	<col style="width:10%;"/>
	<col style="width:5%;text-align='center'"/>	
	<col style="width:3%;text-align='center'"/>	
	<col style="width:3%;text-align='center'"/>	
	<col style="width:3%;text-align='center'"/>	
	<thead>
		<tr bgcolor="#0082A7"> 
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Imagen</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Nombre</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Tipo</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Campa&#241;a</td>
			<td height="27" align="center" bgcolor="#c5c5c5" class="textoBlanco12">Stock Inicial / Stock Real</td>
			<td height="27" align="center" bgcolor="#c5c5c5" class="textoBlanco12">Costo (puntos)</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Destacado</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class	= 'filaBlanco';
		$prm 		= new premio();
		$cmpn	= new campania();
		for($i=0;$i<$nro_prms;$i++) 
		{
			//recupero el nombre de la campaña
			if($cmpn->select($listado[$i]['idcampania']))				
				$nombre_campania = $cmpn->getNombre();
			else
				$nombre_campania = '';
			//determino el stock real del premio (stock inicial - canjes)
			$stock_real = '';
			$stock_real = $prm->getStockReal($listado[$i]['idpremio']);				
			if($stock_real=='') $stock_real = 0;
		?>
		<tr>			
			<td valign="top" class="<?php echo $class?>">
				<?php
				if($listado[$i]['imagen']!='') 
					echo '<a href="../../../archivos/'.$listado[$i]['imagen'].'"><img src="../../../archivos/'.$listado[$i]['imagen'].'" width="60" border="0" /></a>';
				else 
					echo '&#160;';
				?>
			</td>
			<td valign="top" class="<?php echo $class?>"><strong><?php if($listado[$i]['nombre']!='') echo $listado[$i]['nombre']; else echo '&#160;';?></strong></td>
			<td valign="top" class="<?php echo $class?>"><?php if($listado[$i]['tipo']!='') echo $listado[$i]['tipo']; else echo '&#160;';?></td>
			<td valign="top" class="<?php echo $class?>"><?php echo $nombre_campania;?></td>
			<td valign="top" align="center" class="<?php echo $class?>"><?php echo $listado[$i]['stock_inicial'].' / '.$stock_real?></td>
			<td valign="top" align="center" class="<?php echo $class?>"><?php if($listado[$i]['valor']!='') echo $listado[$i]['valor']; else echo '&#160;';?></td>
			<td valign="top" class="<?php echo $class;?>" align="right">
				<select id="destacado" name="destacado" class="selectINPUT5" onChange="cambiaDestacado('<?php echo $listado[$i]['idpremio'];?>',this.value)">						
					<option value="N" <?php if($listado[$i]['destacado']=='N') echo "selected = 'selected'";?>>no</option>					
					<option value="S" <?php if($listado[$i]['destacado']=='S') echo 'selected = "selected"';?>>s&#237;</option>
				</select>	
			</td>	
			<td valign="top" class="<?php echo $class?>" align="center">
				<?php if($listado[$i]['tipo'] == 'digital' || $listado[$i]['tipo'] == 'mixto'){?>
				<a href='../../modulos/premio_codigos/?idp=<?php echo $listado[$i]['idpremio']?>&np=<?php echo $listado[$i]['nombre'];?>' title="administrar codigos del premio" style="text-decoration:none">
					<img src="../../img/icon_codigo.gif" alt="administrar codigos del premio" title="administrar codigos del premio" width="28" height="28" border="0" />
				</a>
				<?php } else echo '&#160;';?>
			</td>			
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idpremio']?>');">
					<img src="../../img/btn_editar.png" alt="Editar premio" title="Editar premio" width="31" height="31" border="0" />
				</a>
			</td>			
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idpremio']?>');">
					<img src="../../img/btn_eliminar.png" alt="Eliminar premio" title="Eliminar premio" width="31" height="31" border="0" />
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