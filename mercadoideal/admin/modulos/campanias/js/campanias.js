//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  			= 0;
var cantidad		= 10;
var gbl_id				= '';

function inicia() {
	validaconeccion();	
}

function iniciaModulo() {
	gbl_id = getQueryVariable('id');	
	/*var dpck_frm_alta_fecha_inicial = new DatePicker({
     relative	: 'frm_alta_fecha_inicial',
     language	: 'sp',
		 keepFieldEmpty: true
	});
  dpck_frm_alta_fecha_inicial.load();
	var dpck_frm_alta_fecha_final = new DatePicker({
     relative	: 'frm_alta_fecha_final',
     language	: 'sp',
		 keepFieldEmpty: true
	});
  dpck_frm_alta_fecha_final.load();*/
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

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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

// FUNCIONES DEL FORMULARIO DE ALTA ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function showAddForm() {
	$('frm_alta').reset();
	$('buscador').hide();
	$('ayuda').show();
	$('listado').hide();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'AGREGAR CAMPA&#209;A';
	$('textoayuda').innerHTML = 'Ingrese los datos de la nueva campa&#241;a.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br />';
	$('btneditar').hide();	
	$('btnagregar').show();
}

function showEditForm(idcampania) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DE LA CAMPA&#209;A';
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar los datos de la campa&#241;a seleccionado.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br />';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idcampania').value = idcampania;
	traeDatos(idcampania);
}

//busca los datos de la campaña elegida en la DDBB
function traeDatos(idcampania){
	var pars = '?c='+idcampania;
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
	var nombre				= '';	
	var nombre_ch	= '';	
	/*var fecha_inicial	= '';
	var fecha_final		= '';*/
			
	var xml_campanias = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];			
	if(xml_campanias.getElementsByTagName('nombre')[0].childNodes.length > 0)
		nombre = xml_campanias.getElementsByTagName('nombre')[0].childNodes[0].nodeValue;		
	if(xml_campanias.getElementsByTagName('nombre_ch')[0].childNodes.length > 0)
		nombre_ch = xml_campanias.getElementsByTagName('nombre_ch')[0].childNodes[0].nodeValue;		
	/*if(xml_campanias.getElementsByTagName('fecha_inicial')[0].childNodes.length > 0)
		fecha_inicial = xml_campanias.getElementsByTagName('fecha_inicial')[0].childNodes[0].nodeValue;		
	if(xml_campanias.getElementsByTagName('fecha_final')[0].childNodes.length > 0)
		fecha_final = xml_campanias.getElementsByTagName('fecha_final')[0].childNodes[0].nodeValue;	*/
	
	//pongo los puntoses en el formulario		
	if(nombre!='')					$('frm_alta_nombre').value 				= nombre;	
	if(nombre_ch!='')		$('frm_alta_nombre_ch').value 	= nombre_ch;	
	/*if(fecha_inicial!='')	$('frm_alta_fecha_inicial').value	= fecha_inicial;	
	if(fecha_final!='')		$('frm_alta_fecha_final').value 	= fecha_final;	*/
}

function verificarAlta() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		$('frm_alta').action = 'php/alta.php';
		$('frm_alta').target = 'campania_upload_target';
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
		alert('No se ha podido cargar esta campania, por favor int\u00e9ntelo m\u00e1s tarde');
}

function verificarEdicion() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	$('alta_asistencia').hide();
	$('alta_verificando').show();				
	$('frm_alta').action = 'php/modificar.php';
	$('frm_alta').target = 'campania_upload_target';
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
		alert('Ha habido un error ya que no se encuentra la campa\u00f1a que desea modificar');	
	if(resultado == '0')
		alert('En este momento no se puede modificar esta campa\u00f1a, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
  Form.enable('frm_alta');				
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();  
  $('alta').hide();	
  muestraResultado();
}																	

function showDeleteForm(idcampania) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar esta campa\u00f1a?')) 
	{
		var pars = '?idcampania='+idcampania;
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
	if(requester.responseText==1 || requester.responseText=="1") {
		buscar();
	} else {
		alert('La categoria no pudo eliminarse, compruebe que no hay premios en esta categoria ingresando al modulo de premios');
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

//------------------------------------------------------------------------------------------------------------------------------------
function aleatorio(inferior,superior){ 
	numPosibilidades = superior - inferior; 
	aleat = Math.random() * numPosibilidades; 
	aleat = Math.round(aleat); 
	return parseInt(inferior) + aleat; 
} 
