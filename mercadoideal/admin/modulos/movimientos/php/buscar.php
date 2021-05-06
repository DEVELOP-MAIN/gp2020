<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

//verifico que vengan los parametros
if(!isset($_GET["idusr"]) || !isset($_GET["d"]) || !isset($_GET["h"])) {printErrorXML(65, "Faltan parametros para este modulo");exit;}

//traigo el listado de usuarios existentes
$ls = new listado(0,10000);

$listado = $ls->getMovimientosManuales($_GET["idusr"],$_GET["d"],$_GET["h"]);
if(count($listado)==0) 
{
?>
	<table bgcolor="#484848" class="textoBlanco12" id="table-0" cellspacing="0" height="60" width="100%">
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
		<col style="width:20%;"/>
		<col style="width:50%;"/>
		<col style="width:5%;text-align='center'"/>	
		<col style="width:5%;text-align='center'"/>	
		<thead>
			<tr bgcolor="#0082A7"> 
				<td height="27" bgcolor="#484848" class="textoBlanco12">&#160;Fecha</td>
				<td height="27" bgcolor="#484848" class="textoBlanco12" align="right">Puntos&#160;&#160;</td>
				<td height="27" bgcolor="#484848" class="textoBlanco12">Motivo</td>
				<td height="27" bgcolor="#484848" class="textoBlanco12">Observaciones</td>
				<td height="27" bgcolor="#484848" class="textoBlanco12">&#160;</td>
				<td height="27" bgcolor="#484848" class="textoBlanco12">&#160;</td>
			</tr>
		</thead>
		<tbody>
			<?php
			$class = "filaBlanco";
			for($i=0;$i<count($listado);$i++) 
			{
				if($listado[$i]["fecha"]!="")
				{ 
					$fp1 = split(" ",$listado[$i]["fecha"]);
					$fp2 = split("-",$fp1[0]);
					$fecha	= $fp2[2]." ".$nombre_mes_abre[floor($fp2[1])]." ".$fp2[0];
					$hora 	= $fp1[1];
				}
				else
				{
					$fecha	= "";
					$hora 	= "";
				}
			?>
			<tr>			
				<td height="30" valign="middle" class="<?php echo $class?>"><?php echo $fecha." ".$hora?></td>
				<td valign="middle" align="right" class="<?php echo $class?>"><?php if($listado[$i]["puntos"]!="") print $listado[$i]["puntos"]."&#160;&#160;"; else print "&#160;";?></td>
				<td valign="middle" class="<?php echo $class?>"><?if($listado[$i]["motivo"]!="") print $listado[$i]["motivo"]; else print "&#160;";?></td>
				<td valign="middle" class="<?php echo $class?>"><?if($listado[$i]["observaciones"]!="") print $listado[$i]["observaciones"]; else print "&#160;";?></td>
				<td valign="middle" class="<?php echo $class?>" align="center">
					<a href='#' onclick="showEditForm('<?php echo $listado[$i]["identrada"]?>');">
						<img src="../../img/btn_edit_on.gif" alt="Editar datos del movimiento" title="Editar datos del movimiento" width="18" height="18" border="0" />
					</a>
				</td>			
				<td valign="middle" class="<?php echo $class?>" align="center">
					<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]["identrada"]?>');">
						<img src="../../img/btn_eliminar_on.gif" alt="Eliminar movimiento" title="Eliminar movimiento" width="18" height="18" border="0" />
					</a>
				</td>			
			</tr>
			<?php 
				if($class=="filaBlanco") $class="filaceleste"; else $class="filaBlanco";
			}
			?>		
		</tbody>
	</table>
	</tables>
<?php }?>