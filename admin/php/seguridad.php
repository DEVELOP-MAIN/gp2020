<?php
if(!isset($_SESSION)) {session_start();}
if(!isset($_SESSION['QLMS_logged']) || $_SESSION['QLMS_logged']!=1) 
{
	echo 'Permisos insuficientes';
	exit;
}
?>