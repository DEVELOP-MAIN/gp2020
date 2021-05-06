<?php
if(!isset($_SESSION)) {session_start();}

if(!isset($_SESSION['QLMSF_email']) || $_SESSION['QLMSF_email']==''){
	header('location:datos_personales.php');
}
?>