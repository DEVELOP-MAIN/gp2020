<?php
//includes
set_time_limit(500);
require_once '../../../php/class/class.codigo.php';
require_once '../../../php/generales.php';
require_once '../../../php/seguridad.php';

//declaracion de variables
$arr_codigos_excel = array();
$situacion = '1';
$error = '';
$inserciones_count = 0;

if(isset($_GET['idp'])) $idp = validaVars($_GET['idp']);

if(isset($_POST['proceso']) && $_POST['proceso']=='S')
{
	if(isset($_FILES['frm_alta_csv']) && $_FILES['frm_alta_csv']['tmp_name']!='')
	{
		$fname = strtolower($_FILES['frm_alta_csv']['name']);

		if (substr($fname, -4) == '.csv' || substr($fname, -4) == '.xls' || substr($fname, -4) == '.txt')
		{
			$ext = substr(strrchr($fname, '.'), 0);
			$nombrearchivo = date('ymdHis').'_'.pongoCeros($idp, 4).'_'.$fname;
			if($ext == '.csv' || $ext == '.txt' || $ext == '.xls') {
				move_uploaded_file($_FILES['frm_alta_csv']['tmp_name'], 'csv/'.$nombrearchivo);
			}
			$row 	= 0;

			if (($handle = fopen('csv/'.$nombrearchivo, 'r')) !== FALSE)
			{
				$separador = ';';
				$encerrado = '"';

				if(isset($_POST['separador']) && $_POST['separador']!=''){
					$separador = $_POST['separador'];
				}

				if(isset($_POST['encerrado']) && $_POST['encerrado']!=''){
					$encerrado = $_POST['encerrado'];
				}

				if(isset($_POST['linea']) && $_POST['linea']=='N'){
					$data = fgetcsv($handle, 1000, $separador, $encerrado);
				}

				if(isset($_POST['comunidad']) && $_POST['comunidad']!=''){
					$comunidad = $_POST['comunidad'];
				}

			  $cdg = new codigo();
			  $log_acciones = '';
				while (($data = fgetcsv($handle,1000, $separador, $encerrado)) !== FALSE)
				{
			  	$num = count($data);
					$row++;
					//el resto se toma del archivo
					$CODIGO	= validaVars(substr($data[0], 0, 255));

					//me fijo si existe el codigo
					if(trim($CODIGO)!='')
					{		
						if($cdg->validaInsert($idp,$CODIGO))
							$log_acciones .= '<div class="reporte">C&#243;digo $CODIGO <font color="RED">- DESCARTADO PORQUE YA EXISTE PARA ESTE PREMIO - </font></div>';
						else
						{
							$cdg->setIdpremio($idp);
							$cdg->setCodigo($CODIGO);
							$cdg->insert();
							$inserciones_count ++;
						}
					}	
				}
				$log_acciones .= '<div class="reporte"><strong> - OK | Se agregaron $inserciones_count</strong></div>';
				fclose($handle);
				$situacion = '3';
			}
		}
		else
		{
			$situacion = '1';
			$error = 'El archivo que intentas subir no cumple con el formato necesario';
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cablevision - Panel de control</title>
<style type="text/css">
<!--
body {
	background-color: #FFF;
	margin:0px;
	padding:10px;
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
}
.salir {
	display:block;
	font-size:18px;
	font-weight: bold;
	width:25px;
	height:25px;
	color:#000;
	text-decoration:none;
	float:right
}
.ayuda {
	width:50%;
	height:310px;
	background: #E3E3E3;
	padding-top:8px;
	margin-top:8px;
	float:right
}
.ayuda li{
	margin-bottom:10px;
}
.fila{
	font-size:11px;
	color: #333333;
	padding-top:3px;
	padding-bottom:3px;
	border-bottom: solid 1px #E6E6E6;
}
.encabezado{
	font-size:11px;
	color: #000;
	font-weight:bold;
	padding-top:3px;
	padding-bottom:3px;
	border-bottom: solid 1px #E6E6E6;
}
.reporte{
	width:100%;
	font-size:11px;
	/*color: #333333;*/
	padding-top:3px;
	padding-bottom:3px;
	border-bottom: solid 1px #E6E6E6;
}
-->
</style>
<link href="../../../css/listados.css" rel="stylesheet" type="text/css" />
<link href="../../../css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../../css/forms.css" rel="stylesheet" type="text/css" />
<link href="../../../css/protoload.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../../js/config.js"></script>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/protoload.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/sortable.js"></script>
<script type="text/javascript" src="../../../js/validation.js"></script>
<script type="text/javascript" src="../../../js/fabtabulous.js"></script>
<script type="text/javascript" src="../../../js/datepicker.js"></script>
<script type="text/javascript" src="../../../js/datefunctions.js"></script>
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
-->
</script>
</head>

<body>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td height="35" valign="middle" bgcolor="#ffcc18">
				<div style="float:left; color: #FFF; padding-top:4px; padding-left:5px">
					<strong>IMPORTAR CODIGOS DESDE UN ARCHIVO CSV</strong>
				</div>
				<a href="#" onclick="window.opener.buscar();window.close();" class="salir" style="color: #FFF;">[X]</a>
			</td>
		</tr>
		<tr>
			<td height="450" valign="top">
				<?php
				if($situacion == '1')
				{
				?>
				<div style="float:left; width:45%; padding-top:8px">
					<!-- MUESTRO EL FORM -->
					<form id="frm_alta" name="frm_alta" method="POST" enctype='multipart/form-data'>
						<input type="hidden" name="proceso" value="S">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td height="55">
									<strong>Seleccione un fichero de extension .csv, .xls &#243; .txt</strong><BR>
									<input name="frm_alta_csv" type="file" class="inputImport" id="frm_alta_csv" size="40">
								</td>
							</tr>
							<tr>
								<td height="30" valign="top" class="textAzul12">
									<input id="separador" name="separador" type="text" value=";" size="2" maxlength="3" class="inputImport">&#160;separador
								</td>
							</tr>
							<tr>
								<td height="30" valign="top" class="textAzul12">
									<input id="encerrado" name="encerrado" type="text" value='"' size="2" maxlength="3" class="inputImport">&#160;calificador de texto<BR>
								</td>
							</tr>
							<tr>
								<td height="55" valign="top" class="textAzul12">
									<select id="linea" name="linea" type="text" class="inputImport" style="width:97%; margin-bottom:5px">
										<option value="S">Procesar la primer linea</option>
										<option value="N" selected>Saltear la primer linea</option>
									</select>
									<BR />(La primer l&#237;nea podr&#237;a contener los nombres de las columnas)
								</td>
							</tr>
							<tr>
								<td height="48" align="center" bgcolor="#E3E3E3" style="border-top:solid 1px #999999">
									<input name="procesar" type="submit" class="boton" id="procesar" value="Importar" />
									<p style="color: red; font-size: 13px;" class="errorclave"><?php if (strlen($error) > 1){echo $error;}?></p>
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div class="ayuda">
					<strong>&#160;&#160;&#160;&#160;Instrucciones</strong>
					<ul>
						<li>Completar la planilla</li>
						<li>Puedes descargar un ejemplo de la planilla <a href="csv/ejemplo/ejemplo.xls" style="text-decoration:none;">aqu&#237;</a>.</li>
						<li>Rellenar la planilla con los datos.</li>
						<li>Los campos codigo deben ser cargados con opciones v&#225;lidas.</li>
						<li>Guardar la planilla en formato csv, datos separados por coma</li>
						<li>Click en examinar y seleccionar el fichero .csv que ha preparado</li>
						<li>El sistema procesara el archivo y le mostrara en pantalla la informacion a insertar</li>
						<li>Luego usted podra seleccionar que registros incluir y cuales dejar fuera de la importaci&#243;n</li>
						<li>Haga click en el bot&#243;n importar para finalizar el proceso.</li>
					</ul>
				</div>
				<?php
				}
				?>

				<?php
				if($situacion == '2')
				{
				?>
				<div style="width:100%; height:602px; overflow-y:scroll; margin-bottom:7px; margin-top:7px">
					<form name="formdatos" id="formdatos" action="importacion.php?idp=<?php echo $idp?>" method="POST">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="impTabla">
							<tr>
								<td class="encabezado"><span>OK</span></td>
								<td class="encabezado"><span>C&#211;DIGO</span></td>
							</tr>
							<!--LA PANTALLA DE PREVIEW DE CARGA-->
							<?php
							$nro = count($arr_codigos_excel);
							for($i=0;$i<$nro;$i++)
							{
							?>
							<tr>
								<td class="fila">
									<input type="hidden" value="<?php echo $arr_codigos_excel[$i]["CODIGO"];?>" name="<?php echo $i?>-CODIGO">
									<input type="checkbox" id="" value="S" name="<?php echo $i?>-chequeado" checked>
								</td>
								<td class="fila"><?php echo $arr_codigos_excel[$i]["CODIGO"];?></td>
							</tr>
							<?php
							}
							?>
						</table>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#E3E3E3">
							<tr>
								<td height="27" align="center" valign="middle">
									<input type="submit" name="enviar" value="IMPORTAR" class="boton">
									(Se importar&#225;n solo los que esten chequeados)
								</td>
							</tr>
						</table>
					</form>
				</div>
				<?php
				}
				?>

				<?php
				if($situacion == '3')
				{
				?>
				<!-- LA PANTALLA DE LOS DATOS INSERTADOS-->
				<div style="width:100%; height:432px; overflow-y:scroll; margin-bottom:7px; margin-top:7px">
					<div class="reporte"><strong>Reporte de la importaci&#243;n</strong></div>
					<?php echo $log_acciones;?>
					<input onclick="window.opener.buscar();window.close();" name="cerrar" type="submit" class="boton" id="cerrar" value="cerrar" />
				</div>
				<?php
				}
				?>
			</td>
		</tr>
	</table>
</body>
</html>
