<?php
require_once '../../../php/class/class.premio.php';
require_once '../../../php/class/class.grupos_premio.php';
require_once '../../../php/class/class.simpleimage.php';
require_once '../../../php/minixml/minixml.inc.php';
require_once '../../../php/generales.php';

//decodifico desde utf-8
$_POST = decodePOST($_POST);

//verifico que lleguen bien los parametros obligatorios
if(!isset($_POST['idpremio']))	{echo '<script>parent.vuelveDeEdicion(2);</script>';	exit;}	

$imagen = '';
if(isset($_FILES['frm_alta_imagen']) && $_FILES['frm_alta_imagen']['tmp_name']!='')
{
	$fname = $_FILES['frm_alta_imagen']['name'];
	$ext = substr(strrchr($fname, '.'), 0);
	$imagen = date('dmYHis').'_'.$fname;
	move_uploaded_file($_FILES['frm_alta_imagen']['tmp_name'], '../../../../archivos/'.$imagen);
	/*Si deseo cambiar el tamaño de la imagen
	$image = new SimpleImage();
	$image->load('../../../../archivos/'.$imagen);
	$image->resize(567,426);
	$image->save('../../../../archivos/'.$imagen);
	$image->resize(83,62);
	$image->save('../../../../archivos/tn_'.$imagen);*/
}

$idpremio	= $_POST['idpremio'];
$grupos 		= $_POST['idgrupos'];
$prm = new premio();

if($prm->select($idpremio))
{	
	if(isset($_POST['frm_alta_tipo']))										$prm->setTipo($_POST['frm_alta_tipo']);
	if(isset($_POST['frm_alta_nombre']))							$prm->setNombre($_POST['frm_alta_nombre']);
	if(isset($_POST['frm_alta_nombre_ch']))					$prm->setNombre_ch($_POST['frm_alta_nombre_ch']);
	if(isset($_POST['frm_alta_campania']))						$prm->setIdcampania($_POST['frm_alta_campania']);
	if($imagen != '') 																							$prm->setImagen($imagen);
	if(isset($_POST['frm_alta_detalle']))								$prm->setDetalle($_POST['frm_alta_detalle']);
	if(isset($_POST['frm_alta_detalle_ch']))					$prm->setDetalle_ch($_POST['frm_alta_detalle_ch']);
	if(isset($_POST['frm_alta_vigencia_desde']))	$prm->setVigencia_desde($_POST['frm_alta_vigencia_desde']);
	if(isset($_POST['frm_alta_vigencia_hasta']))		$prm->setVigencia_hasta($_POST['frm_alta_vigencia_hasta']);
	if(isset($_POST['frm_alta_puntos']))								$prm->setValor($_POST['frm_alta_puntos']);
	if(isset($_POST['frm_alta_stock']))									$prm->setStock_inicial($_POST['frm_alta_stock']);
	if(isset($_POST['frm_alta_chances']))							$prm->setChances($_POST['frm_alta_chances']);
	if(isset($_POST['frm_alta_estado']))								$prm->setEstado($_POST['frm_alta_estado']);
	if(isset($_POST['frm_alta_origen']))								$prm->setOrigen($_POST['frm_alta_origen']);
	if(isset($_POST['frm_alta_garantia']))							$prm->setGarantia($_POST['frm_alta_garantia']);
	
	if($prm->update($idpremio))
	{	
		if($grupos!='')
		{
			$gp = new grupos_premio();
			//primero borro todos los grupos ligados al premio (borrón y cuenta nueva)
			$gp->deleteXpremio($idpremio);
			//luego ingreso nuevamente los grupos elegidos
			$g = preg_split('/\|/',$grupos);
			$nro_g = count($g);
			for($i=0;$i<$nro_g;$i++)
			{
				$idgrupo = $g[$i];
				if($idgrupo!='')
				{
					$gp->setIdgrupo($idgrupo);	
					$gp->setIdpremio($idpremio);
					$gp->insert();
				}
			}
		}
		echo '<script>parent.vuelveDeEdicion(1);</script>';	
	}	
	else echo '<script>parent.vuelveDeEdicion(0);</script>';
}
else	echo '<script>parent.vuelveDeEdicion(3);</script>';
?>