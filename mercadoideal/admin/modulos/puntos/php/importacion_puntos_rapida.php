<?php
//includes
set_time_limit(500);
ini_set('memory_limit', '1024M');
ini_set('post_max_size', '200M');

//Report all errors
//error_reporting(E_ALL);
require_once '../../../php/class/class.cliente.php';
require_once '../../../php/class/class.puntos.php';
require_once '../../../php/generales.php';

//declaracion de variables
$arr_materiales_excel = array();
$tamanio = count($arr_materiales_excel);
$situacion = '1';

if(isset($_POST['proceso']) && $_POST['proceso']=='S')
{
	if(isset($_FILES['frm_alta_csv']) && $_FILES['frm_alta_csv']['tmp_name']!='')
	{
		$fname = date('d-m-Y_H.i.s').'_'.$_FILES['frm_alta_csv']['name'];
		move_uploaded_file($_FILES['frm_alta_csv']['tmp_name'], 'csv/'.$fname);
		$row 	= 0;

		$file = fopen('csv/'.$fname, 'r') or exit('No se pudo abrir el archivo!');
		$nombre_archivo = $fname;

		//salteo la primer fila.
		$data = fgets($file);
		
		$pnts = new puntos();
		$clnt = new cliente();
		
		$log_acciones = '';
		$log_file  = '';
		
		while(!feof($file))
		{
			$data = fgets($file);
			$row++;
			//debo hacer un split
			$_arr_content = preg_split('/;/', $data);
			if(count($_arr_content)>=5) 
			{
				$CODIGO_CLIENTE	=	$_arr_content[0];
				$CODIGO_UNICO	=	$_arr_content[1];
				$PUNTOS			=	trim($_arr_content[2]);
				$FECHA			=	trim($_arr_content[3]);
				$OBSERVACIONES	=	sanear_string(trim($_arr_content[4]));
				$MOTIVO			=	sanear_string(trim($_arr_content[5]));
				
				$indice 		=	$row;
				
				//armo el array completo
				$arr_materiales_excel[$row-1]['CODIGO_CLIENTE']	= $CODIGO_CLIENTE;
				$arr_materiales_excel[$row-1]['CODIGO_UNICO']	= $CODIGO_UNICO;
				$arr_materiales_excel[$row-1]['PUNTOS']			= $PUNTOS;
				$arr_materiales_excel[$row-1]['FECHA'] 			= $FECHA;
				$arr_materiales_excel[$row-1]['OBSERVACIONES']	= $OBSERVACIONES;
				$arr_materiales_excel[$row-1]['MOTIVO'] 		= $MOTIVO;
				
				//agregado pablo para procesamiento al toque
				$idcliente = '';
				if($clnt->select_X_codigo_unico($CODIGO_UNICO)) 
					if($clnt->getEstado()!="P")
						$idcliente = $clnt->getIdcliente(); 					
				
				$IDCLIENTE						= $idcliente;
				
				$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
				$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

				$adv = '';
				if($IDCLIENTE == '') $adv.= '<font color="RED"> | FALTA EL ID DE CLIENTE O EL MISMO NO FUE ECONTRADO X CODIGO UNICO: '.$CODIGO_UNICO.' O NO ESTA ACTIVO</font>';
				if($PUNTOS == '') $adv.= '<font color="RED"> | FALTA LOS PUNTOS A CARGAR </font>';
				
				$pnts->setIdcliente($IDCLIENTE);
				$pnts->setPuntos($PUNTOS);
				$pnts->setFecha($FECHA);
				$pnts->setObservaciones($OBSERVACIONES);
				$pnts->setMotivo($MOTIVO);
				$pnts->setFecha_carga(date('Y-m-d'));
				
				if($pnts->insert()) 
				{
					$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' SE CARGARON: '.$PUNTOS.' PUNTOS al IDCLIENTE:'.$IDCLIENTE.' '.$adv.'</strong></font><br />';
					$log_file .= '- FILA '.$indice.' SE CARGARON: '.$PUNTOS.' PUNTOS '.$adv.PHP_EOL;
				}
				else
				{
					$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
					$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
				}
				
			}
		}
		fclose($file);
	}
	file_put_contents('logs/log_puntos_'.date("Y-m-d").'.txt', $log_file, FILE_APPEND);
	$situacion = '3';
}

//CUANDO CONFIRMA PROCESO TODO EL FORM
if(isset($_POST['enviar']) && $_POST['enviar']=='IMPORTAR')
{
	$indice_anterior = -1;
	$log_acciones = '';
	$log_file = '';
	$pnts = new puntos();
	$clnt = new cliente();
				
	foreach($_POST as $nombre_campo => $valor)
	{
		//reseteo los valores
		$pnts->resetea();
		//tomo el numerito
		$arr_campo = preg_split('/-/',$nombre_campo);
		$indice = $arr_campo[0];
		if($indice != $indice_anterior)
		{
			$indice_anterior = $indice;
			if(isset($_POST[$indice.'-chequeado']) && $_POST[$indice.'-chequeado']=='S')
			{
				//tomo los datos
				//recupero el idcliente a partir de su codigo de cliente
				if($clnt->select_X_codigo_unico($_POST[$indice.'-CODIGO_UNICO'])) $idcliente = $clnt->getIdcliente(); else $idcliente = '';
				$IDCLIENTE						= $idcliente;
				$PUNTOS								= $_POST[$indice.'-PUNTOS'];
				$FECHA									= $_POST[$indice.'-FECHA'];
				$OBSERVACIONES	= $_POST[$indice.'-OBSERVACIONES'];
				$MOTIVO								= $_POST[$indice.'-MOTIVO'];
				
				$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
				$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

				$adv = '';
				if($IDCLIENTE == '') $adv.= '<font color="RED"> | FALTA EL ID DE CLIENTE </font>';
				if($PUNTOS == '') $adv.= '<font color="RED"> | FALTA LOS PUNTOS A CARGAR </font>';
				
				$pnts->setIdcliente($IDCLIENTE);
				$pnts->setPuntos($PUNTOS);
				$pnts->setFecha($FECHA);
				$pnts->setObservaciones($OBSERVACIONES);
				$pnts->setMotivo($MOTIVO);
				$pnts->setFecha_carga(date('Y-m-d'));
				
				if($pnts->insert()) 
				{
					$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' SE CARGARON: '.$PUNTOS.' PUNTOS '.$adv.'</strong></font><br />';
					$log_file .= '- FILA '.$indice.' SE CARGARON: '.$PUNTOS.' PUNTOS '.$adv.PHP_EOL;
				}
				else
				{
					$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
					$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
				}
			}
		}
	}
	file_put_contents('logs/log_puntos_'.date("Y-m-d").'.txt', $log_file, FILE_APPEND);
	$situacion = '3';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MERCADO IDEAL</title>
<style type="text/css">
<!--
body {
	background-color: #D7E5F0;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
<link href="../../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../../css/listados.css" rel="stylesheet" type="text/css" />
<link href="../../css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../css/forms.css" rel="stylesheet" type="text/css" />
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
			<td>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top">
							<table width="99%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="15"></td>
									<td width="100%" class="backTablaSup">&#160;</td>
									<td align="left"></td>
								</tr>
								<tr>
									<td class="backTablaIzq">&#160;</td>
									<td height="150" valign="top" bgcolor="#FFFFFF">
										<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
											<tr>
												<td height="25" valign="bottom" class="textoMarron14sinhover"><strong>ASIGNAR PUNTOS A SUPERMERCADOS ADHERIDOS</strong></td>
												<td height="25" valign="bottom" align="right" class="textNegro14"><a href="#" class="textoMarron12sinhover" onclick="parent.cerrarframe();"><span class="textoMarron12sinhover"><strong>[X]</strong></span></a></td>
										  </tr>
											<tr>
												<td colspan="2" height="20" valign="bottom" class="textCeleste12"></td>
											</tr>
											<tr>
												<td colspan="2" height="450" valign="top">
                          <div style="float:left">
													<?php if($situacion == '1'){?>
													<!-- MUESTRO EL FORM -->
														<form id="frm_alta" name="frm_alta" method="POST" enctype='multipart/form-data'>
															<input type="hidden" id="proceso" name="proceso" value="S">
															<table width="320" border="0" cellpadding="0" cellspacing="0">
																<tr>
																	<td height="55" class="textAzul12" style="border-top:solid 1px #999999">
																	 <strong>seleccione el archivo (.csv)</strong><br />
																	 <input name="frm_alta_csv" type="file" class="inputImport" id="frm_alta_csv" size="40">
																	</td>
																</tr>
																<tr>
																	<td height="48" align="center" bgcolor="#D7E5F0" style="border-top:solid 1px #999999">
																		<input name="procesar" type="submit" class="boton" id="procesar" value="Importar" />
																	</td>
																</tr>
															</table>
														</form>
                          </div>
                          <div class="ayuda">
                          	<table>
															<tr>
																<td>&#160;&#160;&#160;</td>
																<td>&#160;&#160;&#160;</td>
																<td>Desde este modulo se procesa el archivo de puntos para los supermercados adheridos.<br /><strong>La primer fila del archivo sera ignorada dado que se espera contenga los nombres de cada columna.</strong><br />El mismo debe contener la informaci&#243;n de los puntos bajo una estructura determinada. Cada l&#237;nea deber&#225; tener los siguientes campos siempre separados por <strong>punto y coma</strong>.<br /><strong>FORMATO:</strong>CODIGO DE CLIENTE; CODIGO UNICO; PUNTOS; FECHA; OBSERVACIONES; MOTIVO</td>
															</tr>
														</table>
                          </div>
													<?php }?>

													<?php if($situacion == '2'){?>
													<form name="formdatos" id="formdatos" action="importacion_puntos.php" method="POST">
														<table width="100%" border="1" cellpadding="5" cellspacing="5" style="border: 1px solid black; font-size:12px; border-collapse: collapse;">
															<tr>
																<td><span></span></td>
																<td><span>CODIGO DE CLIENTE</span></td>
																<td><span>CODIGO UNICO</span></td>
																<td><span>PUNTOS</span></td>
																<td><span>FECHA</span></td>
																<td><span>OBSERVACIONES</span></td>
																<td><span>MOTIVO</span></td>
															</tr>
															<!-- LA PANTALLA DE PREVIEW DE CARGA-->
															<?php
															for($i=0;$i<$tamanio;$i++)
															{
															?>
															<tr>
																<td>
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['CODIGO_CLIENTE'];?>" name="<?php echo $i?>-CODIGO_CLIENTE">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['CODIGO_UNICO'];?>" name="<?php echo $i?>-CODIGO_UNICO">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['PUNTOS'];?>" name="<?php echo $i?>-PUNTOS">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['FECHA'];?>" name="<?php echo $i?>-FECHA">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['OBSERVACIONES'];?>" name="<?php echo $i?>-OBSERVACIONES">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['MOTIVO'];?>" name="<?php echo $i?>-MOTIVO">
																	<input type="checkbox" id="" value="S" name="<?php echo $i?>-chequeado" checked>
																	<input type="hidden" value="<?php echo $nombre_archivo;?>" name="nombre_archivo">
																</td>
																<td><?php echo $arr_materiales_excel[$i]['CODIGO_CLIENTE'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['CODIGO_UNICO'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['PUNTOS'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['FECHA'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['OBSERVACIONES'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['MOTIVO'];?></td>
															</tr>
															<?php
															}
															?>
														</table>
														<table width="830" border="0" cellpadding="0" cellspacing="0">
															<tr>
																<td width="220" height="35" align="left" valign="bottom">
																	<input type="submit" name="enviar" value="IMPORTAR" class="boton">
																</td>
															</tr>
														</table>
													</form>
													<?php }?>

													<?php if($situacion == '3'){?>
													<!-- LA PANTALLA DE LOS DATOS INSERTADOS-->
													<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?php echo $log_acciones;?></span>
													<?php }?>
                        </td>
										  </tr>
										</table>
									</td>
									<td class="backTablaDer">&#160;</td>
								</tr>
								<tr>
									<td></td>
									<td class="backTablaPie">&#160;</td>
									<td></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>&#160;</td>
		</tr>
	</table>
</body>
</html>