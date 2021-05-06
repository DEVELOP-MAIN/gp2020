<?php
if(!isset($_SESSION)) {session_start();}
if(!isset($_SESSION['QLMSF_logged']) || $_SESSION['QLMSF_logged']!=1 || !isset($_SESSION['QLMSF_estado']) || $_SESSION['QLMSF_estado']!="C") {	
		header("location:php/logout.php");
}
?>