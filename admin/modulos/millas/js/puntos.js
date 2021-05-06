//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  		= 0;
var cantidad		= 50;

function inicia() {
	validaconeccion();
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
function ErrorFunc() {$('listado').innerHTML = 'Error al conectar con el php';	}

function abre_importador_puntos() {
	var urlopen = 'php/importacion_puntos_rapida.php';
	window.open(urlopen);
}

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------------
// realiza la búsqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) {
		d = $F('filtro_fecha_desde');
		h	= $F('filtro_fecha_hasta');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&d='+d+'&h='+h;
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

	//prettyPhoto dentro del ArmarListado
	$j("area[rel^='prettyPhoto']").prettyPhoto();

	$j(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:3000, social_tools: false, autoplay_slideshow: false});
	$j(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:3000, social_tools: false, autoplay_slideshow: false});

	$j("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
		custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
		changepicturecallback: function(){ initialize(); }
	});

	$j("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
		custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
		changepicturecallback: function(){ _bsap.exec(); }
	});
}

//muestra el resultado de la búsqueda
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
function showPlanillaSocios(){
	d = $F('filtro_fecha_desde');
	h	= $F('filtro_fecha_hasta');
	document.location = 'php/planilla_clientes.php?d='+d+'&h='+h;
}

function showPlanillaPuntos(){
	d = $F('filtro_fecha_desde');
	h	= $F('filtro_fecha_hasta');
	document.location = 'php/planilla_puntos.php?d='+d+'&h='+h;
}
