<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

//traigo el listado de grupos existentes en la base
$ls = new listado();
$grupos = $ls->getGruposCombo();
$fin = count($grupos);
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
			for($i=0;$i<$fin;$i++)
			{
			?>
			<div style="width:33%; float:left; line-height:10px;">
				<input type="checkbox" name="grupo_<?php echo $grupos[$i]['idgrupo'];?>" id="grupo_<?php echo $grupos[$i]['idgrupo'];?>" value="<?php echo $grupos[$i]['idgrupo'];?>" />&#160;<?php echo $grupos[$i]['nombre'];?>
			</div>
			<?php
			}
			?>
		</td>
	</tr>
</table>