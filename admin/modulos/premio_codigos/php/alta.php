<?php
require_once '../../../php/class/class.codigo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['frm_alta_codigo'])) {echo '<script>parent.vuelveDelAlta(2);</script>';	exit;}	

$cdg = new codigo();

if(isset($_POST['frm_alta_codigo']))
{
	$codigo = validaVars($_POST['frm_alta_codigo']); 
	$cdg->setCodigo($codigo);
}
if(isset($_POST['idpremio']))
{
	$idpremio = validaVars($_POST['idpremio']); 
	$cdg->setIdpremio($idpremio);
}

if($cdg->validaInsert($_POST['idpremio'],$_POST['frm_alta_codigo']))
{
	if($cdg->insert())
		echo '<script>parent.vuelveDelAlta(1);</script>';	
	else
		echo '<script>parent.vuelveDelAlta(0);</script>';
}
else echo '<script>parent.vuelveDelAlta(3);</script>';	
?>