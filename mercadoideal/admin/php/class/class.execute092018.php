<?php
include_once('class.database.php');
$db = new Database();

//aletar la tabla de noticias para que tenga el campo tipo
$sql = "ALTER TABLE `clientes` ADD COLUMN `lastlog` DATETIME NULL AFTER `dni`;";
$result = $db->query($sql);
echo "OK - add campo last log<BR>";
?>