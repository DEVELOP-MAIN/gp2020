<?php
/*------------------------------------------------------
CLASSNAME:        			noticia
GENERATION DATE: 	07.02.2017
CLASS FILE:       				class.noticia.php
FOR MYSQL TABLE:  	noticias
FOR MYSQL DB:     			aper_quilmes
--------------------------------------------------------
CODE GENERATED BY: dixernet
------------------------------------------------------*/

include_once("class.database.php");

//CLASS DECLARATION
//========================================================================================
class noticia
{ // class : begin

//ATTRIBUTE DECLARATION
//========================================================================================
var $idnoticia;  		// KEY ATTR. WITH AUTOINCREMENT

var $titulo;  					// (normal attribute)
var $cuerpo;				// (normal attribute)
var $fecha_alta;	// (normal attribute)
var $imagen;				// (normal attribute)
var $tipo;				// (normal attribute)
var $estado;				// (normal attribute)
var $video;					// (normal attribute)

var $database; 		// Instance of class database

//CONSTRUCTOR METHOD
//========================================================================================
function noticia() {$this->database = new Database();}

//GETTER METHODS
//========================================================================================
function getIdnoticia() 		{return $this->idnoticia;}
function getEstado() 					{return $this->estado;}
function getTitulo() 					{return $this->titulo;}
function getTipo() 					{return $this->tipo;}
function getCuerpo() 			{return $this->cuerpo;}
function getFecha_alta()	{return $this->fecha_alta;}
function getImagen()				{return $this->imagen;}
function getVideo()					{return $this->video;}

// SETTER METHODS
//========================================================================================
function setIdnoticia($val) 		{$this->idnoticia = $val;}
function setEstado($val) 					{$this->estado = $val;}
function setTitulo($val) 					{$this->titulo = $val;}
function setTipo($val) 					{$this->tipo = $val;}
function setCuerpo($val) 			{$this->cuerpo = $val;}
function setFecha_alta($val)	{$this->fecha_alta = $val;}
function setImagen($val)			{$this->imagen = $val;}
function setVideo($val)					{$this->video = $val;}

//SELECT METHOD / LOAD
//========================================================================================
function select($id)
{
	$sql 		= "SELECT * FROM noticias WHERE idnoticia = '$id'";
	$result = $this->database->query($sql);
	$result = $this->database->result;
	if($row = mysql_fetch_object($result))
	{
		$this->idnoticia 	= $row->idnoticia;
		$this->titulo 		= $row->titulo;
		$this->estado 		= $row->estado;
		$this->tipo 		= $row->tipo;
		$this->cuerpo 		= $row->cuerpo;
		$this->fecha_alta	= $row->fecha_alta;
		$this->imagen		= $row->imagen;
		$this->video	  	= $row->video;
		return true;
	}
	else
		return false;
}

//DELETE
//========================================================================================
function delete($id)
{
	$sql = "DELETE FROM noticias WHERE idnoticia = '$id'";
	$result = $this->database->query($sql);
}

// INSERT
//========================================================================================
function insert()
{
	$this->idnoticia = ''; // clear key for autoincrement
	$this->prepare();
	$sql = "INSERT INTO noticias (titulo,estado,cuerpo,tipo,fecha_alta,imagen,video) VALUES ($this->titulo,$this->estado,$this->cuerpo,$this->tipo,'".date('Y-m-d')."',$this->imagen,$this->video)";
	$result = $this->database->query($sql);
	$this->idnoticia = mysql_insert_id($this->database->link);
	return true;
}

// UPDATE
//========================================================================================
function update($id)
{
	$this->prepare();
	$sql = "UPDATE noticias SET titulo = $this->titulo,estado = $this->estado,tipo = $this->tipo,cuerpo = $this->cuerpo,fecha_alta = '".date('Y-m-d')."',imagen = $this->imagen,video = $this->video WHERE idnoticia = '$id'";
	$result = $this->database->query($sql);
	return true;
}

//========================================================================================
function prepare(){
	if($this->titulo != '')					$this->titulo						= '\''.$this->titulo.'\'';													else $this->titulo					= 'NULL';
	if($this->titulo != '')					$this->tipo						= '\''.$this->tipo.'\'';													else $this->tipo					= 'NULL';
	if($this->cuerpo	!= '')				$this->cuerpo				= '\''.addslashes($this->cuerpo).'\'';	else $this->cuerpo			= 'NULL';
	if($this->fecha_alta != '')	$this->fecha_alta	= '\''.$this->fecha_alta.'\'';									else $this->fecha_alta	= 'NULL';
	if($this->imagen != '')			$this->imagen				= '\''.$this->imagen.'\'';											else $this->imagen			= 'NULL';
	if($this->video != '')					$this->video					= '\''.$this->video.'\'';													else $this->video					= 'NULL';
	if($this->estado != '')					$this->estado					= '\''.$this->estado.'\'';													else $this->estado					= 'NULL';
}

// class : end
}
?>