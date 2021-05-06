//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  			= 0;
var cantidad		= 10;

function inicia() {
	validaconeccion();	
}

function iniciaModulo() {
	buscar();
}

//error al conectar con el webservice
function ErrorFunc(){
	$('listado').innerHTML = 'Error al conectar con el php';	
}

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------
//realiza la búsqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) 
	{			
		e	= $F('filtro_estado');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&e='+e;	
		var url = serverRoot+'php/buscar_mensajes.php';
		$(document.body).startWaiting('bigWaiting');
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

// FUNCIONES DEL FORMULARIO DE ALTA ----------------------------------------------------------------------------------------------
function showAddForm() {
	$('frm_alta').reset();
	$('buscador').hide();
	$('ayuda').show();
	$('listado').hide();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'AGREGAR NUEVO MENSAJE';
	$('textoayuda').innerHTML = 'Ingrese los datos requeridos del nuevo mensaje.<br />&#160;';
	$('btneditar').hide();	
	$('btneliminar').hide();	
	$('btnagregar').show();	
}

function showEditForm(idmensaje) {		
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').hide();	
	$('respuesta').show();	
	$('frmtitulo').innerHTML = 'RESPONDER MENSAJE';
	$('textoayuda').innerHTML = "Aqu&#237; puede ver los datos del mensaje seleccionado y generar una respuesta que ser&#225; enviada al email especificado en el mensaje<br />&#160;";
	Form.disable($('frm_buscador'));		
	$('idmensaje_rta').value = idmensaje;
	traeDatosMensaje(idmensaje);	
}

function showDeleteForm(idmensaje) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'ELIMINAR MENSAJE';
	$('textoayuda').innerHTML = "&#191;Realmente desea eliminar este mensaje del listado?<br />&#160;";	
	Form.disable($('frm_buscador'));
	$('btneditar').hide();	
	$('btneliminar').show();	
	$('btnagregar').hide();
	$('idmensaje').value = idmensaje;
	traeDatosMensaje(idmensaje);
}

//busca los datos del mensaje elegido en la DDBB
function traeDatosMensaje(idmensaje){
	var pars = '?c='+idmensaje;
	var url = serverRoot+'php/datos_mensaje.php';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: poneDatosMensaje,
		onFailure: ErrorFunc
	});		
}

function poneDatosMensaje(requester){
	$('data_mensaje').innerHTML = requester.responseText;
}

function verificarAlta(){
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) 
	{	
  	pars = '?'+Form.serialize($('frm_alta'));
		Form.disable('frm_alta');				
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		var url = serverRoot+'php/alta_mensaje.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'get', 
			parameters: pars, 
			onComplete: vuelveDelAlta,
			onFailure: ErrorFunc
		});					
	}
}									

function vuelveDelAlta(requester) 
{
	if (requester.responseXML!=null) 
	{
		var result = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('resultado').nodeValue;
		var mensaje = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('mensaje').nodeValue;
		
		Form.enable('frm_alta');				
		$('alta_verificando').hide();			
		$('alta_asistencia').show();				
		if(parseInt(result)) 
			Cancelar();
		else
			alert(mensaje);
	}	
	else alert('Resultado no v\u00e1lido');
}

function verificarResponder(){
	var valid = new Validation('frm_respuesta', {onSubmit:false});
	if(valid.validate()) {		
		pars = '?'+Form.serialize($('frm_respuesta'));
		Form.disable('frm_respuesta');				
		$('alta_asistencia').hide();		
		$('alta_verificando').show();				
		var url = serverRoot+'php/modificacion_mensaje.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'post', 
			parameters: pars, 
			onComplete: vuelveDelResponder,
			onFailure: ErrorFunc
		});					
	}
}									

function vuelveDelResponder(requester){
	$('alta_verificando').hide();			
	$('alta_asistencia').show();				
	
	if (requester.responseXML!=null)
	{
		var result = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('resultado').nodeValue;
		var mensaje = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('mensaje').nodeValue;
		
		Form.enable('frm_respuesta');				
		
		if(parseInt(result)) 
			Cancelar();
		else
			alert(mensaje);
	}
	else	{		
		alert('Resultado no v\u00e1lido');
	}	
}

function verificarEliminar(idmensaje) 
{	if(confirm('\u00bfSeguro desea eliminar este mensaje del listado?')){
		$('idmensaje').value = idmensaje;
		var pars = '?'+Form.serialize($('frm_alta'));
		Form.disable('frm_alta');				
		$('alta_asistencia').hide();	
		$('alta_verificando').show();					
		var url = serverRoot+'php/eliminar_mensaje.php';
		
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'get', 
			parameters: pars, 
			onComplete: vuelveDelEliminar,
			onFailure: ErrorFunc
		});					
	}
}									

function vuelveDelEliminar(requester){
	if (requester.responseXML!=null)
	{
		var result = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('resultado').nodeValue;
		var mensaje = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('mensaje').nodeValue;
		Form.enable('frm_alta');				
		$('alta_verificando').hide();			
		$('alta_asistencia').show();				
		if(parseInt(result)) 
			Cancelar();
		else
			alert(mensaje);
	}
	else	alert('Resultado no v\u00e1lido');
}

function Cancelar(){
  Form.enable('frm_alta');				
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();  
  $('frm_respuesta').reset();  
  $('alta').hide();	
  $('respuesta').hide();	
  muestraResultado();
}																	

//funciones de paginacion
function paginar(pag){
	pagina = pag;
	buscar();
}

function todos(pag){
	pagina = 0;
	cantidad = 10000;
	buscar();
}

function paginado(pag){
	pagina = 0;
	cantidad = 10;
	buscar();
}