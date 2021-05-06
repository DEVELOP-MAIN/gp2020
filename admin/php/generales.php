<?php
header("Content-Type: text/html;charset=utf-8");

//path que define el raiz
$path_general = '/';

//array de dias de la semana
$nombre_dia[1] = 'Lunes';
$nombre_dia[2] = 'Martes';
$nombre_dia[3] = 'Miércoles';
$nombre_dia[4] = 'Jueves';
$nombre_dia[5] = 'Viernes';
$nombre_dia[6] = 'Sábado';
$nombre_dia[7] = 'Domingo';

//array de dias de la semana
$nombre_dia2[1] = 'Lun';
$nombre_dia2[2] = 'Mar';
$nombre_dia2[3] = 'Mie';
$nombre_dia2[4] = 'Jue';
$nombre_dia2[5] = 'Vie';
$nombre_dia2[6] = 'Sab';
$nombre_dia2[7] = 'Dom';

//array meses
$nombre_mes[1] = 'Enero';
$nombre_mes[2] = 'Febrero';
$nombre_mes[3] = 'Marzo';
$nombre_mes[4] = 'Abril';
$nombre_mes[5] = 'Mayo';
$nombre_mes[6] = 'Junio';
$nombre_mes[7] = 'Julio';
$nombre_mes[8] = 'Agosto';
$nombre_mes[9] = 'Septiembre';
$nombre_mes[10] = 'Octubre';
$nombre_mes[11] = 'Noviembre';
$nombre_mes[12] = 'Diciembre';

//array meses
$nombre_mes_abre[1] = 'ENE';
$nombre_mes_abre[2] = 'FEB';
$nombre_mes_abre[3] = 'MAR';
$nombre_mes_abre[4] = 'ABR';
$nombre_mes_abre[5] = 'MAY';
$nombre_mes_abre[6] = 'JUN';
$nombre_mes_abre[7] = 'JUL';
$nombre_mes_abre[8] = 'AGO';
$nombre_mes_abre[9] = 'SEP';
$nombre_mes_abre[10] = 'OCT';
$nombre_mes_abre[11] = 'NOV';
$nombre_mes_abre[12] = 'DIC';

//funcion de envio de resultados de error via XML
function printErrorXML($codigo, $mensaje) {
	header('Content-type:text/xml;charset="utf-8"');
	$xmlDoc = new MiniXMLDoc();
	$xmlRoot =& $xmlDoc->getRoot();
	$resultadosGenerales =& $xmlRoot->createChild('resultadosGenerales');
	$resultadosGenerales->attribute('resultado', 0);
	$resultadosGenerales->attribute('codigo', $codigo);
	$mensajes =& $resultadosGenerales->createChild('mensaje');
	$mensajes->text($mensaje);	 
	print html_entity_decode($xmlDoc->toString(MINIXML_NOWHITESPACES));
}

function decode($arreglo) {
	foreach($arreglo as $indice => $valor)
   $arreglo[$indice] = utf8_decode($valor);
  return $arreglo;
}

function decodePOST($arreglo) {
	//foreach($arreglo as $indice => $valor)
   //$arreglo[$indice] = utf8_decode($valor);
  return $arreglo;
}

//funcion que recorta y hace un thumb
function create_jpgthumb($original, $thumbnail, $max_width, $max_height, $quality, $scale = true) {
   
    list ($src_width, $src_height, $type, $w) = getimagesize($original);
   	$off_h = 0;
   	$off_w = 0;
    if (!$srcImage = @imagecreatefromjpeg($original)) {
        return false;
    }

    //image resizes to natural height and width
    if ($scale == true) {
                   
        if ($src_width > $src_height ) {
            $thumb_width = $max_width;
            $thumb_height = floor($src_height * ($max_width / $src_width));
        } else if ($src_width < $src_height ) {
            $thumb_height = $max_height;
            $thumb_width = floor($src_width * ($max_height / $src_height));
        } else {
            $thumb_width = $max_height;
            $thumb_height = $max_height;
        }

        if (!@$destImage = imagecreatetruecolor($thumb_width, $thumb_height)) {
            return false;
        }
       
        if (!@imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $thumb_width, $thumb_height, $src_width, $src_height)) {
            return false;
        }
                   
    //image is fixed to supplied width and height and cropped
    } else if ($scale == false) {
   
        $ratio = $max_width / $max_height;
           
        //thumbnail is landscape
         if ($ratio > 1) {
        
            //uploaded pic is landscape
            if ($src_width > $src_height) {

                $thumb_width = $max_width;
                $thumb_height = ceil($max_width * ($src_height / $src_width));
               
                if ($thumb_height > $max_width) {
                    $thumb_height = $max_width;
                    $thumb_width = ceil($max_width * ($src_width / $src_height));
                } 
                $thumb_height = $max_height;
                
                //$off_w = ($src_width - $src_height) / 2;

            //uploaded pic is portrait
            } else {
           
                $thumb_height = $max_width;
                $thumb_width = ceil($max_width * ($src_height / $src_width));
               
                if ($thumb_width > $max_width) {
                    $thumb_width = $max_width;
                    $thumb_height = ceil($max_width * ($src_height / $src_width));
                }
               
                $off_h = ($src_height - $src_width) / 2;
       
            }

            if (!@$destImage = imagecreatetruecolor($max_width, $max_height)) {
                return false;
            }
   
            if (!@imagecopyresampled($destImage, $srcImage, 0, 0, $off_w, $off_h, $thumb_width, $thumb_height, $src_width, $src_height)) {
                return false;
            }

        //thumbnail is square
         } else {
        
            if ($src_width > $src_height) {
                $off_w = ($src_width - $src_height) / 2;
                $off_h = 0;
                $src_width = $src_height;
            } else if ($src_height > $src_width) {
                $off_w = 0;
                $off_h = ($src_height - $src_width) / 2;
                $src_height = $src_width;
            } else {
                $off_w = 0;
                $off_h = 0;
            }

            if (!@$destImage = imagecreatetruecolor($max_width, $max_height)) {
                return false;
            }
   
            if (!@imagecopyresampled($destImage, $srcImage, 0, 0, $off_w, $off_h, $max_width, $max_height, $src_width, $src_height)) {
                return false;
            }

         }
    
                       
    }
   
    @imagedestroy($srcImage);

    if (!@imageantialias($destImage, true)) {
        return false;
    }
   
    if (!@imagejpeg($destImage, $thumbnail, $quality)) {
        return false;
    }
                   
    @imagedestroy($destImage);
   
    return true;
}


function getURL(){
	$server 	= getenv("SERVER_NAME");
	$pagina 	= getenv("REQUEST_URI");
	if(isset($_SERVER["QUERY_STRING"]))
		$query 	= $_SERVER["QUERY_STRING"];
	else
		$query 	= "";
	$str_url 	= "http://".$server.$pagina.$query;
	return $str_url;
}

function compararFechas($primera, $segunda)
{
  $valoresPrimera 	= explode ("/", $primera);   
  $valoresSegunda	= explode ("/", $segunda); 

  $diaPrimera    	= $valoresPrimera[0];  
  $mesPrimera  	= $valoresPrimera[1];  
  $anyoPrimera	= $valoresPrimera[2]; 

  $diaSegunda  	= $valoresSegunda[0];  
  $mesSegunda 	= $valoresSegunda[1];  
  $anyoSegunda	= $valoresSegunda[2];

  $diasPrimeraJuliano 	= gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
  $diasSegundaJuliano	= gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);     

  if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera))
	{
    // "La fecha ".$primera." no es válida";
    return 0;
  }
	elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda))
	{
    // "La fecha ".$segunda." no es válida";
    return 0;
  }
	else
	  return  $diasPrimeraJuliano - $diasSegundaJuliano;
}

function myTruncate($string, $limit, $break=" ", $pad="...") {	
	if(strlen($string) <= $limit)
		return $string;	
		
	if(false !== ($breakpoint = strpos($string, $break, $limit))) 
	{
		if($breakpoint < (strlen($string)-1)) 
			$string = substr($string, 0, $breakpoint).$pad;
	}
	return $string;
}

function pongoCeros($valor, $limite) {
	$tamano = strlen($valor);
	$result = "";
	for($i=$tamano;$i<$limite;$i++)
		$result.="0";
	return $result.$valor;
}

function pongoEspacios($valor, $limite) {
	$tamano = strlen($valor);
	$result = "";
	for($i=$tamano;$i<$limite;$i++)
		$result.=" ";
	return $valor.$result;
}

/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 *
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */
function sanear_string($string){
	$string = trim($string);
	$string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
	$string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
	$string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
	$string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
	$string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
	$string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string);
	//Esta parte se encarga de eliminar cualquier caracter extraño
	//$string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "< ", ";", ",", ":", ".", " "), "", $string);
	$string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "< ", ";", ",", ":", "."), "", $string);
	
	return $string;
}

function limpiarCaracteresEspeciales($string ){
 $string = htmlentities($string);
 $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
 return $string;
}

function validaVars($campo){
	$campo = xss_cleaner($campo);
	$campo = strip_tags($campo);

	//Array con las posibles cadenas a utilizar por un hacker
	$CadenasProhibidas = array('Content-Type:', 'MIME-Version:', 'Content-Transfer-Encoding:','Return-path:','Subject:','From:','Envelope-to:','To:','bcc:','cc:', ' UNION ', 'SCRIPT','DELETE ','DROP ','SELECT ','INSERT ','UPDATE ','CREATE ','TRUNCATE ','ALTER ','INTO ','DISTINCT ','GROUP BY ','WHERE ','RENAME ','DEFINE ','UNDEFINE ','PROMPT ','ACCEPT ','VIEW ','COUNT ','HAVING ','{','}','[',']');	

	foreach($CadenasProhibidas as $valor)
		$campo = str_replace($valor,'',$campo);
	return $campo;
}

function validaVars2($campo){
	$campo = strip_tags($campo);

	//Array con las posibles cadenas a utilizar por un hacker
	$CadenasProhibidas = array('Content-Type:', 'MIME-Version:', 'Content-Transfer-Encoding:','Return-path:','Subject:','From:','Envelope-to:','To:','bcc:','cc:', ' UNION ', 'SCRIPT','DELETE ','DROP ','SELECT ','INSERT ','UPDATE ','CREATE ','TRUNCATE ','ALTER ','INTO ','DISTINCT ','GROUP BY ','WHERE ','RENAME ','DEFINE ','UNDEFINE ','PROMPT ','ACCEPT ','VIEW ','COUNT ','HAVING ','{','}','[',']');	

	foreach($CadenasProhibidas as $valor)
		$campo = str_replace($valor,'',$campo);
	return $campo;
}

function xss_cleaner($input_str) {
	$return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
	$return_str = str_ireplace( '%3Cscript', '', $return_str );
	return $return_str;
}

/*
* simple method to encrypt or decrypt a plain text string
* initialization vector(IV) has to be the same when encrypting and decrypting
* PHP 5.4.9 ( check your PHP version for function definition changes )
*
* this is a beginners template for simple encryption decryption
* before using this in production environments, please read about encryption
* use at your own risk
*
* @param string $action: can be 'encrypt' or 'decrypt'
* @param string $string: string to encrypt or decrypt
*
* @return string
*/
function encrypt_decrypt($action, $string) {
	$output = false;

	$encrypt_method = "AES-256-CBC";
	$secret_key = 'This is my secret key';
	$secret_iv = 'This is my secret iv';

	// hash
	$key = hash('sha256', $secret_key);

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	if($action == 'encrypt') {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	}
	else if($action == 'decrypt'){
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}

	return $output;
}

function dameEquipoSegunRegion($region) {	
	switch($region) {
    case 'AMBA':
        return 'MC LAREN';
        break;
    case 'CENTRO':
        return 'FERRARI';
        break;
    case 'CUYO':
        return 'WILLIAMS';
        break;
    case 'LITORAL':
        return 'MERCEDES';
        break;
    case 'NEA':
        return 'HASS';
        break;
    case 'NOA':
        return 'RED BULL';
        break;
    case 'PBA':
        return 'ALFA ROMEO';
        break;
    case 'SUR':
        return 'RENAULT';
        break;
	case 'GBA NOROESTE':
        return 'MC LAREN';
        break;
    case 'CAPITAL2':
        return 'FERRARI';
        break;
    case 'LITORAL':
        return 'WILLIAMS';
        break;
    case 'GBA NORTE':
        return 'MERCEDES';
        break;
    case 'CENTRO':
        return 'HASS';
        break;
    case 'GBA SUR2':
        return 'RED BULL';
        break;
    case 'CAPITAL1':
        return 'ALFA ROMEO';
        break;
    case 'LA PLATA':
        return 'RENAULT';
        break;
	default: return '';
	}
}

function dameCssSegunRegion($region) {	
	switch($region) {
    case 'AMBA':
        return 'gp-mclaren';
        break;
    case 'CENTRO':
        return 'gp-ferrari';
        break;
    case 'CUYO':
        return 'gp-williams';
        break;
    case 'LITORAL':
        return 'gp-mercedes';
        break;
    case 'NEA':
        return 'gp-hass';
        break;
    case 'NOA':
        return 'gp-redbull';
        break;
    case 'PBA':
        return 'gp-alfa-romeo';
        break;
    case 'SUR':
        return 'gp-renault';
        break;
	case 'GBA NOROESTE':
        return 'gp-mclaren';
        break;
    case 'CAPITAL2':
        return 'gp-ferrari';
        break;
    case 'LITORAL':
        return 'gp-williams';
        break;
    case 'GBA NORTE':
        return 'gp-mercedes';
        break;
    case 'CENTRO':
        return 'gp-hass';
        break;
    case 'GBA SUR2':
        return 'gp-redbull';
        break;
    case 'CAPITAL1':
        return 'gp-alfa-romeo';
        break;
    case 'LA PLATA':
        return 'gp-renault';
        break;
	default: return '';
	}
}

function dameImgSegunRegion($region) {	
	switch($region) {
    case 'AMBA':
        return 'car_mclaren.svg';
        break;
    case 'CENTRO':
        return 'car_ferrari.svg';
        break;
    case 'CUYO':
        return 'car_williams.svg';
        break;
    case 'LITORAL':
        return 'car_mercedes.svg';
        break;
    case 'NEA':
        return 'car_hass.svg';
        break;
    case 'NOA':
        return 'car_redbull.svg';
        break;
    case 'PBA':
        return 'car_alfaromeo.svg';
        break;
    case 'SUR':
        return 'car_renault.svg';
        break;
		case 'GBA NOROESTE':
        return 'car_mclaren.svg';
        break;
    case 'CAPITAL2':
        return 'car_ferrari.svg';
        break;
    case 'LITORAL':
        return 'car_williams.svg';
        break;
    case 'GBA NORTE':
        return 'car_mercedes.svg';
        break;
    case 'CENTRO':
        return 'car_hass.svg';
        break;
    case 'GBA SUR2':
        return 'car_redbull.svg';
        break;
    case 'CAPITAL1':
        return 'car_alfaromeo.svg';
        break;
    case 'LA PLATA':
        return 'car_renault.svg';
        break;
		default: return '';
	}
}

function dameColorSegunAvance($avance) {
	switch($avance){
		case ($avance >= 0 && $avance <= 85):
			return 'rojo'; break;
		case ($avance >= 85.1 && $avance <= 99.9):
			return 'amarillo'; break;
		case ($avance >= 100):
			return 'verde'; break;
	}
}
?>