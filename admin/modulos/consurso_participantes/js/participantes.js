//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  			= 0;
var cantidad		= 50;
var glb_id				= 0;
var glb_nc				= 0;

function inicia() {
	glb_id 	= getQueryVariable('idc');
	glb_nc	= getQueryVariable('nc');
	$('nombre_concurso').innerHTML = unescape(glb_nc);
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
	//document.location ='../concursos/?id='+glb_id;
	document.location ='../concursos/';
}

function iniciaModulo() {
	var dpck_filtro_fecha_desde = new DatePicker({
		relative	: 'filtro_fecha_desde',
		language	: 'sp',
		keepFieldEmpty: true
	});
  dpck_filtro_fecha_desde.load();
	
	var dpck_filtro_fecha_hasta = new DatePicker({
		relative	: 'filtro_fecha_hasta',
		language	: 'sp',
		keepFieldEmpty: true
	});
  dpck_filtro_fecha_hasta.load();
	
	buscar();
}

//error al conectar con el webservice
function ErrorFunc(){
	$('listado').innerHTML = 'Error al conectar con el php';	
}

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// realiza la busqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) {			
		d	= $F('filtro_fecha_desde');
		h	= $F('filtro_fecha_hasta');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&d='+d+'&h='+h+'&c='+glb_id;	
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

//apertura de las planillas de excel
function showPlanillaParticipantes(){
	d	= $F('filtro_fecha_desde');
	h	= $F('filtro_fecha_hasta');
	document.location = 'php/planilla_participantes.php?d='+d+'&h='+h+'&c='+glb_id;;	
}

