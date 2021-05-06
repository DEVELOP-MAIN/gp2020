<?php
/*------------------------------------------------------
CLASSNAME:        		mensaje
GENERATION DATE:	06.02.2017
CLASS FILE:       			class.mensaje.php
FOR MYSQL TABLE: 	mensajes
FOR MYSQL DB:     		aper_quilmes
--------------------------------------------------------
CODE GENERATED BY: dixernet
------------------------------------------------------*/

include_once('class.database.php');
include_once('class.phpmailer.php');

//CLASS DECLARATION
//==============================================================================================
class mensaje
{ // class : begin

//ATTRIBUTE DECLARATION
//==============================================================================================
var $idmensaje; // KEY ATTR. WITH AUTOINCREMENT

var $mensaje;  	// (normal attribute)
var $fecha;   			// (normal attribute)
var $estado;   		// (normal attribute)
var $idcliente; 		// (normal attribute)

var $database;	// Instance of class database

//CONSTRUCTOR METHOD
//==============================================================================================
function mensaje()
{
	$this->database = new Database();
}

//GETTER METHODS
//==============================================================================================
function getIdmensaje()	{return $this->idmensaje;}
function getMensaje() 		{return $this->mensaje;}
function getFecha() 				{return $this->fecha;}
function getEstado() 				{return $this->estado;}
function getIdcliente() 		{return $this->idcliente;}

// SETTER METHODS
//==============================================================================================
function setIdmensaje($val)	{$this->idmensaje =  $val;}
function setMensaje($val) 		{$this->mensaje =  $val;}
function setFecha($val) 				{$this->fecha =  $val;}
function setEstado($val) 			{$this->estado =  $val;}
function setIdcliente($val) 		{$this->idcliente =  $val;}

//SELECT METHOD / LOAD
//==============================================================================================
function select($id)
{
	$sql 		= "SELECT * FROM mensajes WHERE idmensaje = '$id'";
	$result = $this->database->query($sql);
	$result = $this->database->result;
	if($row = mysql_fetch_object($result)) 
	{
		$this->idmensaje	= $row->idmensaje;
		$this->mensaje 		= $row->mensaje;
		$this->fecha 					= $row->fecha;
		$this->estado 				= $row->estado;
		$this->idcliente 			= $row->idcliente;
		return true;
	} else	
		return false;
}

//DELETE
//==============================================================================================
function delete($id)
{
	$sql = "DELETE FROM mensajes WHERE idmensaje = '$id'";
	$result = $this->database->query($sql);
	return true;
}

// INSERT
//==============================================================================================
function insert()
{
	$this->idmensaje = ''; // clear key for autoincrement
	$this->prepare();
	$sql = "INSERT INTO mensajes (mensaje,fecha,estado,idcliente) VALUES ($this->mensaje,'".date('Y-m-d H:i:s')."',$this->estado,$this->idcliente)";
	$result = $this->database->query($sql);
	$this->idmensaje = mysql_insert_id($this->database->link);
	return true;
}

// UPDATE
//==============================================================================================
function update($id)
{
	$this->prepare();
	$sql = "UPDATE mensajes SET mensaje = $this->mensaje,estado = $this->estado WHERE idmensaje = '$id'";
	$result = $this->database->query($sql);
	return true;
}

//PREPARA PARA INSERTAR O UPDETEAR
//====================================================================================================
function prepare()
{
 	if($this->mensaje != '')	$this->mensaje	= '\''.$this->mensaje.'\'';	else $this->mensaje	= 'NULL';
	if($this->fecha != '')				$this->fecha			= '\''.$this->fecha.'\'';				else $this->fecha 			= 'NULL';
	if($this->estado != '')		$this->estado		= '\''.$this->estado.'\'';		else $this->estado 		= 'NULL';
	if($this->idcliente	!= '')		$this->idcliente	= '\''.$this->idcliente.'\'';	else $this->idcliente	= 'NULL';
}

//====================================================================================================
function sendRespuesta($mailto) 
{
	$pathfile = '../respuesta.html';
	$fh = fopen($pathfile, 'r');		
	$cuerpo = file_get_contents($pathfile);
	$respuesta = str_replace('\n', '<br />', $this->mensaje);
	$respuesta = substr($respuesta, 1);
	$respuesta = substr($respuesta, 0, -1);
	$cuerpo = str_replace('<#RESPUESTA#>', $respuesta, $cuerpo);	
	
	$mail = new PHPMailer();
	
	$mail->isSMTP(); 
	$mail->Host     				= MAIL_HOST; 
	$mail->SMTPAuth			= MAIL_SMTP_AUTH;
	$mail->Username		= MAIL_USERNAME;
	$mail->Password 		= MAIL_PASSWORD;
	$mail->SMTPSecure = MAIL_SMTP_SECURE;
	$mail->Port     					= MAIL_PORT;
			
	$mail->SetFrom('mercadoidealq@gmail.com', 'Mercado Ideal');
	$mail->AddReplyTo('mercadoidealq@gmail.com', 'Mercado Ideal');	
	$mail->AddAddress($mailto);		
	$mail->Subject = 'Respuesta a tu consulta en Mercado Ideal';
	
	$mail->isHTML(true);   
	$mail->Body = $cuerpo;
	$mail->MsgHTML($cuerpo);
	
	//echo $cuerpo; exit;
	$mail->send();
	
	return true;
}

// class : end
}
?>