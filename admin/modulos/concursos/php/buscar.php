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
if(!isset($_GET['b']) || !isset($_GET['d']) || !isset($_GET['h'])) {printErrorXML(65, 'Faltan parametros para este modulo');exit;}

//traigo el listado de concursos existentes
$ls = new listado($pag,$cant);
$listado = $ls->getConcursos($_GET['b'],$_GET['d'],$_GET['h']);
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
	<col style="width:54%;"/>
	<col style="width:18%;"/>
	<col style="width:12%;"/>
	<col style="width:10%;"/>
	<col style="width:3%; text-align:center"/>	
	<col style="width:3%; text-align:center"/>	
	<col style="width:3%; text-align:center"/>	
	<thead>
		<tr bgcolor="#0082A7"> 
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;T&#237;tulo</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Plazo</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Chances m&#237;nimas</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Imagen</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$class='filaBlanco';
		for($i=0;$i<$nro;$i++) 
		{
			//doy formato a la fecha inicial
			if($listado[$i]['fecha_desde']!='')
			{ 
				$v = preg_split('/-/',$listado[$i]['fecha_desde']);
				$fecha_desde = $v[2].$nombre_mes_abre[floor($v[1])].$v[0];
			}
			else
				$fecha_desde = '&#160;';
			//doy formato a la fecha final
			if($listado[$i]['fecha_hasta']!='')
			{ 
				$v = preg_split('/-/',$listado[$i]['fecha_hasta']);
				$fecha_hasta = $v[2].$nombre_mes_abre[floor($v[1])].$v[0];
			}
			else
				$fecha_hasta = '&#160;';
			//recupero el nro de participantes del concurso
			$participantes = $ls->getNroParticipantesConcurso($listado[$i]['idconcurso']);
		?>
		<tr>			
			<td valign="top" class="<?php echo $class?>"><strong><?php if($listado[$i]['titulo']!='') echo $listado[$i]['titulo']; else echo '&#160;';?></strong></td>
			<td valign="top" class="<?php echo $class?>"><?php echo $fecha_desde.' al '.$fecha_hasta;?></td>
			<td valign="top" class="<?php echo $class?>"><?php if($listado[$i]['chances_minimas']!='') echo $listado[$i]['chances_minimas']; else echo '&#160;';?></td>
			<td valign="top" class="<?php echo $class?>">
				<?php if($listado[$i]['imagen']!=''){?>
				<a href="../../../archivos/<?php echo $listado[$i]['imagen'];?>" target="_blank" style="text-decoration: none;">
					<img src="../../../archivos/<?php echo $listado[$i]['imagen'];?>" target="_blank" width="75" border="0">
				</a>
				<?php }?>
			</td>
			<td valign="top" class="<?php echo $class?>" align="center">
				<?php if($participantes > 0){?>
				<a href='../../modulos/consurso_participantes/?idc=<?php echo $listado[$i]['idconcurso']?>&nc=<?php echo $listado[$i]['titulo'];?>' title="ver participantes del concurso" style="text-decoration:none">
					<img src="../../img/participantes.png" alt="ver participantes del concurso" title="ver participantes del concurso" width="31" height="31" border="0" />
				</a>
				<?php } else echo '&#160;';?>
			</td>			
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idconcurso']?>');">
					<img src="../../img/btn_editar.png" alt="Editar concurso" title="Editar concurso" width="31" height="31" border="0" />
				</a>
			</td>			
			<td valign="top" class="<?php echo $class?>" align="center">
				<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idconcurso']?>');">
					<img src="../../img/btn_eliminar.png" alt="Eliminar concurso" title="Eliminar concurso" width="31" height="31" border="0" />
				</a>
			</td>			
		</tr>
		<?php
			if($class=='filaBlanco') $class='filaceleste'; else $class='filaBlanco';
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