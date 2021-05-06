<?php
require_once '../../../php/class/class.listado.php';
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_GET = decode($_GET);

header('Content-type:text/html;charset="utf-8"');

if(!isset($_GET['pag'])||$_GET['pag']=='') 	$pag = 0; 		else	$pag = $_GET['pag'];
if(!isset($_GET['cant'])||$_GET['cant']=='')	$cant = 50;	else	$cant = $_GET['cant'];	

//verifico que vengan los parametros
if(!isset($_GET['d']) || !isset($_GET['h']) || !isset($_GET['c'])) {echoErrorXML(65, 'Faltan parametros para este modulo');exit;}
$desde 				= validaVars($_GET['d']);
$hasta 				= validaVars($_GET['h']);
$idconcurso	= validaVars($_GET['c']);

//traigo el listado de participantes al concurso seleccionado
$ls = new listado($pag,$cant);
$listado = $ls->getParticipantesConcurso($desde,$hasta,$idconcurso);
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
		<col style="width:12%;"/>
		<col style="width:20%;"/>	
		<col style="width:15%;"/>	
		<col style="width:15%;"/>	
		<col style="width:10%;"/>	
		<col style="width:15%;"/>	
		<col style="width:12%;"/>	
		<thead>
			<tr bgcolor="#0082A7"> 
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">&#160;Fecha Inscripci&#243;n</td>	  
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Raz&#243;n Social</td>			
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Titular</td>				  
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Email</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">CUIT</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Domicilio</td>
				<td height="27" bgcolor="#c5c5c5" class="textoBlanco12">Tel&#233;fonos</td>
			</tr>
		</thead>
		<tbody>
			<?php
			$class = 'filaBlanco';
			$clnt = new cliente();
			for($i=0;$i<$nro;$i++) 
			{
				//reseteo las variables del participante
				$razon_social = '';
				$titular = '';
				$email = '';
				$cuit = '';
				$domicilio = '';
				$telefonos = '';
				
				//doy formato a la fecha de inscripcion
				if($listado[$i]['fecha']!='')
				{ 
					$v = preg_split('/-/',$listado[$i]['fecha']);
					$fecha_inscripcion = $v[2].' '.$nombre_mes_abre[floor($v[1])].' '.$v[0];
				}
				else $fecha_inscripcion = '&#160;';
				
				//recupero los datos del supermercado inscripto
				if($clnt->select($listado[$i]['idcliente']))
				{
					$razon_social = $clnt->getRazon_social();
					$titular = $clnt->getNombre().' '.$clnt->getApellido();
					$email = $clnt->getEmail();
					$cuit = $clnt->getCuit();
					$domicilio = $clnt->getDomicilio().'<br />'.$clnt->getDomicilio_localidad().'<br />'.$clnt->getDomicilio_provincia();
					$telefonos = 'M&#243;vil: '.$clnt->getTel_movil().'<br />Fijo: '.$clnt->getTel_otro();
				}
			?>
			<tr>			
				<td valign="top" class="<?php echo $class;?>"><?php echo $fecha_inscripcion;?></td>
				<td valign="top" class="<?php echo $class;?>"><?php echo $razon_social;?></td>
				<td valign="top" class="<?php echo $class;?>"><?php echo $titular;?></td>
				<td valign="top" class="<?php echo $class;?>"><?php echo $email;?></td>
				<td valign="top" class="<?php echo $class;?>"><?php echo $cuit;?></td>
				<td valign="top" class="<?php echo $class;?>"><?php echo $domicilio;?></td>
				<td valign="top" class="<?php echo $class;?>"><?php echo $telefonos;?></td>
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