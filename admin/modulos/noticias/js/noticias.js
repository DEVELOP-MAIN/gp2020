//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  		= 0;
var cantidad		= 10;

function inicia() {
	validaconeccion();	
}

function iniciaModulo() {
	buscar();
}

function getQueryVariable(variable) {
  var result = '';
  var query = window.location.search.substring(1);
  var vars = query.split('&');
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split('=');
    if (pair[0] == variable) {
      return pair[1];
    }
  }
  return result;   
}

//error al conectar con el webservice
function ErrorFunc(){
	$('listado').innerHTML = 'Error al conectar con el php';	
}

// FUNCIONES DEL LISTADO -----------------------------------------------------------------------------------------------------------------------------
// realiza la búsqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()){
		b = $F('filtro_buscar');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b;
		var url = serverRoot+'php/buscar.php';
		$(document.body).startWaiting('waiting');
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'get', 
			parameters: pars, 
			onComplete: ArmarListado,
			onFailure: ErrorFunc
		});	
	}
}

//procesa la informacion del webservice
function ArmarListado(requester){
	$('titulosecciones').hide();
	$('titulo').show();
	$('listado').show();	
	$('listado').innerHTML = requester.responseText;
	$(document.body).stopWaiting();
}

//muestra el resultado de la búsqueda
function muestraResultado(){
	var valid = new Validation('frm_alta', {onSubmit:false});
	valid.reset();
	buscar();
	$('listado').show();
	$('buscador').show();
}

// FUNCIONES DEL FORMULARIO DE ALTA ------------------------------------------------------------------------------------------------------------------
function showAddForm() {
	$('frm_alta').reset();
	$('buscador').hide();
	$('ayuda').show();
	$('listado').hide();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'AGREGAR NOVEDAD';
	$('textoayuda').innerHTML = 'Ingrese los datos de la novedad.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br /><br />Puede agregar una imagen y/o un video pegando el c&#243;digo de inserci&#243;n del mismo.<br /><br />&#160;';
	$('btneditar').hide();	
	$('btnagregar').show();
}

function showEditForm(idnoticia) {
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();
	$('frmtitulo').innerHTML = 'EDITAR DATOS DE LA NOVEDAD';
	$('textoayuda').innerHTML = "Aqu&#237; puede modificar los datos de la novedad seleccionada.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br /><br />Puede agregar una imagen seleccionando un archivo y/o un video pegando el c&#243;digo de inserci&#243;n del mismo.<br /><br />&#160;";
	Form.disable($('frm_buscador'));
	$('btneditar').show();
	$('btnagregar').hide();
	$('idnoticia').value = idnoticia;
	traeDatos(idnoticia);
}

//busca los datos de la novedad elegida en la DDBB (tabla 'noticias')
function traeDatos(idnoticia){
	var pars = '?c='+idnoticia;
	var url = serverRoot+'php/datos_noticia.php';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: poneDatos,
		onFailure: ErrorFunc
	});		
}

function poneDatos(requester){
	var titulo	= '';
	var cuerpo	= '';
	var imagen	= '';
	var video		= '';

	var xml_noticias = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];
	if(xml_noticias.getElementsByTagName('titulo')[0].childNodes.length > 0)
		titulo = xml_noticias.getElementsByTagName('titulo')[0].childNodes[0].nodeValue;
	if(xml_noticias.getElementsByTagName('cuerpo')[0].childNodes.length > 0)
		cuerpo = xml_noticias.getElementsByTagName('cuerpo')[0].childNodes[0].nodeValue;
	if(xml_noticias.getElementsByTagName('imagen')[0].childNodes.length > 0)
		imagen = xml_noticias.getElementsByTagName('imagen')[0].childNodes[0].nodeValue;
	if(xml_noticias.getElementsByTagName('video')[0].childNodes.length > 0)
		video = xml_noticias.getElementsByTagName('video')[0].childNodes[0].nodeValue;

	//pongo los valores en el formulario	
	if(titulo!='')	$('frm_alta_titulo').value 	= titulo;
	if(cuerpo!='')	$('frm_alta_cuerpo').value	= cuerpo;
	if(imagen!='')	$('showImagen').innerHTML		= '<a href="../../../archivos/'+imagen+'" target="_blank"><img src="../../../archivos/'+imagen+'" target="_blank" width="75" border="0"></a>';
	if(video!='')		$('frm_alta_video').value		= video;
}

function verificarAlta() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();
		$('frm_alta').action = 'php/alta.php';
		$('frm_alta').target = 'novedad_upload_target';
		$('frm_alta').submit();
		Form.disable('frm_alta');
	}	
}

function vuelveDelAlta(resultado) {
	Form.enable('frm_alta');
	$('alta_verificando').hide();
	$('alta_asistencia').show();
	if(resultado == '1') 
		Cancelar();
	if(resultado == '2')
		alert('Faltan datos en el env\u00edo');
	if(resultado == '0')
		alert('No se ha podido cargar esta novedad, por favor int\u00e9ntelo m\u00e1s tarde');
}

function verificarEdicion() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	$('alta_asistencia').hide();
	$('alta_verificando').show();
	$('frm_alta').action = 'php/modificar.php';
	$('frm_alta').target = 'novedad_upload_target';
	$('frm_alta').submit();
	Form.disable('frm_alta');
}

function vuelveDeEdicion(resultado) {
	Form.enable('frm_alta');
	$('alta_verificando').hide();
	$('alta_asistencia').show();
	if(resultado == '1')
		Cancelar();
	if(resultado == '2')
		alert('Faltan datos en el env\u00edo');
	if(resultado == '3')
		alert('Ha habido un error: no se encuentra la novedad que desea modificar');
	if(resultado == '0')
		alert('En este momento no se puede modificar esta novedad, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
  $('showImagen').innerHTML = '';
  Form.enable('frm_alta');
  $('ayuda').hide();
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();
  $('alta').hide();
  muestraResultado();
}

function showDeleteForm(idnoticia) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar esta novedad?')){
		var pars = '?idnoticia='+idnoticia;
		var url = serverRoot+'php/eliminar.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'post', 
			parameters: pars, 
			onComplete: buscar,
			onFailure: ErrorFunc
		});
	}	
}

//funciones de paginacion
function paginar(pag) {
	pagina = pag;
	buscar();
}

function todos(pag) {
	pagina = 0;
	cantidad = 10000;
	buscar();
}

function paginado(pag){
	pagina = 0;
	cantidad = 10;
	buscar();
}

function resetPagina(){
	pagina = 0;	
}