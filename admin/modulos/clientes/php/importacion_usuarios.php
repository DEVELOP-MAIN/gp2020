<?php
//includes
set_time_limit(500);
//Report all errors
//error_reporting(E_ALL);
require_once '../../../php/class/class.socio.php';
require_once '../../../php/generales.php';

$hoy 	= date('d/m/Y');
$d 		= date('d');
$nd 	= date('N');
$m 		= date('m');
$m 		= $m + 0;
$anio	= date('Y');

//declaracion de variables
$arr_materiales_excel = array();
$tamanio = count($arr_materiales_excel);
$situacion = '1';

if(isset($_POST['proceso']) && $_POST['proceso']=='S'){
	if(isset($_FILES['frm_alta_csv']) && $_FILES['frm_alta_csv']['tmp_name']!='')	{
		$fname = date('d-m-Y_H.i.s').'_'.$_FILES['frm_alta_csv']['name'];
		move_uploaded_file($_FILES['frm_alta_csv']['tmp_name'], 'csv/'.$fname);
		$row 	= 0;

		$file = fopen('csv/'.$fname, 'r') or exit('No se pudo abrir el archivo!');
		$nombre_archivo = $fname;

		//salteo la primer fila.
		$data = fgets($file);
		
		$indice_anterior = -1;
		$log_acciones = '';
		$log_file = '';
		$clnt = new socio();

		while(!feof($file)){
			$data = fgets($file);
			if(strlen($data)>20){
				$row++;
				$indice = $row;
				//debo hacer un split
				$_arr_content = preg_split('/;/', $data);
				if(count($_arr_content)>=14) {
					$CODIGO_CLIENTE		= sanear_string(trim($_arr_content[0]));
					$CODIGO_UNICO		= sanear_string(trim($_arr_content[1]));
					$CODIGO_GRUPO		= sanear_string(trim($_arr_content[2]));
					$RAZON_SOCIAL		= sanear_string(trim($_arr_content[3]));
					$NOMBRE				= sanear_string(trim($_arr_content[4]));
					$APELLIDO			= sanear_string(trim($_arr_content[5]));
					$CLAVE				= sanear_string(trim($_arr_content[6]));
					$EMAIL				= sanear_string(trim($_arr_content[7]));
					$CUIT				= sanear_string(trim($_arr_content[8]));
					$DOMICILIO			= sanear_string(trim($_arr_content[9]));
					$DOMICILIO_LOCALIDAD= sanear_string(trim($_arr_content[10]));
					$DOMICILIO_PROVINCIA= sanear_string(trim($_arr_content[11]));
					$DOMICILIO_CP		= sanear_string(trim($_arr_content[12]));
					$TEL_MOVIL			= sanear_string(trim($_arr_content[13]));
					$TEL_OTRO			= sanear_string(trim($_arr_content[14]));

					//verifico que no exista ya un supermercado con el mismo CODIGO UNICO
					if($CODIGO_UNICO!='' && strlen($CODIGO_UNICO)<15) 
					{
						$adv = '';
						if($CODIGO_CLIENTE == '') $adv.= '<font color="RED"> | FALTA EL CODIGO DE CLIENTE </font>';
						if($CODIGO_UNICO == '') $adv.= '<font color="RED"> | FALTA EL CODIGO UNICO </font>';
						if($CUIT == '') $adv.= '<font color="RED"> | FALTA EL CUIT </font>';
						
						if(!$clnt->select_X_codigo_unico($CODIGO_UNICO)) 
						{
							$clnt->setCodigo_cliente($CODIGO_CLIENTE);
							$clnt->setCodigo_unico($CODIGO_UNICO);
							$clnt->setIdgrupo($CODIGO_GRUPO);
							$clnt->setEstado('P');
							$clnt->setRazon_social($RAZON_SOCIAL);
							$clnt->setNombre($NOMBRE);
							$clnt->setApellido($APELLIDO);
							$clnt->setClave($CLAVE);
							$clnt->setEmail($EMAIL);
							$clnt->setCuit($CUIT);
							$clnt->setDomicilio($DOMICILIO);
							$clnt->setDomicilio_localidad($DOMICILIO_LOCALIDAD);
							$clnt->setDomicilio_provincia($DOMICILIO_PROVINCIA);
							$clnt->setDomicilio_cp($DOMICILIO_CP);
							$clnt->setTel_movil($TEL_MOVIL);
							$clnt->setTel_otro($TEL_OTRO);
							$clnt->setAcepta_basesycond('1');

							if($clnt->insert()) 
							{
								$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' INSERTADO CLIENTE: '.$RAZON_SOCIAL.' CUIT '.$CUIT.' '.$adv.'</strong></font><br />';
								$log_file .= '- FILA '.$indice.' INSERTADO CLIENTE: '.$RAZON_SOCIAL.' CUIT '.$CUIT.' '.$adv.PHP_EOL;
							}
							else
							{
								$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
								$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
							}
						} 
						else
						{
							$clnt->setCodigo_cliente($CODIGO_CLIENTE);
							$clnt->setIdgrupo($CODIGO_GRUPO);
							$clnt->setRazon_social($RAZON_SOCIAL);							
							$clnt->setNombre($NOMBRE);
							$clnt->setApellido($APELLIDO);
							$clnt->setClave($CLAVE);
							$clnt->setEmail($EMAIL);
							$clnt->setCuit($CUIT);
							$clnt->setDomicilio($DOMICILIO);
							$clnt->setDomicilio_localidad($DOMICILIO_LOCALIDAD);
							$clnt->setDomicilio_provincia($DOMICILIO_PROVINCIA);
							$clnt->setDomicilio_cp($DOMICILIO_CP);
							$clnt->setTel_movil($TEL_MOVIL);
							$clnt->setTel_otro($TEL_OTRO);
							
							if($clnt->update($clnt->getIdcliente()))
							{	
								$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' REEMPLAZADA - CODIGO UNICO '.$CODIGO_UNICO.' PREEXISTENTE EN LA BASE DE DATOS - DATOS ACTUALIZADOS '.$adv.'</strong></font><br />';
								$log_file .= '- FILA '.$indice.' REEMPLAZADA - CODIGO UNICO '.$CODIGO_UNICO.' PREEXISTENTE EN LA BASE DE DATOS - DATOS ACTUALIZADOS '.$adv.PHP_EOL;
							}	
						}
					} 
					else 
					{
						$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' DESCARTADA - CODIGO UNICO '.$CODIGO_UNICO.' VACIO O FORMATO INCORRECTO</strong></font><br />';
						$log_file .= '- FILA '.$indice.' DESCARTADA - CODIGO UNICO '.$CODIGO_UNICO.' VACIO O FORMATO INCORRECTO'.PHP_EOL;
					}						
						
				}
			}
		}
		file_put_contents('logs/log_'.date('Y-m-d').'.txt', $log_file, FILE_APPEND);
		$situacion = '3';		
		fclose($file);
	}
	$tamanio = count($arr_materiales_excel);	
}

//CUANDO CONFIRMA PROCESO TODO EL FORM
/*if(isset($_POST['enviar']) && $_POST['enviar']=='IMPORTAR')
{
	$indice_anterior = -1;
	$log_acciones = '';
	$log_file = '';
	$clnt = new cliente();

	foreach($_POST as $nombre_campo => $valor)
	{
		//reseteo los valores
		$clnt->resetea();
		//tomo el numerito
		$arr_campo = preg_split('/-/',$nombre_campo);
		$indice = $arr_campo[0];
		if($indice != $indice_anterior)
		{
			$indice_anterior = $indice;
			if(isset($_POST[$indice.'-chequeado']) && $_POST[$indice.'-chequeado']=='S')
			{
				//tomo los datos
				$CODIGO_CLIENTE						= $_POST[$indice.'-CODIGO_CLIENTE'];
				$CODIGO_UNICO							= $_POST[$indice.'-CODIGO_UNICO'];
				$RAZON_SOCIAL								= $_POST[$indice.'-RAZON_SOCIAL'];
				$NOMBRE														= $_POST[$indice.'-NOMBRE'];
				$APELLIDO												= $_POST[$indice.'-APELLIDO'];
				$CLAVE															= $_POST[$indice.'-CLAVE'];
				$EMAIL															= $_POST[$indice.'-EMAIL'];
				$CUIT																= $_POST[$indice.'-CUIT'];
				$DOMICILIO											= $_POST[$indice.'-DOMICILIO'];
				$DOMICILIO_LOCALIDAD	= $_POST[$indice.'-DOMICILIO_LOCALIDAD'];
				$DOMICILIO_PROVINCIA		= $_POST[$indice.'-DOMICILIO_PROVINCIA'];
				$DOMICILIO_CP								= $_POST[$indice.'-DOMICILIO_CP'];
				$TEL_MOVIL											= $_POST[$indice.'-TEL_MOVIL'];
				$TEL_OTRO												= $_POST[$indice.'-TEL_OTRO'];
				
				$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
				$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

				//verifico que no exista ya un supermercado con el mismo CODIGO UNICO
				if($CODIGO_UNICO!='' && strlen($CODIGO_UNICO)<15) 
				{
					$adv = '';
					if($CODIGO_CLIENTE == '') $adv.= '<font color="RED"> | FALTA EL CODIGO DE CLIENTE </font>';
					if($CODIGO_UNICO == '') $adv.= '<font color="RED"> | FALTA EL CODIGO UNICO </font>';
					if($CUIT == '') $adv.= '<font color="RED"> | FALTA EL CUIT </font>';
					
					if(!$clnt->select_X_codigo_unico($CODIGO_UNICO)) 
					{
						$clnt->setCodigo_cliente($CODIGO_CLIENTE);
						$clnt->setCodigo_unico($CODIGO_UNICO);
						$clnt->setEstado('P');
						$clnt->setRazon_social($RAZON_SOCIAL);
						$clnt->setNombre($NOMBRE);
						$clnt->setApellido($APELLIDO);
						$clnt->setClave($CLAVE);
						$clnt->setEmail($EMAIL);
						$clnt->setCuit($CUIT);
						$clnt->setDomicilio($DOMICILIO);
						$clnt->setDomicilio_localidad($DOMICILIO_LOCALIDAD);
						$clnt->setDomicilio_provincia($DOMICILIO_PROVINCIA);
						$clnt->setDomicilio_cp($DOMICILIO_CP);
						$clnt->setTel_movil($TEL_MOVIL);
						$clnt->setTel_otro($TEL_OTRO);
						$clnt->setAcepta_basesycond('1');

						if($clnt->insert()) 
						{
							$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' INSERTADO CLIENTE: '.$RAZON_SOCIAL.' CUIT '.$CUIT.' '.$adv.'</strong></font><br />';
							$log_file .= '- FILA '.$indice.' INSERTADO CLIENTE: '.$RAZON_SOCIAL.' CUIT '.$CUIT.' '.$adv.PHP_EOL;
						}
						else
						{
							$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
							$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
						}
					} 
					else
					{
						$clnt->setCodigo_cliente($CODIGO_CLIENTE);
						$clnt->setRazon_social($RAZON_SOCIAL);
						$clnt->setNombre($NOMBRE);
						$clnt->setApellido($APELLIDO);
						$clnt->setClave($CLAVE);
						$clnt->setEmail($EMAIL);
						$clnt->setCuit($CUIT);
						$clnt->setDomicilio($DOMICILIO);
						$clnt->setDomicilio_localidad($DOMICILIO_LOCALIDAD);
						$clnt->setDomicilio_provincia($DOMICILIO_PROVINCIA);
						$clnt->setDomicilio_cp($DOMICILIO_CP);
						$clnt->setTel_movil($TEL_MOVIL);
						$clnt->setTel_otro($TEL_OTRO);
						
						if($clnt->update($clnt->getIdcliente()))
						{	
							$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' REEMPLAZADA - CODIGO UNICO '.$CODIGO_UNICO.' PREEXISTENTE EN LA BASE DE DATOS - DATOS ACTUALIZADOS '.$adv.'</strong></font><br />';
							$log_file .= '- FILA '.$indice.' REEMPLAZADA - CODIGO UNICO '.$CODIGO_UNICO.' PREEXISTENTE EN LA BASE DE DATOS - DATOS ACTUALIZADOS '.$adv.PHP_EOL;
						}	
					}
				} 
				else 
				{
					$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' DESCARTADA - CODIGO UNICO '.$CODIGO_UNICO.' VACIO O FORMATO INCORRECTO</strong></font><br />';
					$log_file .= '- FILA '.$indice.' DESCARTADA - CODIGO UNICO '.$CODIGO_UNICO.' VACIO O FORMATO INCORRECTO'.PHP_EOL;
				}
			}
		}
	}
	file_put_contents('logs/log_'.date('Y-m-d').'.txt', $log_file, FILE_APPEND);
	$situacion = '3';
}*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Grand Prix 2020 | PEÑAFLOR</title>
<style type="text/css">
<!--
body {
	background-color: #1d1d1d;
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
				<td height="100" align="center" valign="middle">
       <img src="../../../img/logo_gp2020.svg" alt="GP 2020"/>
			</td>
			</tr>
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
												<td height="25" valign="bottom" class="textoMarron14sinhover"><strong>IMPORTAR ARCHIVO DE SOCIOS</strong></td>
												<td height="25" valign="bottom" align="right" class="textNegro14"><!--<a href="#" class="textoMarron12sinhover" onclick="parent.cerrarframe();"><span class="textoMarron12sinhover"><strong>[X]</strong></span></a>--></td>
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
																	 <strong>seleccione el archivo (.csv)</strong><BR>
																	 <input name="frm_alta_csv" type="file" class="inputImport" id="frm_alta_csv" size="40">
																	</td>
																</tr>
																<tr>
																	<td height="48" align="center" bgcolor="#e30613" style="border-top:solid 1px #999999">
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
																<td>Desde este modulo se procesa el archivo de socios. <strong>La primer fila del archivo sera ignorada dado que se espera contenga los nombres de cada columna.</strong><br /><br />El mismo debe contener la informaci&#243;n de los socios bajo una estructura determinada. Cada l&#237;nea deber&#225; tener los siguientes campos siempre separados por <strong>punto y coma</strong>.<br /><br /> <span style="display:block; border:solid 1px #333; padding:15px"><strong>FORMATO:</strong>CODIGO CLIENTE; CODIGO UNICO; CODIGO GRUPO; RAZON SOCIAL; NOMBRE; APELLIDO; CLAVE; EMAIL; CUIT; DOMICILIO; LOCALIDAD; PROVINCIA; C&#211;DIGO POSTAL; TEL&#201;FONO MOVIL; OTRO TEL&#201;FONO</span></td>
															</tr>
														</table>
                          </div>
													<?php }?>

													<?php if($situacion == '2'){?>
													<form name="formdatos" id="formdatos" action="importacion_sups.php" method="POST">
														<table width="100%" border="1" cellpadding="5" cellspacing="5" style="border: 1px solid black; font-size:12px; border-collapse: collapse;">
															<tr>
																<td><span></span></td>
																<td><span>CODIGO CLIENTE</span></td>
																<td><span>CODIGO UNICO</span></td>
																<td><span>CODIGO GRUPO</span></td>
																<td><span>RAZON SOCIAL</span></td>
																<td><span>NOMBRE</span></td>
																<td><span>APELLIDO</span></td>
																<td><span>CLAVE</span></td>
																<td><span>EMAIL</span></td>
																<td><span>CUIT</span></td>
																<td><span>DOMICILIO</span></td>
																<td><span>LOCALIDAD</span></td>
																<td><span>PROVINCIA</span></td>
																<td><span>CP</span></td>
																<td><span>TEL_MOVIL</span></td>
																<td><span>TEL_OTRO</span></td>
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
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['CODIGO_GRUPO'];?>" name="<?php echo $i?>-CODIGO_GRUPO">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['RAZON_SOCIAL'];?>" name="<?php echo $i?>-RAZON_SOCIAL">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['NOMBRE'];?>" name="<?php echo $i?>-NOMBRE">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['APELLIDO'];?>" name="<?php echo $i?>-APELLIDO">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['CLAVE'];?>" name="<?php echo $i?>-CLAVE">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['EMAIL'];?>" name="<?php echo $i?>-EMAIL">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['CUIT'];?>" name="<?php echo $i?>-CUIT">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['DOMICILIO'];?>" name="<?php echo $i?>-DOMICILIO">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['DOMICILIO_LOCALIDAD'];?>" name="<?php echo $i?>-DOMICILIO_LOCALIDAD">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['DOMICILIO_PROVINCIA'];?>" name="<?php echo $i?>-DOMICILIO_PROVINCIA">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['DOMICILIO_CP'];?>" name="<?php echo $i?>-DOMICILIO_CP">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['TEL_MOVIL'];?>" name="<?php echo $i?>-TEL_MOVIL">
																	<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['TEL_OTRO'];?>" name="<?php echo $i?>-TEL_OTRO">
																	<input type="checkbox" id="" value="S" name="<?php echo $i?>-chequeado" checked>
																	<input type="hidden" value="<?php echo $nombre_archivo;?>" name="nombre_archivo">
																</td>
																<td><?php echo $arr_materiales_excel[$i]['CODIGO_CLIENTE'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['CODIGO_UNICO'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['CODIGO_GRUPO'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['RAZON_SOCIAL'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['NOMBRE'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['APELLIDO'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['CLAVE'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['EMAIL'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['CUIT'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['DOMICILIO'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['DOMICILIO_LOCALIDAD'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['DOMICILIO_PROVINCIA'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['DOMICILIO_CP'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['TEL_MOVIL'];?></td>
																<td><?php echo $arr_materiales_excel[$i]['TEL_OTRO'];?></td>
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