//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  		= 0;
var cantidad		= 50;
var gbl_id			= '';

function inicia() {
	validaconeccion();	
}

function iniciaModulo() {
	gbl_id = getQueryVariable('id');
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
	$('listado').innerHTML = 'error al conectar con el php';	
}

// FUNCIONES DEL LISTADO -----------------------------------------------------------------------------------------------------------------------------
function limpiar(){
	gbl_id = '';
	buscar();
}

// realiza la búsqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) {
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
	$('frmtitulo').innerHTML = 'AGREGAR CATEGOR&#205;A';
	$('textoayuda').innerHTML = 'Ingrese el nombre de la nueva categor&#237;a.<br /><br />Este dato es obligatorio.<br />';
	$('btneditar').hide();	
	$('btnagregar').show();
}

function showEditForm(idcategoria) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DE LA CATEGOR&#205;A';
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar el nombre de la categor&#237;a seleccionada.<br /><br />Recuerde que este dato es obligatorio.<br />';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idcategoria').value = idcategoria;
	traeDatos(idcategoria);
}

//busca los datos de la categoria elegida en la DDBB (tabla 'campanias')
function traeDatos(idcategoria){
	var pars = '?c='+idcategoria;
	var url = serverRoot+'php/datos.php';
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
	var nombre	= '';	

	var xml_campanias = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];
	if(xml_campanias.getElementsByTagName('nombre')[0].childNodes.length > 0)
		nombre = xml_campanias.getElementsByTagName('nombre')[0].childNodes[0].nodeValue;

	//pongo los datos en el formulario
	if(nombre!='')	$('frm_alta_nombre').value = nombre;
}

function verificarAlta() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();
		$('frm_alta').action = 'php/alta.php';
		$('frm_alta').target = 'categoria_upload_target';
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
		alert('No se ha podido cargar esta categor\u00eda, por favor int\u00e9ntelo m\u00e1s tarde');
}

function verificarEdicion() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	$('alta_asistencia').hide();
	$('alta_verificando').show();
	$('frm_alta').action = 'php/modificar.php';
	$('frm_alta').target = 'categoria_upload_target';
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
		alert('Ha habido un error: no se encuentra la categor\u00eda que desea modificar');	
	if(resultado == '0')
		alert('En este momento no se puede modificar esta categor\u00eda, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
  Form.enable('frm_alta');
  $('ayuda').hide();
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();
  $('alta').hide();
  muestraResultado();
}

function showDeleteForm(idcategoria) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar esta categor\u00eda?')){
		var pars = '?idcategoria='+idcategoria;
		var url = serverRoot+'php/eliminar.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'post', 
			parameters: pars, 
			onComplete: vuelveEliminar,
			onFailure: ErrorFunc
		});
	}	
}

function vuelveEliminar(requester) {
	if(requester.responseText==1 || requester.responseText=='1') {
		buscar();
	} else {
		alert('La categor\u00eda no pudo eliminarse, compruebe que no hay premios en esta categor\u00eda ingresando al modulo de premios');
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
	cantidad = 50;
	buscar();
}

function resetPagina(){
	pagina = 0;	
}

//------------------------------------------------------------------------------------------------------------------------------------
function aleatorio(inferior,superior){ 
	numPosibilidades = superior - inferior; 
	aleat = Math.random() * numPosibilidades; 
	aleat = Math.round(aleat); 
	return parseInt(inferior) + aleat; 
} 
