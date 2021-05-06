<?php
/*------------------------------------------------------
CLASSNAME:        premio
GENERATION DATE:	03.07.2020
CLASS FILE:       class.premio.php
FOR MYSQL TABLE: 	premios
FOR MYSQL DB:     gp2020_dev
--------------------------------------------------------
CODE GENERATED BY: dixernet
------------------------------------------------------*/

include_once('class.database.php');

//CLASS DECLARATION
//==================================================================================================
class premio
{ // class : begin


//ATTRIBUTE DECLARATION
//==================================================================================================
var $idpremio;   		// KEY ATTR. WITH AUTOINCREMENT

var $idcampania;   	// (normal attribute)
var $nombre;   			// (normal attribute)
var $imagen;   			// (normal attribute)
var $tipo;					// (normal attribute)
var $detalle;  			// (normal attribute)
var $sucursales;  	// (normal attribute)
var $valor;   			// (normal attribute)
var $stock_inicial;	// (normal attribute)
var $vigencia_desde;// (normal attribute)
var $vigencia_hasta;// (normal attribute)
var $estado;  		 	// (normal attribute)
var $origen;   			// (normal attribute)
var $garantia;   		// (normal attribute)
var $destacado;   	// (normal attribute)

var $database; 			// Instance of class database

//CONSTRUCTOR METHOD
//==================================================================================================
function premio() {$this->database = new Database();}

//GETTER METHODS
//==================================================================================================
function getIdpremio() 			{return $this->idpremio;}
function getIdcampania() 		{return $this->idcampania;}
function getNombre() 				{return $this->nombre;}
function getImagen() 				{return $this->imagen;}
function getTipo()					{return $this->tipo;}
function getDetalle() 			{return $this->detalle;}
function getSucursales() 		{return $this->sucursales;}
function getValor() 				{return $this->valor;}
function getStock_inicial()	{return $this->stock_inicial;}
function getVigencia_desde(){return $this->vigencia_desde;}
function getVigencia_hasta(){return $this->vigencia_hasta;}
function getEstado()				{return $this->estado;}
function getOrigen() 				{return $this->origen;}
function getGarantia() 			{return $this->garantia;}
function getDestacado() 		{return $this->destacado;}

// SETTER METHODS
//==================================================================================================
function setIdpremio($val) 			{$this->idpremio =  $val;}
function setIdcampania($val) 		{$this->idcampania =  $val;}
function setNombre($val) 				{$this->nombre =  $val;}
function setImagen($val) 				{$this->imagen =  $val;}
function setTipo($val)	  			{$this->tipo =  $val;}
function setDetalle($val) 			{$this->detalle =  $val;}
function setSucursales($val) 		{$this->sucursales =  $val;}
function setValor($val) 				{$this->valor =  $val;}
function setStock_inicial($val)	{$this->stock_inicial =  $val;}
function setVigencia_desde($val){$this->vigencia_desde =  $val;}
function setVigencia_hasta($val){$this->vigencia_hasta =  $val;}
function setEstado($val) 				{$this->estado =  $val;}
function setOrigen($val) 				{$this->origen =  $val;}
function setGarantia($val) 			{$this->garantia =  $val;}
function setDestacado($val) 		{$this->destacado =  $val;}

//SELECT METHOD / LOAD
//==================================================================================================
function select($id) {
	$sql 		= "SELECT pr.*, DATE_FORMAT(pr.vigencia_desde, '%d/%m/%Y') as vigencia_desde, DATE_FORMAT(pr.vigencia_hasta, '%d/%m/%Y') as vigencia_hasta FROM premios pr WHERE pr.idpremio = '$id'";
	$result = $this->database->query($sql);
	$result = $this->database->result;
	if($row = mysql_fetch_object($result)){
		$this->idpremio 			= $row->idpremio;
		$this->idcampania 		= $row->idcampania;
		$this->nombre 				= $row->nombre;
		$this->imagen 				= $row->imagen;
		$this->tipo						= $row->tipo;
		$this->detalle 				= $row->detalle;
		$this->sucursales 		= $row->sucursales;
		$this->valor 					= $row->valor;
		$this->stock_inicial	= $row->stock_inicial;
		$this->vigencia_desde	= $row->vigencia_desde;
		$this->vigencia_hasta	= $row->vigencia_hasta;
		$this->estado 				= $row->estado;
		$this->origen 				= $row->origen;
		$this->garantia 			= $row->garantia;
		$this->destacado 			= $row->destacado;
		return true;
	} 
	else	return false;
}

function dameCodigo($idpremio, $idcliente) {
	$sql 		= "SELECT codigo, idcodigo FROM premios_codigos WHERE disponible = 1 and idpremio = '$idpremio'";
	$result = $this->database->query($sql);
	$result = $this->database->result;
	if($row = mysql_fetch_object($result)) {
		$codigo 	= $row->codigo;
		$idcodigo	= $row->idcodigo;

		$sql = "UPDATE premios_codigos SET disponible = 0, idcliente = '$idcliente', fechauso = '".date("Y-m-d")."' WHERE idcodigo = '$idcodigo'";
		$result = $this->database->query($sql);

		return $codigo;
	}	
	return '';
}

//DELETE
//==================================================================================================
function delete($id) {
	$sql = "DELETE FROM premios WHERE idpremio = '$id'";
	$result = $this->database->query($sql);
	return true;
}

function delete_todo($id) {
	$sql = "DELETE FROM premios_codigos WHERE idpremio = '$id'";
	$result = $this->database->query($sql);
	$sql1 = "DELETE FROM premios WHERE idpremio = '$id'";
	$result1 = $this->database->query($sql1);
	return true;
}

// INSERT
//==================================================================================================
function insert() {
	$this->idpremio = ''; // clear key for autoincrement
	$this->prepare();
	$sql = "INSERT INTO premios (idcampania,nombre,imagen,tipo,detalle,sucursales,valor,stock_inicial,vigencia_desde,vigencia_hasta,estado,origen,garantia,destacado) VALUES ($this->idcampania,$this->nombre,$this->imagen,$this->tipo,$this->detalle,$this->sucursales,$this->valor,$this->stock_inicial,STR_TO_DATE(".$this->vigencia_desde.", '%d/%m/%Y'),STR_TO_DATE(".$this->vigencia_hasta.", '%d/%m/%Y'),$this->estado,$this->origen,$this->garantia,$this->destacado)";
	$result = $this->database->query($sql);
	$this->idpremio = mysql_insert_id($this->database->link);
	return true;
}

// UPDATE
//==================================================================================================
function update($id) {
	$this->prepare();
	$sql = "UPDATE premios SET idcampania = $this->idcampania,nombre = $this->nombre,imagen = $this->imagen,tipo = $this->tipo,detalle = $this->detalle,sucursales = $this->sucursales,valor = $this->valor,stock_inicial = $this->stock_inicial,vigencia_desde = STR_TO_DATE(".$this->vigencia_desde.", '%d/%m/%Y'),vigencia_hasta = STR_TO_DATE(".$this->vigencia_hasta.", '%d/%m/%Y'),estado = $this->estado,origen = $this->origen,garantia = $this->garantia WHERE idpremio = '$id'";
	$result = $this->database->query($sql);
	return true;
}

function cambiaEstado($id,$destacado){
	$sql = "UPDATE premios SET destacado = '$destacado' WHERE idpremio = '$id'";
	$result = $this->database->query($sql);
	return true;
}

function cambiaPublicado($id,$estado){
	$sql = "UPDATE premios SET estado = '$estado' WHERE idpremio = '$id'"; 
	$result = $this->database->query($sql);
	return true;
}

function getStockReal($id) {
	$sql 		= "SELECT stock_inicial FROM premios WHERE idpremio = '$id'";
	$result = $this->database->query($sql);
	$result = $this->database->result;
	$stock_inicial = '';
	if($row = mysql_fetch_object($result))
		$stock_inicial 	= $row->stock_inicial;
	if($stock_inicial == '') return '-LIBRE-';
	
	$sql2 	= "SELECT COUNT(idcanje) as canjes FROM canjes WHERE idpremio = '$id' and estado <> 'anulado' and estado <> 'devuelto'";
	$result = $this->database->query($sql2);
	$result = $this->database->result;
	$canjes = 0;
	if($row = mysql_fetch_object($result))
		$canjes 	= $row->canjes;
	
	$stock_real = $stock_inicial - $canjes;
	if($stock_real > 0) return $stock_real;	else return 0;	
}

function estaEnGrupo($idpremio, $idgrupo) {
	$sql 		= "SELECT * FROM premios_grupos WHERE idpremio = '$idpremio' and idgrupo = '$idgrupo'";
	$result = $this->database->query($sql);
	$result = $this->database->result;	
	if($row = mysql_fetch_object($result))
		return true;
	else
		return false;
}

//========================================================================================
function prepare(){
	if($this->idcampania != '')			$this->idcampania			= '\''.$this->idcampania.'\'';			else $this->idcampania			= 'NULL';
	if($this->nombre != '')					$this->nombre					= '\''.$this->nombre.'\'';					else $this->nombre					= 'NULL';
	if($this->imagen != '')					$this->imagen					= '\''.$this->imagen.'\'';					else $this->imagen					= 'NULL';
	if($this->tipo != '')						$this->tipo						= '\''.$this->tipo.'\'';						else $this->tipo						= 'NULL';
	if($this->detalle != '')				$this->detalle				= '\''.$this->detalle.'\'';					else $this->detalle					= 'NULL';
	if($this->sucursales != '')			$this->sucursales			= '\''.$this->sucursales.'\'';			else $this->sucursales			= 'NULL';
	if($this->valor != '')					$this->valor					= '\''.$this->valor.'\'';						else $this->valor						= 'NULL';
	if($this->stock_inicial != '')	$this->stock_inicial	= '\''.$this->stock_inicial.'\'';		else $this->stock_inicial		= 'NULL';
	if($this->vigencia_desde != '')	$this->vigencia_desde	= '\''.$this->vigencia_desde.'\'';	else $this->vigencia_desde	= 'NULL';
	if($this->vigencia_hasta!= '')	$this->vigencia_hasta	= '\''.$this->vigencia_hasta.'\'';	else $this->vigencia_hasta	= 'NULL';
	if($this->estado!= '')					$this->estado					= '\''.$this->estado.'\'';					else $this->estado					= 'NULL';
	if($this->origen!= '')					$this->origen					= '\''.$this->origen.'\'';					else $this->origen					= 'NULL';
	if($this->garantia!= '')				$this->garantia				= '\''.$this->garantia.'\'';				else $this->garantia				= 'NULL';
	if($this->destacado!= '')				$this->destacado			= '\''.$this->destacado.'\'';				else $this->destacado				= 'N';
}

function getCategoria(){
	$this->database = new Database();
	$categoria = '';

	$sql = "select nombre from campanias where idcampania = '$this->idcampania'";

	$result = $this->database->query($sql);
	$result = $this->database->result;
	if ($row = mysql_fetch_object($result))	{
		$categoria = $row->nombre;
	}
	//retornar el arreglo
	return $categoria;
}
// class : end
}
?>