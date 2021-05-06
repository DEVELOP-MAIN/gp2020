<?php
if(!isset($_SESSION)) {session_start();}
$_SESSION['QLMSF_logged'] 		= 0;
$_SESSION['QLMSF_idcliente']	= '';
$_SESSION['QLMSF_nombre']			= '';
$_SESSION['QLMSF_apellido']		= '';
header("location:../index.php");

?>