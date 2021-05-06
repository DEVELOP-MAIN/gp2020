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
function ErrorFunc()
{
	$('listado').innerHTML = 'Error al conectar con el php';	
}

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// realiza la búsqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) 
	{			
		b = $F('filtro_buscar');
		t = $F('filtro_tipo');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b+'&t='+t;	
		var url = serverRoot+'php/buscar_operadores.php';
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
function ArmarListado(requester)
{
	$('titulosecciones').hide();
	$('titulo').show();
	$('listado').show();	
	$('listado').innerHTML = requester.responseText;
	$(document.body).stopWaiting();
}

//muestra el resultado de la busqueda
function muestraResultado() 
{
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
	$('frmtitulo').innerHTML = 'AGREGAR NUEVO OPERADOR';
	$('textoayuda').innerHTML = 'Ingrese los datos del nuevo operador.<br /><br />Los campos con asterisco son obligatorios.<br /><br />El sistema verificar&#225; que el nombre de usuario elegido no exista previamente en el sistema.<br />&#160;';
	$('btneditar').hide();	
	$('btneliminar').hide();	
	$('btnagregar').show();	
}

function showEditForm(idoperador) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DEL OPERADOR';
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar los datos del operador seleccionado, recuerde cargar toda la informaci&#243;n posible.<br /><br />Los campos con asterisco son obligatorios.<br /><br />El sistema verificar&#225; que el nombre de usuario elegido no exista previamente en el sistema.<br />&#160;';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btneliminar').hide();	
	$('btnagregar').hide();
	$('idop').value = idoperador;
	traeDatosOperador(idoperador);
}

function showDeleteForm(idoperador) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'ELIMINAR USUARIO DEL SISTEMA';
	$('textoayuda').innerHTML = '&#191;Realmente desea eliminar este usuario del sistema?<br />&#160;';	
	Form.disable($('frm_buscador'));
	$('btneditar').hide();	
	$('btneliminar').show();	
	$('btnagregar').hide();
	$('idop').value = idoperador;
	traeDatosOperador(idoperador);
}

//busca los datos del usuario elegido en la DDBB
function traeDatosOperador(idoperador)
{
	var pars = '?c='+idoperador;
	var url = serverRoot+'php/datos_operador.php';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: poneDatosOperador,
		onFailure: ErrorFunc
	});		
}

function poneDatosOperador(requester) 
{
	//inicializo las variables		
	var edt_op_tipo				= '';
	var edt_op_usuario	= '';
	var edt_op_clave			= '';
	
	//recupero todo en variables
	var xml_operadores = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];		
	
	if(xml_operadores.getElementsByTagName('tipo')[0].childNodes.length > 0)
		edt_op_tipo = xml_operadores.getElementsByTagName('tipo')[0].childNodes[0].nodeValue;		
	if(xml_operadores.getElementsByTagName('usuario')[0].childNodes.length > 0)
		edt_op_usuario = xml_operadores.getElementsByTagName('usuario')[0].childNodes[0].nodeValue;		
	if(xml_operadores.getElementsByTagName('clave')[0].childNodes.length > 0)
		edt_op_clave = xml_operadores.getElementsByTagName('clave')[0].childNodes[0].nodeValue;
	
	//pongo los valores en el formulario		
	if(edt_op_tipo!='')				$('frm_alta_tipo').value			= edt_op_tipo;
	if(edt_op_usuario!='')	$('frm_alta_usuario').value	= edt_op_usuario;
	if(edt_op_clave!='') 			$('frm_alta_clave').value 		= edt_op_clave;		
}

function verificarAlta() 
{
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) 
	{	
  	pars = '?'+Form.serialize($('frm_alta'));
		Form.disable('frm_alta');				
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		var url = serverRoot+'php/alta_operador.php';
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

function verificarEdicion() 
{
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) 
	{		
		pars = '?'+Form.serialize($('frm_alta'));
		Form.disable('frm_alta');				
		$('alta_asistencia').hide();		
		$('alta_verificando').show();				
		var url = serverRoot+'php/modificacion_operador.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'get', 
			parameters: pars, 
			onComplete: vuelveDelModificar,
			onFailure: ErrorFunc
		});					
	}
}									

function vuelveDelModificar(requester) 
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

function verificarEliminar() 
{
	pars = '?'+Form.serialize($('frm_alta'));
	Form.disable('frm_alta');				
	$('alta_asistencia').hide();	
	$('alta_verificando').show();					
	var url = serverRoot+'php/eliminar_operador.php';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: vuelveDelEliminar,
		onFailure: ErrorFunc
	});					
}									

function vuelveDelEliminar(requester) 
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

function Cancelar() 
{
  Form.enable('frm_alta');				
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();  
  $('alta').hide();	
  muestraResultado();
}																	

//funciones de paginacion
function paginar(pag) 
{
	pagina = pag;
	buscar();
}

function todos(pag) 
{
	pagina = 0;
	cantidad = 10000;
	buscar();
}

function paginado(pag) 
{
	pagina = 0;
	cantidad = 10;
	buscar();
}
