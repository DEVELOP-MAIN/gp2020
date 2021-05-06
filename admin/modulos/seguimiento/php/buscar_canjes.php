<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/xml;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') $pag = 0; else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='') $cant = 50; else	$cant = $_GET['cant'];

//traigo el listado de canjes existentes
$ls = new listado($pag,$cant);
$listado = $ls->getCanjes($_GET['b'], $_GET['e'],$_GET['d'],$_GET['h'],$_GET['t'],$_GET['ts']);
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
<?php } else {?>
<tables>
<table id="table-1" cellspacing="0" width="100%" border="0">	
	<col style="width:5%;"/>
	<col style="width:10%;"/>
	<col style="width:15%;"/>
	<col style="width:25%;"/>
	<col style="width:25%;"/>
	<col style="width:7%;"/>	
	<col style="width:7%;"/>	
	<thead>
		<tr bgcolor="#c5c5c5"> 
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;ID</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Fecha</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Cliente</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Premio</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12" align="center">COD. SEGUIMIENTO</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12" align="center">SEGUIR</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12" align="right">Estado&#160;</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class = 'filaBlanco';
		for($i=0;$i<$nro;$i++){
			//doy formato a la fecha del canje
			if($listado[$i]['fecha']!=''){
				$fe1 = preg_split('/ /',$listado[$i]['fecha']);
				$fe2 = preg_split('/-/',$fe1[0]);
				$fecha_canje	= $fe2[2].' '.$nombre_mes_abre[floor($fe2[1])].' '.$fe2[0].'<br />'.$fe1[1];
			}
			else $fecha_canje	= '';
		?>
		<tr>			
			<td height="25" align="left" valign="top" class="<?php echo $class?>"><?php echo $listado[$i]['idcanje'];?></td>
			<td height="25" align="left" valign="top" class="<?php echo $class?>"><?php echo $fecha_canje;?></td>
			<td align="left" valign="top" class="<?php echo $class?>">
				<strong><?php echo $listado[$i]['cliente']?></strong><br/>
				<strong>COD UNICO:</strong> <?php echo $listado[$i]['codigo_unico']?>
				<?php if($listado[$i]['email'] != '') echo '<br />'.$listado[$i]['email'];?>
				<?php if($listado[$i]['movil'] != '') echo '<br />'.$listado[$i]['movil'];?>
				<?php if($listado[$i]['fijo'] != '') echo '<br />'.$listado[$i]['fijo'];?>
			</td>
			<td align="left" valign="middle" class="<?php echo $class?>"><?php echo $listado[$i]['premio'];?></td>
			<td align="center" valign="middle" class="<?php echo $class?>">
				<input type="text" value="<?php echo $listado[$i]["seguimiento"]?>" name="codigo_<?php echo $listado[$i]["idcanje"]?>" id="codigo_<?php echo $listado[$i]["idcanje"]?>"> &nbsp;&nbsp;<a href="#" onclick="guardarCodigo(<?php echo $listado[$i]["idcanje"]?>)"><img name="img_<?php echo $listado[$i]["idcanje"]?>" id="img_<?php echo $listado[$i]["idcanje"]?>" src="../../img/btn_edit_on.gif" width="15"></a>
			</td>
			<td align="center" valign="middle" class="<?php echo $class?>">
				<?php if($listado[$i]["seguimiento"]!="") {?>
					<a href="https://track.aftership.com/oca-ar/<?php echo $listado[$i]['seguimiento']?>&#38iframe=true&#38width=629&#38height=296" rel="prettyPhoto[]" class="alfa">
						<img src="../../img/btn_historial.gif" width="28" height="28" border="0" title="Ver el seguimiento" />
					</a>
				<?php }?>	
			</td>			
			<td valign="middle" class="<?php echo $class?>" align="right">
				<?php
				switch($listado[$i]['estado']){
					case 'anulado': echo '[ANULADO]&#160;'; break;
					case 'devuelto': echo '[DEVUELTO]&#160;'; break;
					case 'solicitado':
				?>
				<select id="alta_estado" name="alta_estado" class="selectINPUT5" onchange="modificaEstado('<?php echo $listado[$i]['idcanje']?>', this.value);"> 
					<option value="solicitado" <?php if($listado[$i]['estado']=='solicitado') echo 'selected = "selected"'?>>solicitado</option>
					<option value="en proceso" <?php if($listado[$i]['estado']=='en proceso') echo 'selected = "selected"'?>>en proceso</option>
					<option value="efectivizado" <?php if($listado[$i]['estado']=='efectivizado') echo 'selected = "selected"'?>>efectivizado</option>
					<option value="anulado" <?php if($listado[$i]['estado']=="anulado") echo 'selected = "selected"'?>>anulado</option>
				</select>
				<?php
						break;
					case 'en proceso':
				?>
				<select id="alta_estado" name="alta_estado" class="selectINPUT5" onchange="modificaEstado('<?php echo $listado[$i]['idcanje']?>', this.value);">
					<option value="en proceso" <?php if($listado[$i]['estado']=='en proceso') echo 'selected = "selected"'?>>en proceso</option>
					<option value="efectivizado" <?php if($listado[$i]['estado']=='efectivizado') echo 'selected = "selected"'?>>efectivizado</option>
					<option value="anulado" <?php if($listado[$i]['estado']=="anulado") echo 'selected = "selected"'?>>anulado</option>
				</select>
				<?php
						break;
					case 'efectivizado':
				?>
				<select id="alta_estado" name="alta_estado" class="selectINPUT5" onchange="modificaEstado('<?php echo $listado[$i]['idcanje']?>', this.value);"> 
					<option value="efectivizado" <?php if($listado[$i]['estado']=='efectivizado') echo 'selected = "selected"'?>>efectivizado</option>
					<option value="devuelto" <?php if($listado[$i]['estado']=='devuelto') echo 'selected = "selected"'?>>devolver</option>
					<option value="anulado" <?php if($listado[$i]['estado']=="anulado") echo 'selected = "selected"'?>>anular</option>
				</select>
				<?php
						break;
				}
				?>
			</td>
		</tr>
		<?php
			if($class=='filaBlanco') $class = 'filaceleste'; else $class = 'filaBlanco';
		}
		?>		
	</tbody>
</table>
</tables>
<?php }?>