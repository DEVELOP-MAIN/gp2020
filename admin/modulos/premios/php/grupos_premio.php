<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//verifico que vengan los parametros
if(!isset($_GET['idpremio'])) {printErrorXML(65, 'Faltan parametros para este modulo');	exit;}
$idpremio = $_GET['idpremio'];

//traigo el listado de grupos existentes en la base y el de grupos asociados al premio pasado como parámetro
$ls = new listado();
$grupos_premio = $ls->getGrupos_Premio($idpremio);
$grupos = $ls->getGruposCombo();
$nro = count($grupos);
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<div style="width:100%; float:left; line-height:15px;">
				<a href="javascript:seleccionar_todo('grupo');" style="text-decoration:none;">[Marcar todos]</a>
				&#160;
				<a href="javascript:deseleccionar_todo('grupo');" style="text-decoration:none;">[Desmarcar todos]</a>
			</div>	
			<?php
			for($i=0;$i<$nro;$i++){
			?>
			<div style="width:33%; float:left; line-height:10px;">
				<?php
				if(estaIncluido($grupos[$i]['idgrupo'])){
				?>
				<input type="checkbox" name="grupo_<?php echo $grupos[$i]['idgrupo'];?>" id="grupo_<?php echo $grupos[$i]['idgrupo'];?>" value="<?php echo $grupos[$i]['idgrupo'];?>" checked />&#160;<?php if(estaIncluido($grupos[$i]['idgrupo'])) echo '<strong>';?><?php echo $grupos[$i]['nombre'];?><?php if(estaIncluido($grupos[$i]['idgrupo'])) echo '</strong>';?>
				<?php
				} else {
				?>
				<input type="checkbox" name="grupo_<?php echo $grupos[$i]['idgrupo'];?>" id="grupo_<?php echo $grupos[$i]['idgrupo'];?>" value="<?php echo $grupos[$i]['idgrupo'];?>" />&#160;<?php echo $grupos[$i]['nombre'];?>
				<?php
				}
				?>
			</div>
			<?php
			}
			?>
		</td>
	</tr>
</table>

<?php
function estaIncluido($id){
	global $grupos_premio;
	$fin = count($grupos_premio);
	for($j=0;$j<$fin;$j++){
		if($grupos_premio[$j]['idgrupo']==$id)
			return true;
	}
	return false;
}
?>