<?php
if(!isset($_SESSION)) {session_start();}
if(isset($_REQUEST["l"]) && $_REQUEST["l"]=="zh_CN") {
	$_SESSION['QLMSF_idioma'] = "zh_CN";
	setcookie("QLMSF_idioma","zh_CN");
}
if(isset($_REQUEST["l"]) && $_REQUEST["l"]=="ar_ES") {
	$_SESSION['QLMSF_idioma'] = "ar_ES";
	setcookie("QLMSF_idioma","ar_ES");
}	
header("location:".$_SERVER['HTTP_REFERER']);
?>