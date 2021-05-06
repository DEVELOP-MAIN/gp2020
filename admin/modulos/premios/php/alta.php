<?php
require_once '../../../php/class/class.premio.php';
require_once '../../../php/class/class.grupos_premio.php';
require_once '../../../php/class/class.simpleimage.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['frm_alta_nombre'])) {echo '<script>parent.vuelveDelAlta(2);</script>';	exit;}	

$imagen = '';
if(isset($_FILES['frm_alta_imagen']) && $_FILES['frm_alta_imagen']['tmp_name']!=''){
	$fname = $_FILES['frm_alta_imagen']['name'];
	$ext = substr(strrchr($fname, '.'), 0);
	$imagen = date('dmYHis').'_'.$fname;
	move_uploaded_file($_FILES['frm_alta_imagen']['tmp_name'], '../../../../archivos/'.$imagen);
	/*
	Código para cambiar el tamaño de la imagen
	$image = new SimpleImage();
	$image->load('../../../../archivos/'.$imagen);
	$image->resize(567,426);
	$image->save('../../../../archivos/'.$imagen);
	$image->resize(83,62);
	$image->save('../../../../archivos/tn_'.$imagen);
	*/
}

$prm = new premio();

if(isset($_POST['frm_alta_tipo']))						$prm->setTipo($_POST['frm_alta_tipo']);
if(isset($_POST['frm_alta_nombre']))					$prm->setNombre($_POST['frm_alta_nombre']);
if(isset($_POST['frm_alta_campania']))				$prm->setIdcampania($_POST['frm_alta_campania']);
$prm->setImagen($imagen);
if(isset($_POST['frm_alta_detalle']))					$prm->setDetalle($_POST['frm_alta_detalle']);
if(isset($_POST['frm_alta_sucursales']))			$prm->setSucursales($_POST['frm_alta_sucursales']);
if(isset($_POST['frm_alta_vigencia_desde']))	$prm->setVigencia_desde($_POST['frm_alta_vigencia_desde']);
if(isset($_POST['frm_alta_vigencia_hasta']))	$prm->setVigencia_hasta($_POST['frm_alta_vigencia_hasta']);
if(isset($_POST['frm_alta_millas']))					$prm->setValor($_POST['frm_alta_millas']);
if(isset($_POST['frm_alta_stock']))						$prm->setStock_inicial($_POST['frm_alta_stock']);
if(isset($_POST['frm_alta_estado']))					$prm->setEstado($_POST['frm_alta_estado']);
//if(isset($_POST['frm_alta_origen']))				$prm->setOrigen($_POST['frm_alta_origen']);
//if(isset($_POST['frm_alta_garantia']))			$prm->setGarantia($_POST['frm_alta_garantia']);
$prm->setDestacado('N');

if($prm->insert()){
	$idpremio = $prm->getIdpremio();
	if(isset($_POST['idgrupos']) && $_POST['idgrupos']!='')	{
		$g = preg_split('/\|/',$_POST['idgrupos']);
		$nro_g = count($g);
		$gp = new grupos_premio();
		for($i=0;$i<$nro_g;$i++){
			$idgrupo = $g[$i];
			if($idgrupo != ''){
				$gp->setIdgrupo($idgrupo);	
				$gp->setIdpremio($idpremio);
				$gp->insert();
			}
		}
	}
	echo '<script>parent.vuelveDelAlta(1);</script>';	
}	
else	echo '<script>parent.vuelveDelAlta(0);</script>';
?>