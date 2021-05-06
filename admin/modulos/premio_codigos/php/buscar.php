<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') 	$pag = 0; 		else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='')	$cant = 50;	else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['b']) || !isset($_GET['d']) || !isset($_GET['c'])) {echoErrorXML(65, 'Faltan parametros para este modulo');exit;}
$b = validaVars($_GET['b']);
$d = validaVars($_GET['d']);
$c = validaVars($_GET['c']);

//traigo el listado de codigos existentes para este premio
$ls = new listado($pag,$cant);
$listado = $ls->getCodigosPremio($b,$d,$c);
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
<?php } else {?>
	<tables>
		<table id="table-1" cellspacing="0" width="100%">
      <col style="width:59%;"/>
      <col style="width:20%;text-align='center'"/>	
			<col style="width:5%;text-align='center'"/>	
			<col style="width:3%;text-align='center'"/>	
			<col style="width:3%;text-align='center'"/>	
			<thead>
				<tr bgcolor="#0082A7"> 
					<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;C&#243;digo</td>			
					<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Fecha Alta</td>	  
				  <td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Disponible</td>				  
				  <td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
				  <td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;</td>
			  </tr>
			</thead>
			<tbody>
				<?php
				$class = 'filaBlanco';
				for($i=0;$i<$nro;$i++) 
				{
					//doy formato a la fecha de alta
					if($listado[$i]['fechaalta']!='')
					{ 
						$v = preg_split('/-/',$listado[$i]['fechaalta']);
						$fecha_alta = $v[2].' '.$nombre_mes_abre[floor($v[1])].' '.$v[0];
					}
					else
						$fecha_alta = '&#160;';
				?>
				<tr>			
					<td valign="top" class="<?php echo $class?>">
						<?php if($listado[$i]['disponible'] == 1){?>
						<span class="detCodDisp"><?php if($listado[$i]['codigo']!='') echo $listado[$i]['codigo']; else echo '&#160;';?></span>
						<?php }else{?>
						<span class="detCodNodisp"><?php if($listado[$i]['codigo']!='') echo $listado[$i]['codigo']; else echo '&#160;';?></span>
						<?php }?>
					</td>
					<td nowrap valign="top" class="<?php echo $class?>">
						<?php if($listado[$i]['disponible'] == 1){?>
						<span class="detCodDisp"><?php echo $fecha_alta;?></span>
						<?php }else{?>
						<span class="detCodNodisp"><?php echo $fecha_alta;?></span>
						<?php }?>
					</td>
					<td valign="top" class="<?php echo $class?>">
						<?php if($listado[$i]['disponible'] == 1){?>
						<img src="../../img/destacado_si.gif" alt="disponible" title="disponible" width="31" height="31" border="0" />
						<?php }else{?>
						<img src="../../img/destacado_no.gif" alt="utilizado" title="utilizado" width="31" height="31" border="0" />
						<?php }?>
					</td>
					<td valign="top" class="<?php echo $class?>" align="center">
						<?php if($listado[$i]['disponible'] == 1){?>
						<a href='#' onclick="showEditForm('<?php echo $listado[$i]['idcodigo']?>');">
							<img src="../../img/btn_editar.png" alt="Editar codigo" title="Editar codigo" width="31" height="31" border="0" />
						</a>
						<?php }?>
					</td>			
					<td valign="top" class="<?php echo $class?>" align="center">
						<?php if($listado[$i]['disponible'] == 1){?>
						<a href='#' onclick="showDeleteForm('<?php echo $listado[$i]['idcodigo']?>');">
							<img src="../../img/btn_eliminar.png" alt="Eliminar codigo" title="Eliminar codigo" width="31" height="31" border="0" />
						</a>
						<?php }?>
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