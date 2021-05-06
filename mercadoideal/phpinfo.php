<?php
if(!isset($_SESSION)) {session_start();}

$_SESSION['QLMSF_idioma'] = "zh_CN";

include("traduccion.php");

echo _('Domicilio');

phpinfo();
?>