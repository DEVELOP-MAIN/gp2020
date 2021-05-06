<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.socio.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

if(!isset($_SESSION)) {session_start();}

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 50; else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['b']) || !isset($_GET['idclnt'])) {echoErrorXML(65, 'Faltan parametros para este modulo');exit;}

if($_GET['ej']!=''){
	$f = preg_split('/,/',$_GET['ej']);
	$ej = $f[0];
}
else $ej = '';

if($_GET['jf']!=''){
	$f = preg_split('/,/',$_GET['jf']);
	$jf = $f[0];
}
else $jf = '';

if($_GET['gr']!=''){
	$f = preg_split('/,/',$_GET['gr']);
	$gr = $f[0];
}
else $gr = '';

//traigo el listado de socios distribuidores existentes
$ls = new listado($pag,$cant);

if($_GET['idclnt']!='')
	$listado = $ls->getClientes('',$_GET['idclnt']);
else	
	$listado = $ls->getClientes($_GET['b'],'','1',$_GET['rg'],$ej,$jf,$gr);

$fin = count($listado);
if($fin == 0){
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
<table id="table-1" border=0 cellspacing="0" width="100%">
	<col style="width:5%;"/>
	<col style="width:20%;"/>
	<col style="width:5%;"/>
	<col style="width:10%;"/>
	<col style="width:10%;"/>
	<col style="width:15%;"/>
	<col style="width:3%;"/>
	<col style="width:5%;"/>
	<col style="width:5%;"/>
	<col style="width:5%;"/>
	<col style="width:5%;"/>
	<thead>
		<tr bgcolor="#0082A7">
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Fch alta</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Socio</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Codigo</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Rango</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Region</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Email</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Saldo</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Balance</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Carga Manual</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12" align="center">Editar</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12" align="center">Eliminar</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class = 'filaBlanco';
		$cli = new socio;
		for($i=0;$i<$fin;$i++){
			$saldo = 0;
			if($listado[$i]['fechaalta']!='' && $listado[$i]['fechaalta']!='0000-00-00'){
				$f = preg_split('/-/',$listado[$i]['fechaalta']);
				$fechaalta = $f[2].$nombre_mes_abre[floor($f[1])].$f[0];
			}
			else $fechaalta = '&#160;';
			$saldo = $cli->dameBalanceId($listado[$i]['idsocio']);
		?>
		<tr>			
			<td valign="middle" class="<?php echo $class?>"><?php echo $fechaalta;?></td>
			<td valign="middle" class="<?php echo $class?>"><?php echo $listado[$i]['apellido'].', '.$listado[$i]['nombre'];?></td>
			<td valign="middle" class="<?php echo $class?>"><?php echo $listado[$i]['codigo'];?></td>
			<td valign="middle" class="<?php echo $class?>"><?php echo $listado[$i]['rango'];?></td>
			<td valign="middle" class="<?php echo $class?>"><?php echo $listado[$i]['region'];?></td>
			<td valign="middle" class="<?php echo $class?>"><?php if($listado[$i]['email']!='') echo $listado[$i]['email']; else echo '&#160;';?></td>
			<td valign="middle" class="<?php echo $class?>"><?php echo $saldo;?></td>
			<td valign="middle" class="<?php echo $class?>">
				<a href='php/balance_completo.php?c=<?php echo $listado[$i]['idsocio'];?>'>
					<img src="../../img/descargar.png" alt="Ver Balance" title="Ver Balance" width="31" height="31" border="0" />
				</a>	
			</td>
			<td valign="middle" class="<?php echo $class?>">
				<a href='../movimientos/?n=<?php echo $listado[$i]['apellido'].', '.$listado[$i]['nombre'];?>&c=<?php echo $listado[$i]['idsocio'];?>'>
					<img src="../../img/puntos.png" alt="Carga manual de millas" title="Carga Manual de millas" width="31" height="31" border="0" />
				</a>
			</td>
			<td valign="middle" class="<?php echo $class;?>" align="center">
				<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idsocio'];?>');">
					<img src="../../img/btn_editar.png" alt="Editar datos del socio" title="Editar datos del socio" width="31" height="31" border="0" />
				</a>
			</td>
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idsocio']?>');">
					<img src="../../img/btn_eliminar.png" alt="Eliminar socio" title="Eliminar socio" width="31" height="31" border="0" />
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