//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  			= 0;
var cantidad		= 50;
var glb_id				= 0;
var glb_np				= 0;

function inicia() {
	glb_id 	= getQueryVariable('idp');
	glb_np	= getQueryVariable('np');
	$('idpremio').value = glb_id;	
	$('nombre_premio').innerHTML = unescape(glb_np);
	validaconeccion();	
}

function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split('&');
  for (var i=0;i<vars.length;i++) 
	{
    var pair = vars[i].split('=');
    if (pair[0] == variable) {
      return pair[1];
    }
  }   
}

function volver(){
	//document.location ='../premios/?id='+glb_id;
	document.location ='../premios/';
}

function iniciaModulo() {
	buscar();
}

//error al conectar con el webservice
function ErrorFunc(){
	$('listado').innerHTML = 'Error al conectar con el php';	
}

function abreimportador() {
	var urlopen = 'php/importacion.php?idp='+glb_id;
	window.open(urlopen);
}

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// realiza la busqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) {			
		b	= $F('filtro_buscar');
		d	= $F('filtro_disponible');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b+'&d='+d+'&c='+glb_id;	
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

//muestra el resultado de la busqueda
function muestraResultado(){
	var valid = new Validation('frm_alta', {onSubmit:false});
	valid.reset();
	buscar();
	$('listado').show();
	$('buscador').show();
}

// FUNCIONES DEL FORMULARIO DE ALTA ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function showAddForm() {
	$('frm_alta').reset();
	$('buscador').hide();
	$('ayuda').show();
	$('listado').hide();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'AGREGAR CODIGO';
	$('textoayuda').innerHTML = 'Ingrese el texto del nuevo c&#243;digo.<br />';
	$('btneditar').hide();	
	$('btnagregar').show();
}

function showEditForm(idcodigo) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR TEXTO DEL CODIGO';
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar el codigo seleccionado.<br /><br />&#160;';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idcodigo').value = idcodigo;
	traeDatos(idcodigo);
}

//busca los datos del codigo elegido en la DDBB
function traeDatos(idcodigo){
	var pars = '?c='+idcodigo;
	var url = serverRoot+'php/datos_codigo.php';
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
	var codigo	= '';
	
	var xml_codigos = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];		
	if(xml_codigos.getElementsByTagName('codigo')[0].childNodes.length > 0)
		codigo = xml_codigos.getElementsByTagName('codigo')[0].childNodes[0].nodeValue;		
		
	//pongo los valores en el formulario		
	if(codigo!="")	$('frm_alta_codigo').value	= codigo;
}

function verificarAlta() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		$('frm_alta').action = 'php/alta.php';
		$('frm_alta').target = 'codigo_upload_target';
		$('frm_alta').submit();
		Form.disable('frm_alta');				
	}	
}

function vuelveDelAlta(resultado) {
	Form.enable('frm_alta');				
	$('alta_verificando').hide();			
	$('alta_asistencia').show();				
	if(resultado == '0')	alert('No se ha podido cargar este c\u00f3digo, por favor int\u00e9ntelo m\u00e1s tarde');
	if(resultado == '1')	Cancelar();
	if(resultado == '2')	alert('Faltan datos en el env\u00edo');
	if(resultado == '3')	alert('Ya existe este c\u00f3digo para este premio');
}

function verificarEdicion() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		$('frm_alta').action = 'php/modificar.php';
		$('frm_alta').target = 'codigo_upload_target';
		$('frm_alta').submit();
		Form.disable('frm_alta');
	}	
}

function vuelveDeEdicion(resultado) {
	Form.enable('frm_alta');				
	$('alta_verificando').hide();			
	$('alta_asistencia').show();				
	if(resultado == '0')	alert('En este momento no se puede modificar el c\u00f3digo, por favor int\u00e9ntelo m\u00e1s tarde');
	if(resultado == '1')	Cancelar();
	if(resultado == '2')	alert('Faltan datos en el env\u00edo');
	if(resultado == '3')	alert('Ha habido un error ya que no se encuentra el c\u00f3digo que desea modificar');	
	if(resultado == '4')	alert('Ya existe este c\u00f3digo para este premio');	
}

function Cancelar() {
  Form.enable('frm_alta');				
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();  
  $('alta').hide();	
  muestraResultado();
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

function showDeleteForm(idcodigo) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar este c\u00f3digo?')) 
	{
		var pars = '?idcodigo='+idcodigo;
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