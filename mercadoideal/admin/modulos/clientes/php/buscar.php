<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/class/class.grupo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 10; else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['b']) || !isset($_GET['idclnt'])) {echoErrorXML(65, 'Faltan parametros para este modulo');exit;}

//traigo el listado de socios existentes
$ls = new listado($pag,$cant);

if($_GET['idclnt']!='')
	$listado = $ls->getClientes('',$_GET['idclnt'],'');
else	
	$listado = $ls->getClientes($_GET['b'],'',$_GET['st'],$_GET['g']);

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
	<table id="table-1" border=0 cellspacing="0" width="100%">
		<col style="width:10%;"/>
		<col style="width:10%;"/>
		<col style="width:20%;"/>
		<col style="width:10%;"/>
		<col style="width:10%;"/>
		<col style="width:10%;"/>
		<col style="width:5%;"/>	
		<col style="width:5%;"/>	
		<col style="width:5%;"/>	
		<col style="width:5%;"/>	
		<thead>
			<tr bgcolor="#0082A7"> 
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Fecha alta</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Codigo Unico</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Supermercado</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Grupo</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Email</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Saldo</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Bajar</td>				
				<?php if($_SESSION['QLMS_tipo']!="V") { ?>					
					<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Carga Manual</td>
					<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Editar</td>					
					<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Cambiar Estado</td>					
				<?php } ?>	
			</tr>
		</thead>
		<tbody>
			<?php
			$class 	= 'filaBlanco';
			$cli = new cliente;
			$grp = new grupo;
			for($i=0;$i<$fin;$i++) 
			{
				$saldo = 0;
				if($listado[$i]['fechaalta']!='' && $listado[$i]['fechaalta']!='0000-00-00')
				{ 
					$f = preg_split('/-/',$listado[$i]['fechaalta']);
					$fechaalta = $f[2].' '.$nombre_mes_abre[floor($f[1])].' '.$f[0];
				}
				else $fechaalta = '&#160;';
				$saldo = $cli->dameBalanceId($listado[$i]['idcliente']);
				if($grp->select($listado[$i]['idgrupo'])) $grupo = $grp->getNombre(); else $grupo = '';
			?>
			<tr>			
				<td valign="middle" class="<?php echo $class?>"><?php echo $fechaalta;?></td>
				<td valign="middle" class="<?php echo $class?>"><?php echo $listado[$i]['codigo_unico'];?></td>
				<td valign="middle" class="<?php echo $class?>"><?php echo $listado[$i]['razon_social'];?></td>
				<td valign="middle" class="<?php echo $class?>"><?php echo $grupo;?></td>
				<td valign="middle" class="<?php echo $class?>"><?php if($listado[$i]['email']!='') echo $listado[$i]['email']; else echo '&#160;';?></td>
				<td valign="middle" class="<?php echo $class?>"><?php echo $saldo;?></td>
				<td valign="middle" class="<?php echo $class?>">
					<a href='php/balance_completo.php?c=<?php echo $listado[$i]['idcliente'];?>'>
						<img src="../../img/descargar.png" alt="Ver Balance" title="Ver Balance" width="31" height="31" border="0" />
					</a>	
				</td>
				<?php if($_SESSION['QLMS_tipo'] != 'V') { ?>
				<td valign="middle" class="<?php echo $class?>">
					<a href='../movimientos/?n=<?php echo $listado[$i]['razon_social'];?>&c=<?php echo $listado[$i]['idcliente'];?>'>
						<img src="../../img/puntos.png" alt="Carga manual de puntos" title="Carga Manual de puntos" width="31" height="31" border="0" />
					</a>	
				</td>
				<td valign="middle" class="<?php echo $class;?>" align="center">
					<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idcliente'];?>');">
						<img src="../../img/btn_editar.png" alt="Editar socio" title="Editar socio" width="31" height="31" border="0" />
					</a>
				</td>			
				<td valign="middle" class="<?php echo $class;?>" align="right">
					<select id="estado" name="estado" class="selectINPUT5" onChange="cambiaEstado('<?php echo $listado[$i]['idcliente'];?>',this.value)">						
						<option value="P" <?php if($listado[$i]['estado']=='P') echo "selected = 'selected'";?>>preinscripto</option>					
						<option value="A" <?php if($listado[$i]['estado']=='A') echo 'selected = "selected"';?>>activo</option>
						<option value="I" <?php if($listado[$i]['estado']=='I') echo 'selected = "selected"';?>>inactivo</option>
						<option value="C" <?php if($listado[$i]['estado']=='C') echo 'selected = "selected"';?>>confirmado</option>
						<option value="S" <?php if($listado[$i]['estado']=='S') echo 'selected = "selected"';?>>confirmado SPC</option>
					</select>	
				</td>			
				<?php } else {?>	
				<td  class="<?php echo $class;?>" >
					<?php if($listado[$i]['estado']=='P') echo 'Preinscripto';?>
					<?php if($listado[$i]['estado']=='A') echo 'Activo';?>
					<?php if($listado[$i]['estado']=='I') echo 'Inactivo';?>
					<?php if($listado[$i]['estado']=='C') echo 'Confirmado';?>
					<?php if($listado[$i]['estado']=='C') echo 'Confirmado SPC';?>
				</td>		
				<?php } ?>	
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