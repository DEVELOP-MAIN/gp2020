//DEFINICION VARIABLES
var serverRoot		= '';
var pagina  			= 0;
var cantidad			= 50;
var glbl_id				=	0;
var glbl_nusr			= '';
var glbl_volver_a	= '';

function inicia() {
	validaconeccion();	
}

function iniciaModulo(){
	var dpck_filtro_desde = new DatePicker({
		 relative	: 'filtro_desde',
		 language	: 'sp',
		 keepFieldEmpty: true
	});
	dpck_filtro_desde.load();
	
	var dpck_filtro_hasta = new DatePicker({
		relative	: 'filtro_hasta',
		language	: 'sp',
		keepFieldEmpty: true
	});	
	dpck_filtro_hasta.load();
	
	glbl_id 			= getQueryVariable('c');
	glbl_nusr			= getQueryVariable('n');
	glbl_volver_a	= getQueryVariable('v');

	$('idusuario').value = glbl_id;
	$('user').innerHTML = unescape(glbl_nusr);	
	buscar();
}

function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split('&');
  for (var i=0;i<vars.length;i++){
    var pair = vars[i].split('=');
    if (pair[0] == variable) {
      return pair[1];
    }
  }
}

function volver(){	
	document.location = '../'+glbl_volver_a+'/';
}

//error al conectar con el webservice
function ErrorFunc(){
	$('listado').innerHTML = 'Error al conectar con el php';	
}

// FUNCIONES DEL LISTADO ---------------------------------------------------------------------------------------------------------
// realiza la b�squeda
function buscar(){
	d = $F('filtro_desde');
	h = $F('filtro_hasta');
	var pars = '?idusr='+glbl_id+'&d='+d+'&h='+h;
	var url = serverRoot+'php/buscar.php';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmarListado,
		onFailure: ErrorFunc
	});	
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

// FUNCIONES DEL FORMULARIO DE ALTA ---------------------------------------------------------------------------------------------
function showAddForm() {
	$('frm_alta').reset();
	$('buscador').hide();
	$('ayuda').show();
	$('listado').hide();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'AGREGAR MOVIMIENTO MANUAL A ' + unescape(glbl_nusr);
	$('textoayuda').innerHTML = 'Ingrese los datos del nuevo movimiento.</br></br>El campo <strong>millas</strong> es obligatorio y puede tener signo <strong>-</strong> si se trata de una quita de millas.</br></br>&#160;';
	$('btneditar').hide();	
	$('btnagregar').show();

	var now = new Date();
	var month = (now.getMonth() + 1);               
	var day = now.getDate();
	if (month < 10) 
	    month = "0" + month;
	if (day < 10) 
	    day = "0" + day;
	var today = now.getFullYear() + '-' + month + '-' + day;
	document.getElementById('fecha_movimiento').value = today;
}

function showEditForm(idingreso) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DEL MOVIMIENTO'
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar los datos del movimiento.</br></br>El campo <strong>millas</strong> es obligatorio y puede tener signo <strong>-</strong> si se trata de una quita de millas.</br></br>&#160;';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idingreso').value = idingreso;
	traeDatos(idingreso);
}

//busca los datos del movimiento manual elegido en la DDBB
function traeDatos(idingreso){
	var pars = 'c='+idingreso;
	
	var url = serverRoot+'php/datos_movimiento.php';
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
	var puntos				= '';
	var motivo				= '';
	var observaciones	= '';
		
	var xml_movimientos = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];		
	if(xml_movimientos.getElementsByTagName('puntos')[0].childNodes.length > 0)
		puntos = xml_movimientos.getElementsByTagName('puntos')[0].childNodes[0].nodeValue;		
	if(xml_movimientos.getElementsByTagName('observaciones')[0].childNodes.length > 0)
		observaciones = xml_movimientos.getElementsByTagName('observaciones')[0].childNodes[0].nodeValue;		
	if(xml_movimientos.getElementsByTagName('motivo')[0].childNodes.length > 0)
		motivo = xml_movimientos.getElementsByTagName('motivo')[0].childNodes[0].nodeValue;		
	
	//pongo los valores en el formulario	
	if(puntos!='')				$('frm_alta_puntos').value 				= puntos;
	if(motivo!='')				$('frm_alta_motivo').value				= motivo;
	if(observaciones!='')	$('frm_alta_observaciones').value	= observaciones;
}

function verificarAlta() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();
		$('frm_alta').action = 'php/alta.php';
		$('frm_alta').target = 'movimiento_upload_target';
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
		alert('No se ha podido cargar este movimiento, por favor int\u00e9ntelo m\u00e1s tarde');
}

function verificarEdicion() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	$('alta_asistencia').hide();
	$('alta_verificando').show();
	$('frm_alta').action = 'php/modificar.php';
	$('frm_alta').target = 'movimiento_upload_target';
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
		alert('Ha habido un error: no se encuentra el movimiento que desea modificar');	
	if(resultado == '0')
		alert('En este momento no se puede modificar este movimiento, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
  Form.enable('frm_alta');
  $('ayuda').hide();
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();
  $('alta').hide();
  muestraResultado();
}

function showDeleteForm(idingreso) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar este movimiento?')){
		var pars = 'idingreso='+idingreso;
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
	cantidad = 50;
	buscar();
}

function resetPagina(){
	pagina = 0;	
}

//-------------------------------------------------------------------------------------------------------------------------
function aleatorio(inferior,superior){ 
	numPosibilidades = superior - inferior; 
	aleat = Math.random() * numPosibilidades; 
	aleat = Math.round(aleat); 
	return parseInt(inferior) + aleat; 
} 