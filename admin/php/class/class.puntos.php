<?php
/*------------------------------------------------------
CLASSNAME:        puntos
GENERATION DATE:  15.07.2020
CLASS FILE:       class.puntos.php
FOR MYSQL TABLE:  puntos
FOR MYSQL DB:     aper_pg2020
--------------------------------------------------------
CODE GENERATED BY: dixernet
------------------------------------------------------*/

include_once('class.database.php');

//CLASS DECLARATION
//========================================================================================================
class puntos
{ // class : begin


//ATTRIBUTE DECLARATION
//========================================================================================================
var $identrada;   // KEY ATTR. WITH AUTOINCREMENT

var $idcliente;   // (normal attribute)
var $puntos;   // (normal attribute)
var $fecha;   // (normal attribute)
var $linea;   // (normal attribute)
var $observaciones;   // (normal attribute)
var $fecha_carga;   // (normal attribute)
var $tipo;   // (normal attribute)
var $directa;   // (normal attribute)
var $region;   // (normal attribute)
var $codigo;   // (normal attribute)
var $usuario;   // (normal attribute)
var $objetivo;   // (normal attribute)
var $avance;   // (normal attribute)
var $avance_por;   // (normal attribute)
var $ejecutivo;   // (normal attribute)
var $jefe;   // (normal attribute)
var $gerente;   // (normal attribute)
var $peso;   // (normal attribute)
var $millas_individuales;   // (normal attribute)
var $ranking_mes;   // (normal attribute)
var $ranking_total;   // (normal attribute)

var $database; // Instance of class database


//CONSTRUCTOR METHOD
//========================================================================================================
function puntos(){$this->database = new Database();}

//GETTER METHODS
//========================================================================================================
function getIdentrada(){return $this->identrada;}
function getIdcliente(){return $this->idcliente;}
function getPuntos(){return $this->puntos;}
function getFecha(){return $this->fecha;}
function getLinea(){return $this->linea;}
function getObservaciones(){return $this->observaciones;}
function getFecha_carga(){return $this->fecha_carga;}
function getTipo(){return $this->tipo;}
function getDirecta(){return $this->directa;}
function getRegion(){return $this->region;}
function getCodigo(){return $this->codigo;}
function getUsuario(){return $this->usuario;}
function getObjetivo(){return $this->objetivo;}
function getAvance(){return $this->avance;}
function getAvance_por(){return $this->avance_por;}
function getEjecutivo(){return $this->ejecutivo;}
function getJefe(){return $this->jefe;}
function getGerente(){return $this->gerente;}
function getPeso(){return $this->peso;}
function getMillas_individuales(){return $this->millas_individuales;}
function getRanking_mes(){return $this->ranking_mes;}
function getRanking_total(){return $this->ranking_total;}

// SETTER METHODS
//========================================================================================================
function setIdentrada($val){$this->identrada =  $val;}
function setIdcliente($val){$this->idcliente =  $val;}
function setPuntos($val){$this->puntos =  $val;}
function setFecha($val){$this->fecha =  $val;}
function setLinea($val){$this->linea =  $val;}
function setObservaciones($val){$this->observaciones =  $val;}
function setFecha_carga($val){$this->fecha_carga =  $val;}
function setTipo($val){$this->tipo =  $val;}
function setDirecta($val){$this->directa =  $val;}
function setRegion($val){$this->region =  $val;}
function setCodigo($val){$this->codigo =  $val;}
function setUsuario($val){$this->usuario =  $val;}
function setObjetivo($val){$this->objetivo =  $val;}
function setAvance($val){$this->avance =  $val;}
function setAvance_por($val){$this->avance_por =  $val;}
function setEjecutivo($val){$this->ejecutivo =  $val;}
function setJefe($val){$this->jefe =  $val;}
function setGerente($val){$this->gerente =  $val;}
function setPeso($val){$this->peso =  $val;}
function setMillas_individuales($val){$this->millas_individuales =  $val;}
function setRanking_mes($val){$this->ranking_mes =  $val;}
function setRanking_total($val){$this->ranking_total =  $val;}

//SELECT METHOD / LOAD
//========================================================================================================
function select($id){
	$sql =  "SELECT * FROM puntos WHERE identrada = '$id'";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	if($row = mysql_fetch_object($result)){
		$this->identrada = $row->identrada;
		$this->idcliente = $row->idcliente;
		$this->puntos = $row->puntos;
		$this->fecha = $row->fecha;
		$this->linea = $row->linea;
		$this->observaciones = $row->observaciones;
		$this->fecha_carga = $row->fecha_carga;
		$this->tipo = $row->tipo;
		$this->directa = $row->directa;
		$this->region = $row->region;
		$this->codigo = $row->codigo;
		$this->usuario = $row->usuario;
		$this->objetivo = $row->objetivo;
		$this->avance = $row->avance;
		$this->avance_por = $row->avance_por;
		$this->ejecutivo = $row->ejecutivo;
		$this->jefe = $row->jefe;
		$this->gerente = $row->gerente;
		$this->peso = $row->peso;
		$this->millas_individuales = $row->millas_individuales;
		$this->ranking_mes = $row->ranking_mes;
		$this->ranking_total = $row->ranking_total;
		return true;
	}
	else return false;
}

//DELETE
//========================================================================================================
function delete($id){
	$sql = "DELETE FROM puntos WHERE identrada = '$id'";
	$result = $this->database->query($sql);
	return true;
}

// INSERT
//========================================================================================================
function insert(){
	$this->identrada = ''; // clear key for autoincrement
	$this->prepare();
	$sql = "INSERT INTO puntos (idcliente,puntos,fecha,linea,observaciones,fecha_carga,tipo,directa,region,codigo,usuario,objetivo,avance,avance_por,ejecutivo,jefe,gerente,peso,millas_individuales,ranking_mes,ranking_total) VALUES ($this->idcliente,$this->puntos,$this->fecha,$this->linea,$this->observaciones,$this->fecha_carga,$this->tipo,$this->directa,$this->region,$this->codigo,$this->usuario,$this->objetivo,$this->avance,$this->avance_por,$this->ejecutivo,$this->jefe,$this->gerente,$this->peso,$this->millas_individuales,$this->ranking_mes,$this->ranking_total)";
	$result = $this->database->query($sql);

	$this->identrada = mysql_insert_id($this->database->link);
	return true;
}

// UPDATE
//========================================================================================================
function update($id){
	$this->prepare();
	$sql = "UPDATE puntos SET idcliente = $this->idcliente,puntos = $this->puntos,fecha = $this->fecha,linea = $this->linea,observaciones = $this->observaciones,fecha_carga = $this->fecha_carga,tipo = $this->tipo,directa = $this->directa,region = $this->region,codigo = $this->codigo,usuario = $this->usuario,objetivo = $this->objetivo,avance = $this->avance,avance_por = $this->avance_por,ejecutivo = $this->ejecutivo,jefe = $this->jefe,gerente = $this->gerente,peso = $this->peso,millas_individuales = $this->millas_individuales,ranking_mes = $this->ranking_mes,ranking_total = $this->ranking_total WHERE identrada = '$id'";
	$result = $this->database->query($sql);
	return true;
}

//========================================================================================================
function prepare(){
	$this->idcliente 						= str_replace("'", "", $this->idcliente);
	$this->puntos 							= str_replace("'", "", $this->puntos);
	$this->puntos								= str_replace(",", ".", $this->puntos);
	$this->fecha 								= str_replace("'", "", $this->fecha);
	$this->linea 								= str_replace("'", "", $this->linea);
	$this->observaciones				= str_replace("'", "", $this->observaciones);
	$this->fecha_carga 					= str_replace("'", "", $this->fecha_carga);
	$this->tipo 								= str_replace("'", "", $this->tipo);
	$this->directa 							= str_replace("'", "", $this->directa);
	$this->region 							= str_replace("'", "", $this->region);
	$this->codigo 							= str_replace("'", "", $this->codigo);
	$this->usuario		 					= str_replace("'", "", $this->usuario);
	$this->objetivo 						= str_replace("'", "", $this->objetivo);
	$this->objetivo							= str_replace(",", ".", $this->objetivo);
	$this->avance 							= str_replace("'", "", $this->avance);
	$this->avance								= str_replace(",", ".", $this->avance);
	$this->avance_por 					= str_replace("'", "", $this->avance_por);
	$this->avance_por 					= str_replace("%", "", $this->avance_por);
	$this->avance_por						= str_replace(",", ".", $this->avance_por);
	$this->ejecutivo 						= str_replace("'", "", $this->ejecutivo);
	$this->jefe 								= str_replace("'", "", $this->jefe);
	$this->gerente 							= str_replace("'", "", $this->gerente);
	$this->peso 								= str_replace("'", "", $this->peso);
	$this->peso 								= str_replace(",", ".", $this->peso);
	$this->peso 								= str_replace("%", "", $this->peso);
	$this->millas_individuales	= str_replace("'", "", $this->millas_individuales);
	$this->millas_individuales 	= str_replace(",", ".", $this->millas_individuales);
	$this->millas_individuales 	= str_replace("%", "", $this->millas_individuales);
	$this->ranking_mes 					= str_replace("'", "", $this->ranking_mes);
	$this->ranking_mes 					= str_replace(",", ".", $this->ranking_mes);
	$this->ranking_mes 					= str_replace("%", "", $this->ranking_mes);
	$this->ranking_total 				= str_replace("'", "", $this->ranking_total);
	$this->ranking_total 				= str_replace(",", ".", $this->ranking_total);
	$this->ranking_total 				= str_replace("%", "", $this->ranking_total);

	if($this->idcliente != '')			$this->idcliente			= '\''.$this->idcliente.'\'';			else $this->idcliente			= 'NULL';
	if($this->puntos != '')					$this->puntos					= '\''.$this->puntos.'\'';				else $this->puntos				= 'NULL';
	if($this->fecha != '')					$this->fecha					= '\''.$this->fecha.'\'';					else $this->fecha					= 'NULL';
	if($this->linea != '')					$this->linea					= '\''.$this->linea.'\'';					else $this->linea					= 'NULL';
	if($this->observaciones != '')	$this->observaciones	= '\''.$this->observaciones.'\'';	else $this->observaciones	= 'NULL';
	if($this->fecha_carga != '')		$this->fecha_carga		= '\''.$this->fecha_carga.'\'';		else $this->fecha_carga		= 'NULL';
	if($this->tipo != '')						$this->tipo						= '\''.$this->tipo.'\'';					else $this->tipo					= 'NULL';
	if($this->directa != '')				$this->directa				= '\''.$this->directa.'\'';				else $this->directa				= 'NULL';
	if($this->region != '')					$this->region					= '\''.$this->region.'\'';				else $this->region				= 'NULL';
	if($this->codigo != '')					$this->codigo					= '\''.$this->codigo.'\'';				else $this->codigo				= 'NULL';
	if($this->usuario != '')				$this->usuario				= '\''.$this->usuario.'\'';				else $this->usuario				= 'NULL';
	if($this->objetivo != '')				$this->objetivo				= '\''.$this->objetivo.'\'';			else $this->objetivo			= 'NULL';
	if($this->avance != '')					$this->avance					= '\''.$this->avance.'\'';				else $this->avance				= 'NULL';
	if($this->avance_por != '')			$this->avance_por			= '\''.$this->avance_por.'\'';		else $this->avance_por		= 'NULL';
	if($this->ejecutivo != '')			$this->ejecutivo			= '\''.$this->ejecutivo.'\'';			else $this->ejecutivo			= 'NULL';
	if($this->jefe != '')						$this->jefe						= '\''.$this->jefe.'\'';					else $this->jefe					= 'NULL';
	if($this->gerente != '')				$this->gerente				= '\''.$this->gerente.'\'';				else $this->gerente				= 'NULL';
	if($this->peso != '')						$this->peso						= '\''.$this->peso.'\'';					else $this->peso					= 'NULL';
	if($this->millas_individuales != '') $this->millas_individuales = '\''.$this->millas_individuales.'\''; else $this->millas_individuales = 'NULL';
	if($this->ranking_mes != '') $this->ranking_mes = '\''.$this->ranking_mes.'\''; else $this->ranking_mes = 'NULL';
	if($this->ranking_total != '') $this->ranking_total = '\''.$this->ranking_total.'\''; else $this->ranking_total = 'NULL';
}

function resetea(){
	$this->idcliente = '';
	$this->puntos = '';
	$this->fecha = '';
	$this->linea = '';
	$this->observaciones = '';
	$this->fecha_carga = '';
	$this->tipo = '';
	$this->directa = '';
	$this->region = '';
	$this->codigo = '';
	$this->usuario = '';
	$this->objetivo = '';
	$this->avance = '';
	$this->avance_por = '';
	$this->ejecutivo = '';
	$this->jefe = '';
	$this->gerente = '';
	$this->peso = '';
	$this->millas_individuales = '';
	$this->ranking_mes = '';
	$this->ranking_total = '';
}

// class : end
}
?>