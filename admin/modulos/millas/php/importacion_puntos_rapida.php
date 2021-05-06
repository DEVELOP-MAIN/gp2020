<?php
//includes
set_time_limit(500);
ini_set('memory_limit', '1024M');
ini_set('post_max_size', '200M');

//Report all errors
//error_reporting(E_ALL);
require_once '../../../php/class/class.socio.php';
require_once '../../../php/class/class.puntos.php';
require_once '../../../php/generales.php';

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

		$pnts = new puntos();
		$clnt = new socio();

		$log_acciones = '';
		$log_file  = '';

		while(!feof($file)){
			$data = fgets($file);
			$row++;
			//debo hacer un split
			$_arr_content = preg_split('/;/', $data);
			if(count($_arr_content)>=18){
				$tipos_directa = array('directa', 'DIRECTA', 'Directa');
				$tipos_distris = array('distribuidor', 'DISTRIBUIDOR', 'Distribuidor');
				$tipos_repositores = array('repositor', 'REPOSITOR', 'Repositor');
				$tipo_socio_planilla =	sanear_string(trim($_arr_content[2]));
				if (in_array($tipo_socio_planilla, $tipos_directa)){
					$ANIO								=	trim($_arr_content[0]);
					$MES								=	sanear_string(trim($_arr_content[1]));
					$TIPO								=	sanear_string(trim($_arr_content[2]));
					$REGION							=	sanear_string(trim($_arr_content[3]));
					$DIRECTA						=	sanear_string(trim($_arr_content[4]));
					$USUARIO						=	sanear_string(trim($_arr_content[5]));
					$LINEA							=	sanear_string(trim($_arr_content[6]));
					$OBJETIVO						=	trim($_arr_content[7]);
					$AVANCE_SUMA				=	trim($_arr_content[8]);
					$AVANCE_PORCENTAJE	=	trim($_arr_content[9]);
					$EJECUTIVO					=	sanear_string(trim($_arr_content[10]));
					$JEFE								=	sanear_string(trim($_arr_content[11]));
					$GERENTE						=	sanear_string(trim($_arr_content[12]));
					$PESO								=	trim($_arr_content[13]);
					$MILLAS							=	trim($_arr_content[14]);
					$DIRECTA2						=	trim($_arr_content[15]);
					$RANKING_MES				=	trim($_arr_content[16]);
					$RANKING_TOTAL			=	trim($_arr_content[17]);
					$LEGAJO							=	trim($_arr_content[18]);
					
					$AVANCE_PORCENTAJE = number_format(floatval($AVANCE_SUMA)*100/floatval($OBJETIVO), 2);

					$indice =	$row;

					//armo el array completo
					$arr_materiales_excel[$row-1]['ANIO']								= $ANIO;
					$arr_materiales_excel[$row-1]['MES']								= $MES;
					$arr_materiales_excel[$row-1]['TIPO']								= $TIPO;
					$arr_materiales_excel[$row-1]['REGION']							= $REGION;
					$arr_materiales_excel[$row-1]['DIRECTA']						= $DIRECTA;
					$arr_materiales_excel[$row-1]['USUARIO']						= $USUARIO;
					$arr_materiales_excel[$row-1]['LINEA']							= $LINEA;
					$arr_materiales_excel[$row-1]['OBJETIVO']						= $OBJETIVO;
					$arr_materiales_excel[$row-1]['AVANCE_SUMA']				= $AVANCE_SUMA;
					$arr_materiales_excel[$row-1]['AVANCE_PORCENTAJE']	= $AVANCE_PORCENTAJE;
					$arr_materiales_excel[$row-1]['EJECUTIVO']					= $EJECUTIVO;
					$arr_materiales_excel[$row-1]['JEFE']								= $JEFE;
					$arr_materiales_excel[$row-1]['GERENTE']						= $GERENTE;
					$arr_materiales_excel[$row-1]['PESO']								= $PESO;
					$arr_materiales_excel[$row-1]['MILLAS']							= $MILLAS;
					$arr_materiales_excel[$row-1]['DIRECTA2']						= $DIRECTA2;
					$arr_materiales_excel[$row-1]['RANKING_MES']				= $RANKING_MES;
					$arr_materiales_excel[$row-1]['RANKING_TOTAL']			= $RANKING_TOTAL;
					$arr_materiales_excel[$row-1]['LEGAJO']							= $LEGAJO;

					//agregado pablo para procesamiento al toque
					$idcliente = '';
					if($clnt->select_X_codigo($LEGAJO)) $idcliente = $clnt->getIdsocio();

					$IDCLIENTE = $idcliente;

					$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
					$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

					$adv = '';
					if($IDCLIENTE == '') $adv.= '<font color="RED"> | FALTA EL ID DEL SOCIO &#211; NO FUE ENCONTRADO POR EL CODIGO '.$LEGAJO.'</font>';
					if($MILLAS == '') {
						$adv.= '<font color="RED"> | NO SE CONSIGNARON MILLAS</font>';
						$MILLAS = 0;
					}	
					
					$AVANCE_PORCENTAJE = number_format(floatval($AVANCE_SUMA)*100/floatval($OBJETIVO), 2);

					//Como en la planilla figuran el año y el mes en texto debo armar la fecha para ingresar en la tabla
										
					if (strrpos(strtolower($MES), "ene") !== false) {
						$NRO_MES = '01';
					}
					if (strrpos(strtolower($MES), "feb") !== false) {
						$NRO_MES = '02';
					}
					if (strrpos(strtolower($MES), "mar") !== false) {
						$NRO_MES = '03';
					}
					if (strrpos(strtolower($MES), "abr") !== false) {
						$NRO_MES = '04';
					}
					if (strrpos(strtolower($MES), "may") !== false) {
						$NRO_MES = '05';
					}
					if (strrpos(strtolower($MES), "jun") !== false) {
						$NRO_MES = '06';
					}
					if (strrpos(strtolower($MES), "jul") !== false) {
						$NRO_MES = '07';
					}
					if (strrpos(strtolower($MES), "ago") !== false) {
						$NRO_MES = '08';
					}
					if (strrpos(strtolower($MES), "sep") !== false) {
						$NRO_MES = '09';
					}
					if (strrpos(strtolower($MES), "oct") !== false) {
						$NRO_MES = '10';
					}
					if (strrpos(strtolower($MES), "nov") !== false) {
						$NRO_MES = '11';
					}
					if (strrpos(strtolower($MES), "dic") !== false) {
						$NRO_MES = '12';
					}
					
					if (strrpos(strtolower($MES), "enero") !== false) {
						$NRO_MES = '01';
					}
					if (strrpos(strtolower($MES), "febrero") !== false) {
						$NRO_MES = '02';
					}
					if (strrpos(strtolower($MES), "marzo") !== false) {
						$NRO_MES = '03';
					}
					if (strrpos(strtolower($MES), "abril") !== false) {
						$NRO_MES = '04';
					}
					if (strrpos(strtolower($MES), "mayo") !== false) {
						$NRO_MES = '05';
					}
					if (strrpos(strtolower($MES), "junio") !== false) {
						$NRO_MES = '06';
					}
					if (strrpos(strtolower($MES), "julio") !== false) {
						$NRO_MES = '07';
					}
					if (strrpos(strtolower($MES), "agosto") !== false) {
						$NRO_MES = '08';
					}
					if (strrpos(strtolower($MES), "septiembre") !== false) {
						$NRO_MES = '09';
					}
					if (strrpos(strtolower($MES), "octubre") !== false) {
						$NRO_MES = '10';
					}
					if (strrpos(strtolower($MES), "noviembre") !== false) {
						$NRO_MES = '11';
					}
					if (strrpos(strtolower($MES), "diciembre") !== false) {
						$NRO_MES = '12';
					}
					
					$FECHA = $ANIO.'-'.$NRO_MES.'-01';

					$pnts->setIdcliente($IDCLIENTE);
					$pnts->setPuntos($MILLAS);
					$pnts->setFecha($FECHA);
					$pnts->setLinea($LINEA);
					$pnts->setFecha_carga(date('Y-m-d'));
					$pnts->setTipo($TIPO);
					$pnts->setDirecta($DIRECTA);
					$pnts->setRegion($REGION);
					$pnts->setCodigo($LEGAJO);
					$pnts->setUsuario($USUARIO);
					$pnts->setObjetivo($OBJETIVO);
					$pnts->setAvance($AVANCE_SUMA);
					$pnts->setAvance_por($AVANCE_PORCENTAJE);
					$pnts->setEjecutivo($EJECUTIVO);
					$pnts->setJefe($JEFE);
					$pnts->setGerente($GERENTE);
					$pnts->setPeso($PESO);
					$pnts->setMillas_individuales('');
					$pnts->setRanking_mes($RANKING_MES);
					$pnts->setRanking_total($RANKING_TOTAL);

					if($pnts->insert()){
						$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS al IDCLIENTE:'.$IDCLIENTE.' '.$adv.'</strong></font><br />';
						$log_file .= '- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS '.$adv.PHP_EOL;
					}	else {
						$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
						$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
					}
				}

				if (in_array($tipo_socio_planilla, $tipos_distris)){
					$ANIO								=	trim($_arr_content[0]);
					$MES								=	sanear_string(trim($_arr_content[1]));
					$TIPO								=	sanear_string(trim($_arr_content[2]));
					$REGION							=	sanear_string(trim($_arr_content[3]));
					$USUARIO						=	sanear_string(trim($_arr_content[4]));
					$LINEA							=	sanear_string(trim($_arr_content[5]));
					$OBJETIVO						=	trim($_arr_content[6]);
					$AVANCE_SUMA				=	trim($_arr_content[7]);
					$AVANCE_PORCENTAJE	=	trim($_arr_content[8]);
					$EJECUTIVO					=	sanear_string(trim($_arr_content[9]));
					$JEFE								=	sanear_string(trim($_arr_content[10]));
					$GERENTE						=	sanear_string(trim($_arr_content[11]));
					$FDP								=	sanear_string(trim($_arr_content[12]));
					$PESO								=	trim($_arr_content[13]);
					$MILLAS							=	trim($_arr_content[14]);
					$MILLAS_INDIVIDUALES=	trim($_arr_content[15]);
					$RANKING_MES				=	trim($_arr_content[16]);
					$RANKING_TOTAL			=	trim($_arr_content[17]);
					$CODIGO							=	trim($_arr_content[18]);
					
					$AVANCE_PORCENTAJE = number_format(floatval($AVANCE_SUMA)*100/floatval($OBJETIVO), 2);

					$indice =	$row;

					//armo el array completo
					$arr_materiales_excel[$row-1]['ANIO']								= $ANIO;
					$arr_materiales_excel[$row-1]['MES']								= $MES;
					$arr_materiales_excel[$row-1]['TIPO']								= $TIPO;
					$arr_materiales_excel[$row-1]['REGION']							= $REGION;
					$arr_materiales_excel[$row-1]['USUARIO']						= $USUARIO;
					$arr_materiales_excel[$row-1]['LINEA']							= $LINEA;
					$arr_materiales_excel[$row-1]['OBJETIVO']						= $OBJETIVO;
					$arr_materiales_excel[$row-1]['AVANCE_SUMA']				= $AVANCE_SUMA;
					$arr_materiales_excel[$row-1]['AVANCE_PORCENTAJE']	= $AVANCE_PORCENTAJE;
					$arr_materiales_excel[$row-1]['EJECUTIVO']					= $EJECUTIVO;
					$arr_materiales_excel[$row-1]['JEFE']								= $JEFE;
					$arr_materiales_excel[$row-1]['GERENTE']						= $GERENTE;
					$arr_materiales_excel[$row-1]['FDP']								= $FDP;
					$arr_materiales_excel[$row-1]['PESO']								= $PESO;
					$arr_materiales_excel[$row-1]['MILLAS']							= $MILLAS;
					$arr_materiales_excel[$row-1]['MILLAS_INDIVIDUALES']= $MILLAS_INDIVIDUALES;
					$arr_materiales_excel[$row-1]['RANKING_MES']				= $RANKING_MES;
					$arr_materiales_excel[$row-1]['RANKING_TOTAL']			= $RANKING_TOTAL;
					$arr_materiales_excel[$row-1]['CODIGO']							= $CODIGO;

					//agregado pablo para procesamiento al toque
					$idcliente = '';
					if($clnt->select_X_codigo($CODIGO)) $idcliente = $clnt->getIdsocio();

					$IDCLIENTE = $idcliente;

					$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
					$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

					$adv = '';
					if($IDCLIENTE == '') $adv.= '<font color="RED"> | FALTA EL ID DEL SOCIO &#211; NO FUE ENCONTRADO POR EL CODIGO '.$CODIGO.'</font>';
					if($MILLAS == '') $adv.= '<font color="RED"> | FALTA EL VALOR DE MILLAS A CARGAR</font>';

					if (strrpos(strtolower($MES), "ene") !== false) {
						$NRO_MES = '01';
					}
					if (strrpos(strtolower($MES), "feb") !== false) {
						$NRO_MES = '02';
					}
					if (strrpos(strtolower($MES), "mar") !== false) {
						$NRO_MES = '03';
					}
					if (strrpos(strtolower($MES), "abr") !== false) {
						$NRO_MES = '04';
					}
					if (strrpos(strtolower($MES), "may") !== false) {
						$NRO_MES = '05';
					}
					if (strrpos(strtolower($MES), "jun") !== false) {
						$NRO_MES = '06';
					}
					if (strrpos(strtolower($MES), "jul") !== false) {
						$NRO_MES = '07';
					}
					if (strrpos(strtolower($MES), "ago") !== false) {
						$NRO_MES = '08';
					}
					if (strrpos(strtolower($MES), "sep") !== false) {
						$NRO_MES = '09';
					}
					if (strrpos(strtolower($MES), "oct") !== false) {
						$NRO_MES = '10';
					}
					if (strrpos(strtolower($MES), "nov") !== false) {
						$NRO_MES = '11';
					}
					if (strrpos(strtolower($MES), "dic") !== false) {
						$NRO_MES = '12';
					}
					
					if (strrpos(strtolower($MES), "enero") !== false) {
						$NRO_MES = '01';
					}
					if (strrpos(strtolower($MES), "febrero") !== false) {
						$NRO_MES = '02';
					}
					if (strrpos(strtolower($MES), "marzo") !== false) {
						$NRO_MES = '03';
					}
					if (strrpos(strtolower($MES), "abril") !== false) {
						$NRO_MES = '04';
					}
					if (strrpos(strtolower($MES), "mayo") !== false) {
						$NRO_MES = '05';
					}
					if (strrpos(strtolower($MES), "junio") !== false) {
						$NRO_MES = '06';
					}
					if (strrpos(strtolower($MES), "julio") !== false) {
						$NRO_MES = '07';
					}
					if (strrpos(strtolower($MES), "agosto") !== false) {
						$NRO_MES = '08';
					}
					if (strrpos(strtolower($MES), "septiembre") !== false) {
						$NRO_MES = '09';
					}
					if (strrpos(strtolower($MES), "octubre") !== false) {
						$NRO_MES = '10';
					}
					if (strrpos(strtolower($MES), "noviembre") !== false) {
						$NRO_MES = '11';
					}
					if (strrpos(strtolower($MES), "diciembre") !== false) {
						$NRO_MES = '12';
					}
					
					$FECHA = $ANIO.'-'.$NRO_MES.'-01';

					$pnts->setIdcliente($IDCLIENTE);
					$pnts->setPuntos($MILLAS);
					$pnts->setFecha($FECHA);
					$pnts->setLinea($LINEA);
					$pnts->setFecha_carga(date('Y-m-d'));
					$pnts->setTipo($TIPO);
					$pnts->setDirecta('');
					$pnts->setRegion($REGION);
					$pnts->setCodigo($CODIGO);
					$pnts->setUsuario($USUARIO);
					$pnts->setObjetivo($OBJETIVO);
					$pnts->setAvance($AVANCE_SUMA);
					$pnts->setAvance_por($AVANCE_PORCENTAJE);
					$pnts->setEjecutivo($EJECUTIVO);
					$pnts->setJefe($JEFE);
					$pnts->setGerente($GERENTE);
					$pnts->setPeso($PESO);
					$pnts->setMillas_individuales($MILLAS_INDIVIDUALES);
					$pnts->setRanking_mes($RANKING_MES);
					$pnts->setRanking_total($RANKING_TOTAL);

					if($pnts->insert()){
						$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS al IDCLIENTE:'.$IDCLIENTE.' '.$adv.'</strong></font><br />';
						$log_file .= '- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS '.$adv.PHP_EOL;
					}	else {
						$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
						$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
					}
				}


				if (in_array($tipo_socio_planilla, $tipos_repositores)){
					$ANIO								=	trim($_arr_content[0]);
					$MES								=	sanear_string(trim($_arr_content[1]));
					$TIPO								=	sanear_string(trim($_arr_content[2]));
					$REGION							=	sanear_string(trim($_arr_content[3]));
					
					//NEW
					$DIRECTA_DISTRI							=	sanear_string(trim($_arr_content[4]));

					$USUARIO						=	sanear_string(trim($_arr_content[5]));

					//Unifica G y H
					$LINEA							=	sanear_string(trim($_arr_content[6]));

					$OBJETIVO						=	trim($_arr_content[7]);
					$AVANCE_SUMA				=	trim($_arr_content[8]);
					$AVANCE_PORCENTAJE	=	trim($_arr_content[9]);
					$EJECUTIVO					=	sanear_string(trim($_arr_content[10]));
					$JEFE								=	sanear_string(trim($_arr_content[11]));
					$GERENTE						=	sanear_string(trim($_arr_content[12]));
					
					$MILLAS							=	trim($_arr_content[13]);
					$RANKING_MES				=	trim($_arr_content[14]);
					$RANKING_TOTAL			=	trim($_arr_content[15]);
					$CODIGO							=	trim($_arr_content[16]);
					$LEGAJO							=	trim($_arr_content[17]);
					
					$AVANCE_PORCENTAJE = number_format(floatval($AVANCE_SUMA)*100/floatval($OBJETIVO), 2);

					$indice =	$row;

					//armo el array completo
					$arr_materiales_excel[$row-1]['ANIO']								= $ANIO;
					$arr_materiales_excel[$row-1]['MES']								= $MES;
					$arr_materiales_excel[$row-1]['TIPO']								= $TIPO;
					$arr_materiales_excel[$row-1]['REGION']							= $REGION;
					$arr_materiales_excel[$row-1]['DIRECTA_DISTRI']						= $DIRECTA_DISTRI;
					$arr_materiales_excel[$row-1]['USUARIO']						= $USUARIO;
					$arr_materiales_excel[$row-1]['LINEA']							= $LINEA;
					$arr_materiales_excel[$row-1]['OBJETIVO']						= $OBJETIVO;
					$arr_materiales_excel[$row-1]['AVANCE_SUMA']				= $AVANCE_SUMA;
					$arr_materiales_excel[$row-1]['AVANCE_PORCENTAJE']	= $AVANCE_PORCENTAJE;
					$arr_materiales_excel[$row-1]['EJECUTIVO']					= $EJECUTIVO;
					$arr_materiales_excel[$row-1]['JEFE']								= $JEFE;
					$arr_materiales_excel[$row-1]['GERENTE']						= $GERENTE;
					$arr_materiales_excel[$row-1]['MILLAS']							= $MILLAS;
					$arr_materiales_excel[$row-1]['RANKING_MES']				= $RANKING_MES;
					$arr_materiales_excel[$row-1]['RANKING_TOTAL']			= $RANKING_TOTAL;
					$arr_materiales_excel[$row-1]['CODIGO']							= $CODIGO;
					$arr_materiales_excel[$row-1]['LEGAJO']							= $LEGAJO;

					//agregado pablo para procesamiento al toque
					$idcliente = '';
					if($clnt->select_X_codigo($LEGAJO)) $idcliente = $clnt->getIdsocio();

					$IDCLIENTE = $idcliente;

					$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
					$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

					$adv = '';
					if($IDCLIENTE == '') $adv.= '<font color="RED"> | FALTA EL ID DEL SOCIO &#211; NO FUE ENCONTRADO POR EL CODIGO '.$LEGAJO.'</font>';
					if($MILLAS == '') $adv.= '<font color="RED"> | FALTA EL VALOR DE MILLAS A CARGAR</font>';

					if (strrpos(strtolower($MES), "ene") !== false) {
						$NRO_MES = '01';
					}
					if (strrpos(strtolower($MES), "feb") !== false) {
						$NRO_MES = '02';
					}
					if (strrpos(strtolower($MES), "mar") !== false) {
						$NRO_MES = '03';
					}
					if (strrpos(strtolower($MES), "abr") !== false) {
						$NRO_MES = '04';
					}
					if (strrpos(strtolower($MES), "may") !== false) {
						$NRO_MES = '05';
					}
					if (strrpos(strtolower($MES), "jun") !== false) {
						$NRO_MES = '06';
					}
					if (strrpos(strtolower($MES), "jul") !== false) {
						$NRO_MES = '07';
					}
					if (strrpos(strtolower($MES), "ago") !== false) {
						$NRO_MES = '08';
					}
					if (strrpos(strtolower($MES), "sep") !== false) {
						$NRO_MES = '09';
					}
					if (strrpos(strtolower($MES), "oct") !== false) {
						$NRO_MES = '10';
					}
					if (strrpos(strtolower($MES), "nov") !== false) {
						$NRO_MES = '11';
					}
					if (strrpos(strtolower($MES), "dic") !== false) {
						$NRO_MES = '12';
					}
					
					if (strrpos(strtolower($MES), "enero") !== false) {
						$NRO_MES = '01';
					}
					if (strrpos(strtolower($MES), "febrero") !== false) {
						$NRO_MES = '02';
					}
					if (strrpos(strtolower($MES), "marzo") !== false) {
						$NRO_MES = '03';
					}
					if (strrpos(strtolower($MES), "abril") !== false) {
						$NRO_MES = '04';
					}
					if (strrpos(strtolower($MES), "mayo") !== false) {
						$NRO_MES = '05';
					}
					if (strrpos(strtolower($MES), "junio") !== false) {
						$NRO_MES = '06';
					}
					if (strrpos(strtolower($MES), "julio") !== false) {
						$NRO_MES = '07';
					}
					if (strrpos(strtolower($MES), "agosto") !== false) {
						$NRO_MES = '08';
					}
					if (strrpos(strtolower($MES), "septiembre") !== false) {
						$NRO_MES = '09';
					}
					if (strrpos(strtolower($MES), "octubre") !== false) {
						$NRO_MES = '10';
					}
					if (strrpos(strtolower($MES), "noviembre") !== false) {
						$NRO_MES = '11';
					}
					if (strrpos(strtolower($MES), "diciembre") !== false) {
						$NRO_MES = '12';
					}
					
					$FECHA = $ANIO.'-'.$NRO_MES.'-01';

					$pnts->setIdcliente($IDCLIENTE);
					$pnts->setPuntos($MILLAS);
					$pnts->setFecha($FECHA);
					$pnts->setLinea($LINEA);
					$pnts->setFecha_carga(date('Y-m-d'));
					$pnts->setTipo($TIPO);
					$pnts->setDirecta($DIRECTA_DISTRI);
					$pnts->setRegion($REGION);
					if($CODIGO)
						$pnts->setCodigo($CODIGO);
				
					$pnts->setUsuario($USUARIO);
					$pnts->setObjetivo($OBJETIVO);
					$pnts->setAvance($AVANCE_SUMA);
					$pnts->setAvance_por($AVANCE_PORCENTAJE);
					$pnts->setEjecutivo($EJECUTIVO);
					$pnts->setJefe($JEFE);
					$pnts->setGerente($GERENTE);
					$pnts->setRanking_mes($RANKING_MES);
					$pnts->setRanking_total($RANKING_TOTAL);
					$pnts->setMillas_individuales('');
					$pnts->setPeso('');

					if($pnts->insert()){
						$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS al IDCLIENTE:'.$IDCLIENTE.' '.$adv.'</strong></font><br />';
						$log_file .= '- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS '.$adv.PHP_EOL;
					}	else {
						$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
						$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
					}
				}
			}
		}
		fclose($file);
	}
	file_put_contents('logs/log_millas_'.date("Y-m-d").'.txt', $log_file, FILE_APPEND);
	$situacion = '3';
}

//CUANDO CONFIRMA PROCESO TODO EL FORM
if(isset($_POST['enviar']) && $_POST['enviar']=='IMPORTAR'){
	$indice_anterior = -1;
	$log_acciones = '';
	$log_file = '';
	$pnts = new puntos();
	$clnt = new socio();

	foreach($_POST as $nombre_campo => $valor){
		//reseteo los valores
		$pnts->resetea();
		//tomo el numerito
		$arr_campo = preg_split('/-/',$nombre_campo);
		$indice = $arr_campo[0];
		if($indice != $indice_anterior){
			$indice_anterior = $indice;
			if(isset($_POST[$indice.'-chequeado']) && $_POST[$indice.'-chequeado']=='S'){
				$tipos_directa = array('directa', 'DIRECTA', 'Directa');
				$tipos_distris = array('distribuidor', 'DISTRIBUIDOR', 'Distribuidor');
				$tipo_socio_planilla = sanear_string(trim($_POST[$indice.'-TIPO']));
				if (in_array($tipo_socio_planilla, $tipos_directa)){
					//tomo los datos
					//recupero el idcliente a partir de su codigo
					if($clnt->select_X_codigo($_POST[$indice.'-LEGAJO'])) $idcliente = $clnt->getIdsocio(); else $idcliente = '';
					$IDCLIENTE					= $idcliente;
					$ANIO								=	$_POST[$indice.'-ANIO'];
					$MES								=	$_POST[$indice.'-MES'];
					$TIPO								=	$_POST[$indice.'-TIPO'];
					$REGION							=	$_POST[$indice.'-REGION'];
					$DIRECTA						=	$_POST[$indice.'-DIRECTA'];
					$LEGAJO							=	$_POST[$indice.'-LEGAJO'];
					$USUARIO						=	$_POST[$indice.'-USUARIO'];
					$LINEA							=	$_POST[$indice.'-LINEA'];
					$OBJETIVO						=	$_POST[$indice.'-OBJETIVO'];
					$AVANCE_SUMA				=	$_POST[$indice.'-AVANCE_SUMA'];
					$AVANCE_PORCENTAJE	=	$_POST[$indice.'-AVANCE_PORCENTAJE'];
					$EJECUTIVO					=	$_POST[$indice.'-EJECUTIVO'];
					$JEFE								=	$_POST[$indice.'-JEFE'];
					$GERENTE						=	$_POST[$indice.'-GERENTE'];
					$PESO								= $_POST[$indice.'-PESO'];
					$MILLAS							=	$_POST[$indice.'-MILLAS'];
					$DIRECTA2						=	$_POST[$indice.'-DIRECTA2'];
					$RANKING_MES				=	$_POST[$indice.'-RANKING_MES'];
					$RANKING_TOTAL			=	$_POST[$indice.'-RANKING_TOTAL'];

					$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
					$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

					$adv = '';
					if($IDCLIENTE == '') $adv.= '<font color="RED"> | FALTA EL ID DE CLIENTE </font>';
					if($MILLAS == '') $adv.= '<font color="RED"> | FALTAN LAS MILLAS A CARGAR </font>';

					switch($MES) {
						case 'enero':
						case 'Enero':
						case 'ENERO':
						case 'ene':
						case 'Ene':
						case 'ENE':
						case '1':
						case '01':
							$NRO_MES = '01'; break;
						case 'febrero':
						case 'Febrero':
						case 'FEBRERO':
						case 'feb':
						case 'Feb':
						case 'FEB':
						case '2':
						case '02':
							$NRO_MES = '02'; break;
						case 'marzo':
						case 'Marzo':
						case 'MARZO':
						case 'mar':
						case 'Mar':
						case 'MAR':
						case '3':
						case '03':
							$NRO_MES = '03'; break;
						case 'abril':
						case 'Abril':
						case 'ABRIL':
						case 'abr':
						case 'Abr':
						case 'ABR':
						case '4':
						case '04':
							$NRO_MES = '04'; break;
						case 'mayo':
						case 'Mayo':
						case 'MAYO':
						case 'may':
						case 'May':
						case 'MAY':
						case '5':
						case '05':
							$NRO_MES = '05'; break;
						case 'junio':
						case 'Junio':
						case 'JUNIO':
						case 'jun':
						case 'Jun':
						case 'JUN':
						case '6':
						case '06':
							$NRO_MES = '06'; break;
						case 'julio':
						case 'Julio':
						case 'JULIO':
						case 'jul':
						case 'Jul':
						case 'JUL':
						case '7':
						case '07':
							$NRO_MES = '07'; break;
						case 'agosto':
						case 'Agosto':
						case 'AGOSTO':
						case 'ago':
						case 'Ago':
						case 'AGO':
						case '8':
						case '08':
							$NRO_MES = '08'; break;
						case 'setiembre':
						case 'Setiembre':
						case 'SETIEMBRE':
						case 'septiembre':
						case 'Septiembre':
						case 'SEPTIEMBRE':
						case 'set':
						case 'Set':
						case 'SET':
						case 'sep':
						case 'Sep':
						case 'SEP':
						case '9':
						case '09':
							$NRO_MES = '09'; break;
						case 'octubre':
						case 'Octubre':
						case 'OCTUBRE':
						case 'oct':
						case 'Oct':
						case 'OCT':
						case '10':
							$NRO_MES = '10'; break;
						case 'noviembre':
						case 'Noviembre':
						case 'NOVIEMBRE':
						case 'nov':
						case 'Nov':
						case 'NOV':
						case '11':
							$NRO_MES = '11'; break;
						case 'diciembre':
						case 'Diciembre':
						case 'DICIEMBRE':
						case 'dic':
						case 'Dic':
						case 'DIC':
						case '12':
							$NRO_MES = '12'; break;
						default: $NRO_MES = '';
					}
					$FECHA = $ANIO.'-'.$NRO_MES.'-01';

					$pnts->setIdcliente($IDCLIENTE);
					$pnts->setPuntos($MILLAS);
					$pnts->setFecha($FECHA);
					$pnts->setLinea($LINEA);
					$pnts->setFecha_carga(date('Y-m-d'));
					$pnts->setTipo($TIPO);
					$pnts->setDirecta($DIRECTA);
					$pnts->setRegion($REGION);
					$pnts->setCodigo($LEGAJO);
					$pnts->setUsuario($USUARIO);
					$pnts->setObjetivo($OBJETIVO);
					$pnts->setAvance($AVANCE_SUMA);
					$pnts->setAvance_por($AVANCE_PORCENTAJE);
					$pnts->setEjecutivo($EJECUTIVO);
					$pnts->setJefe($JEFE);
					$pnts->setGerente($GERENTE);
					$pnts->setPeso($PESO);
					$pnts->setMillas_individuales('');
					$pnts->setRanking_mes($RANKING_MES);
					$pnts->setRanking_total($RANKING_TOTAL);

					if($pnts->insert()){
						$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS '.$adv.'</strong></font><br />';
						$log_file .= '- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS '.$adv.PHP_EOL;
					}	else {
						$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
						$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
					}
				}

				if (in_array($tipo_socio_planilla, $tipos_distris)){
					//tomo los datos
					//recupero el idcliente a partir de su codigo
					if($clnt->select_X_codigo($_POST[$indice.'-CODIGO'])) $idcliente = $clnt->getIdsocio(); else $idcliente = '';
					$IDCLIENTE					= $idcliente;
					$ANIO								=	$_POST[$indice.'-ANIO'];
					$MES								=	$_POST[$indice.'-MES'];
					$TIPO								=	$_POST[$indice.'-TIPO'];
					$REGION							=	$_POST[$indice.'-REGION'];
					$CODIGO							=	$_POST[$indice.'-CODIGO'];
					$USUARIO						=	$_POST[$indice.'-USUARIO'];
					$LINEA							=	$_POST[$indice.'-LINEA'];
					$OBJETIVO						=	$_POST[$indice.'-OBJETIVO'];
					$AVANCE_SUMA				=	$_POST[$indice.'-AVANCE_SUMA'];
					$AVANCE_PORCENTAJE	=	$_POST[$indice.'-AVANCE_PORCENTAJE'];
					$EJECUTIVO					=	$_POST[$indice.'-EJECUTIVO'];
					$JEFE								=	$_POST[$indice.'-JEFE'];
					$GERENTE						=	$_POST[$indice.'-GERENTE'];
					$FDV								=	$_POST[$indice.'-FDV'];
					$PESO								= $_POST[$indice.'-PESO'];
					$MILLAS							=	$_POST[$indice.'-MILLAS'];
					$MILLAS_INDIVIDUALES=	$_POST[$indice.'-MILLAS_INDIVIDUALES'];
					$RANKING_MES				=	$_POST[$indice.'-RANKING_MES'];
					$RANKING_TOTAL			=	$_POST[$indice.'-RANKING_TOTAL'];

					$log_acciones .= '<br /><div class="impTt">Procesando la linea '.$indice.'</div>';
					$log_file .= 'Procesando la linea '.$indice.PHP_EOL;

					$adv = '';
					if($IDCLIENTE == '') $adv.= '<font color="RED"> | FALTA EL ID DE CLIENTE </font>';
					if($MILLAS == '') $adv.= '<font color="RED"> | FALTAN LAS MILLAS A CARGAR </font>';

					switch($MES) {
						case 'enero':
						case 'Enero':
						case 'ENERO':
						case 'ene':
						case 'Ene':
						case 'ENE':
						case '1':
						case '01':
							$NRO_MES = '01'; break;
						case 'febrero':
						case 'Febrero':
						case 'FEBRERO':
						case 'feb':
						case 'Feb':
						case 'FEB':
						case '2':
						case '02':
							$NRO_MES = '02'; break;
						case 'marzo':
						case 'Marzo':
						case 'MARZO':
						case 'mar':
						case 'Mar':
						case 'MAR':
						case '3':
						case '03':
							$NRO_MES = '03'; break;
						case 'abril':
						case 'Abril':
						case 'ABRIL':
						case 'abr':
						case 'Abr':
						case 'ABR':
						case '4':
						case '04':
							$NRO_MES = '04'; break;
						case 'mayo':
						case 'Mayo':
						case 'MAYO':
						case 'may':
						case 'May':
						case 'MAY':
						case '5':
						case '05':
							$NRO_MES = '05'; break;
						case 'junio':
						case 'Junio':
						case 'JUNIO':
						case 'jun':
						case 'Jun':
						case 'JUN':
						case '6':
						case '06':
							$NRO_MES = '06'; break;
						case 'julio':
						case 'Julio':
						case 'JULIO':
						case 'jul':
						case 'Jul':
						case 'JUL':
						case '7':
						case '07':
							$NRO_MES = '07'; break;
						case 'agosto':
						case 'Agosto':
						case 'AGOSTO':
						case 'ago':
						case 'Ago':
						case 'AGO':
						case '8':
						case '08':
							$NRO_MES = '08'; break;
						case 'setiembre':
						case 'Setiembre':
						case 'SETIEMBRE':
						case 'septiembre':
						case 'Septiembre':
						case 'SEPTIEMBRE':
						case 'set':
						case 'Set':
						case 'SET':
						case 'sep':
						case 'Sep':
						case 'SEP':
						case '9':
						case '09':
							$NRO_MES = '09'; break;
						case 'octubre':
						case 'Octubre':
						case 'OCTUBRE':
						case 'oct':
						case 'Oct':
						case 'OCT':
						case '10':
							$NRO_MES = '10'; break;
						case 'noviembre':
						case 'Noviembre':
						case 'NOVIEMBRE':
						case 'nov':
						case 'Nov':
						case 'NOV':
						case '11':
							$NRO_MES = '11'; break;
						case 'diciembre':
						case 'Diciembre':
						case 'DICIEMBRE':
						case 'dic':
						case 'Dic':
						case 'DIC':
						case '12':
							$NRO_MES = '12'; break;
						default: $NRO_MES = '';
					}
					$FECHA = $ANIO.'-'.$NRO_MES.'-01';

					$pnts->setIdcliente($IDCLIENTE);
					$pnts->setPuntos($MILLAS);
					$pnts->setFecha($FECHA);
					$pnts->setLinea($LINEA);
					$pnts->setFecha_carga(date('Y-m-d'));
					$pnts->setTipo($TIPO);
					$pnts->setDirecta('');
					$pnts->setRegion($REGION);
					$pnts->setCodigo($LEGAJO);
					$pnts->setUsuario($USUARIO);
					$pnts->setObjetivo($OBJETIVO);
					$pnts->setAvance($AVANCE_SUMA);
					$pnts->setAvance_por($AVANCE_PORCENTAJE);
					$pnts->setEjecutivo($EJECUTIVO);
					$pnts->setJefe($JEFE);
					$pnts->setGerente($GERENTE);
					$pnts->setPeso($PESO);
					$pnts->setMillas_individuales($MILLAS_INDIVIDUALES);
					$pnts->setRanking_mes($RANKING_MES);
					$pnts->setRanking_total($RANKING_TOTAL);

					if($pnts->insert()){
						$log_acciones .= '<strong><font color="GREEN">- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS '.$adv.'</strong></font><br />';
						$log_file .= '- FILA '.$indice.' SE CARGARON: '.$MILLAS.' MILLAS '.$adv.PHP_EOL;
					}	else {
						$log_acciones .= '<strong><font color="RED">- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA</strong></font><br />';
						$log_file .= '- FILA '.$indice.' ERROR SQL AL INSERTAR ESTA LINEA'.PHP_EOL;
					}
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
				d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
			}
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
													<td height="25" valign="bottom" class="textoMarron14sinhover"><strong>ASIGNAR MILLAS A SOCIOS ADHERIDOS</strong></td>
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
																	<td>
																		Desde este modulo se procesa el archivo de millas para los socios adheridos.<strong>La primer fila del archivo sera ignorada dado que se espera contenga los nombres de cada columna.</strong><br /><br />El mismo debe contener la informaci&#243;n de las millas bajo una estructura determinada. Cada l&#237;nea deber&#225; tener los siguientes campos siempre separados por <strong>punto y coma</strong>.<br /><br /><span style="display:block; border:solid 1px #333; padding:15px">
																		<strong>FORMATO PARA VENDEDORES:</strong><br>Año; Mes en curso; Distri / directa; Region; Directa; Vendedor;; Linea comercial; Objetivo; Suma de Avance CCC; Suma de Avance %; Ejecutivo; Jefe; Gerente; Peso; Millas; Directa 2; Ranking del mes; Ranking total; Legajo</span>
																		<br /><br /><span style="display:block; border:solid 1px #333; padding:15px"><strong>FORMATO PARA DISTRIBUIDORES:</strong><br>Año; Mes en curso; Distri / directa; Region; Distribuidor; Linea comercial; Objetivo; Suma de Avance CCC; Suma de Avance %; Ejecutivo; Jefe; Gerente; FDP; Peso; Millas; Millas individuales; Ranking del mes; Ranking total; Codigo Distribuidor</span>

																		<br /><br /><span style="display:block; border:solid 1px #333; padding:15px"><strong>FORMATO PARA REPOSITORES:</strong><br>Año;Mes en curso;Distri / directa;Region;Directa / Distri;Repo;Tipo exhibicion Categoria;Objetivo;Suma de Avance CCC;Suma de Avance %;Supervisor;Jefe;Gerente;Millas;Ranking mes;Ranking acum;COD DI / Directa;Legajo</span>
																	</td>
																</tr>
															</table>
														</div>
														<?php }?>

														<?php if($situacion == '2'){?>
														<form name="formdatos" id="formdatos" action="importacion_puntos.php" method="POST">
															<table width="100%" border="1" cellpadding="5" cellspacing="5" style="border: 1px solid black; font-size:12px; border-collapse: collapse;">
																<tr>
																	<td><span></span></td>
																	<td><span>Año</span></td>
																	<td><span>Mes en curso</span></td>
																	<td><span>Distri / directa</span></td>
																	<td><span>Region</span></td>
																	<td><span>Directa</span></td>
																	<td><span>Codigo</span></td>
																	<td><span>Usuario</span></td>
																	<td><span>Linea comercial</span></td>
																	<td><span>Objetivo</span></td>
																	<td><span>Suma de Avance CCC</span></td>
																	<td><span>Suma de Avance %</span></td>
																	<td><span>Ejecutivo</span></td>
																	<td><span>Jefe</span></td>
																	<td><span>Gerente</span></td>
																	<td><span>Peso</span></td>
																	<td><span>Millas</span></td>
																	<td><span>Millas individuales</span></td>
																	<td><span>Ranking del mes</span></td>
																	<td><span>Ranking total</span></td>
																</tr>
																<!-- LA PANTALLA DE PREVIEW DE CARGA-->
																<?php
																for($i=0;$i<$tamanio;$i++){
																?>
																<tr>
																	<td>
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['ANIO'];?>" name="<?php echo $i?>-ANIO">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['MES'];?>" name="<?php echo $i?>-MES">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['TIPO'];?>" name="<?php echo $i?>-TIPO">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['REGION'];?>" name="<?php echo $i?>-REGION">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['DIRECTA'];?>" name="<?php echo $i?>-DIRECTA">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['CODIGO'];?>" name="<?php echo $i?>-CODIGO">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['USUARIO'];?>" name="<?php echo $i?>-USUARIO">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['LINEA'];?>" name="<?php echo $i?>-LINEA">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['OBJETIVO'];?>" name="<?php echo $i?>-OBJETIVO">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['AVANCE_SUMA'];?>" name="<?php echo $i?>-AVANCE_SUMA">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['AVANCE_PORCENTAJE'];?>" name="<?php echo $i?>-AVANCE_PORCENTAJE">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['EJECUTIVO'];?>" name="<?php echo $i?>-EJECUTIVO">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['JEFE'];?>" name="<?php echo $i?>-JEFE">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['GERENTE'];?>" name="<?php echo $i?>-GERENTE">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['PESO'];?>" name="<?php echo $i?>-PESO">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['MILLAS'];?>" name="<?php echo $i?>-MILLAS">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['MILLAS_INDIVIDUALES'];?>" name="<?php echo $i?>-MILLAS_INDIVIDUALES">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['RANKING_MES'];?>" name="<?php echo $i?>-RANKING_MES">
																		<input type="hidden" value="<?php echo $arr_materiales_excel[$i]['RANKING_TOTAL'];?>" name="<?php echo $i?>-RANKING_TOTAL">
																		<input type="checkbox" id="" value="S" name="<?php echo $i?>-chequeado" checked>
																		<input type="hidden" value="<?php echo $nombre_archivo;?>" name="nombre_archivo">
																	</td>
																	<td><?php echo $arr_materiales_excel[$i]['ANIO'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['MES'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['TIPO'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['REGION'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['DIRECTA'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['CODIGO'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['USUARIO'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['LINEA'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['OBJETIVO'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['AVANCE_SUMA'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['AVANCE_PORCENTAJE'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['EJECUTIVO'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['JEFE'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['GERENTE'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['PESO'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['MILLAS'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['MILLAS_INDIVIDUALES'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['RANKING_MES'];?></td>
																	<td><?php echo $arr_materiales_excel[$i]['RANKING_TOTAL'];?></td>
																</tr>
																<?php	}?>
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