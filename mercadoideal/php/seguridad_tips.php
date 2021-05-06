<?php
if(!isset($_SESSION)) {session_start();}
if(!isset($_SESSION['QLMSF_logged']) || $_SESSION['QLMSF_logged']!=1 || !isset($_SESSION['QLMSF_estado']) || $_SESSION['QLMSF_estado']!="C") {	
		//phpinfo();
		$_SESSION['url_redirect'] = $_SERVER["REQUEST_URI"];
		header("location:php/logout.php");
}
?>