//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  			= 0;
var cantidad		= 10;

function inicia() {
	validaconeccion();	
}

function iniciaModulo() {
	var dpck_desde = new DatePicker({
     relative	: 'filtro_fecha_desde',
     language	: 'sp',
		 keepFieldEmpty: true
	});
  dpck_desde.load();
  var dpck_hasta = new DatePicker({
     relative	: 'filtro_fecha_hasta',
     language	: 'sp',
		 keepFieldEmpty: true
	});
  dpck_hasta.load();
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
		b	= $F('filtro_buscar');
		e	= $F('filtro_estado');
		d 	= $F('filtro_fecha_desde');
		h	= $F('filtro_fecha_hasta');
		t 	= $F('filtro_tipo');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b+'&e='+e+'&d='+d+'&h='+h+'&t='+t;
		var url = serverRoot+'php/buscar_canjes.php';
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
	
	//prettyPhoto dentro del Armar listado
	$j("area[rel^='prettyPhoto']").prettyPhoto();
	$j(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:3000, social_tools: false, autoplay_slideshow: false});
	$j(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'facebook',slideshow:3000, social_tools: false, autoplay_slideshow: false});
}

//muestra el resultado de la busqueda
function muestraResultado() 
{
	buscar();
	$('listado').show();
	$('buscador').show();
}

function modificaEstado(id, estado) {
	if(confirm('\u00bfEst\u00e1 seguro de cambiar el estado del canje a '+estado.toUpperCase()+'?')) 
	{
		var pars = '?c='+id+'&e='+estado;
		var url = serverRoot+'php/modificar_canje.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'get', 
			parameters: pars, 
			onComplete: vuelveDeEdicion,
			onFailure: ErrorFunc
		});	
	}	
}

function vuelveDeEdicion(requester) {
	if (requester.responseXML!=null)
	{
		var result = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('resultado').nodeValue;
		var mensaje = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('mensaje').nodeValue;
		if(parseInt(result)) {			 
			var apellido = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('apellido').nodeValue;
			var estadonuevo = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('estadonuevo').nodeValue;
			$('filtro_buscar').value = apellido;			
			$('filtro_estado').value = estadonuevo;
			buscar();
		}
		else alert(mensaje);
	}
	else alert('Resultado no v\u00e1lido');
}

function Cancelar() {
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
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
	cantidad = 10;
	buscar();
}

//apertura de la planilla de excel
function showPlanillaCanjes(){
	b 	= $F('filtro_buscar');
	e	= $F('filtro_estado');
	d 	= $F('filtro_fecha_desde');
	h	= $F('filtro_fecha_hasta');
	t 	= $F('filtro_tipo');
	document.location = 'php/planilla_canjes.php?b='+b+'&e='+e+'&d='+d+'&h='+h+'&t='+t;	
}

//apertura de la planilla de excel
function showPlanillaCanjesSolicitados(){
	document.location = 'php/planilla_canjes_solicitados.php';	
	buscar();
}
