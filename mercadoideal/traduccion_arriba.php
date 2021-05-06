<?php
ini_set("safe_mode",0);

if(!isset($_SESSION)) {session_start();}

//por defecto es español
$codigo_idioma = "ar-ES";

//obtengo el idioma por defecto
if(getenv("HTTP_ACCEPT_LANGUAGE")!="") {
	$codigo_idioma = substr(getenv("HTTP_ACCEPT_LANGUAGE"),0,2);
	if($codigo_idioma == "es") $codigo_idioma = "ar-ES";
}

//si esta logueado tomo el idioma que el usuario selecciono
if(isset($_SESSION['QLMSF_idioma']) && $_SESSION['QLMSF_idioma']!="") {
	$codigo_idioma = $_SESSION['QLMSF_idioma'];
}

//si esta logueado tomo el idioma que el usuario selecciono
if(isset($_COOKIE['QLMSF_idioma']) && $_COOKIE['QLMSF_idioma']!="") {
	$codigo_idioma = $_COOKIE['QLMSF_idioma'];
}

//seteo variables para gettext
switch($codigo_idioma){ //ahora cargamos el archivo que contiene el idioma segun la varible que sacamos antes
	case "ar-ES" : //es = español	
		putenv("LANGUAGE=ar_ES");
		putenv('LANG=ar_ES');
		putenv("LC_ALL={ar_ES}");		
		setlocale(LC_ALL, 'ar_ES');
        break;
    case "zh_CN" : //zh = chino		
		putenv("LANGUAGE=zh_CN.UTF-8");
		putenv('LANG=zh_CN.UTF-8');
		putenv("LC_ALL={zh_CN.UTF-8}");		
		setlocale(LC_ALL, NULL);
		setlocale(LC_TIME, "");
		setlocale(LC_ALL, 'zh_CN.UTF-8');
        break;
	case "zh_CN_dxr" : //zh = chino		
		putenv("LANGUAGE=zh_CN");
		putenv('LANG=zh_CN');
		putenv("LC_ALL={zh_CN}");		
		setlocale(LC_ALL, NULL);
		setlocale(LC_TIME, "");
		setlocale(LC_ALL, 'zh_CN');
        break;	
    default :
		putenv("LANGUAGE=ar_ES");
		putenv('LANG=ar_ES');
		putenv("LC_ALL={ar_ES}");		
		setlocale(LC_ALL, 'ar_ES');
        break;
}

$domain = "message";
bindtextdomain($domain, 'locale/nocache');
bindtextdomain($domain, 'locale');
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);
?>