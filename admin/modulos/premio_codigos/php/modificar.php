<?php
require_once '../../../php/class/class.codigo.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idcodigo']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$idcodigo = $_POST['idcodigo'];
$cdg = new codigo();

if($cdg->select($idcodigo))
{
	$idpremio = $cdg->getIdpremio();
	if(isset($_POST['frm_alta_codigo']))	
	{
		$codigo = validaVars($_POST['frm_alta_codigo']); 
		$cdg->setCodigo($codigo);
	}
	
	if($cdg->validaInsert($idpremio,$_POST['frm_alta_codigo']))
	{
		if($cdg->update($idcodigo))
			echo '<script>parent.vuelveDeEdicion(1);</script>';	
		else
			echo '<script>parent.vuelveDeEdicion(0);</script>';
	}
	else echo '<script>parent.vuelveDeEdicion(4);</script>';
}
else	echo '<script>parent.vuelveDeEdicion(3);</script>';
?>