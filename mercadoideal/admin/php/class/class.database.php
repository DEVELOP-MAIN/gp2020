<?php
include_once("configuracion.php");

class Database
{ 
// Class : begin

var $host;  				//Hostname, Server
var $password;	//Passwort MySQL
var $user; 					//User MySQL
var $database;	//Datenbankname MySQL
var $link;
var $query;
var $result;
var $rows;

function Database(){  	
// Method : begin
//Konstruktor
// ********** DIESE WERTE ANPASSEN **************
// ********** ADJUST THESE VALUES HERE ***********
$this->host 		= DDBBHOST;
$this->password	= DDBBPASSWORD;
$this->user 		= DDBBUSER;    
$this->database = DDBBDATABASE;
$this->rows 		= 0;

// ********************************************
// ********************************************

} // Method : end

function OpenLink()
{ // Method : begin
$this->link = @mysql_connect($this->host,$this->user,$this->password) or die (print 'Class Database: Error while connecting to DB (link)'); 	
@mysql_set_charset('utf8', $this->link);
} // Method : end

function SelectDB()
{ // Method : begin 
@mysql_select_db($this->database,$this->link) or die (print 'Class Database: Error while selecting DB');  
} // Method : end

function CloseDB()
{ // Method : begin 
mysql_close();
} // Method : end

function Query($query)
{ // Method : begin
$this->OpenLink();
$this->SelectDB(); 
$this->query  = $query;
$this->result = mysql_query($query,$this->link);
if(!$this->result) 
{	
	print mysql_error(); //return false;	
}

if(preg_match('/SELECT/',$query))
{
 $this->rows = mysql_num_rows($this->result);
}

$this->CloseDB();
return true;
} // Method : end	

// Class : end
}
?>