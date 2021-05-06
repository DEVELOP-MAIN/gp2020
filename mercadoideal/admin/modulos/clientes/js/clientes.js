//DEFINICION VARIABLES
var serverRoot			= '';
var pagina  					= 0;
var cantidad				= 10;
var gbl_id						= '';
var gbl_localidad	= '';

function inicia() {
	validaconeccion();	
}

function iniciaModulo() {
	gbl_id 	= getQueryVariable('id');	
	if(gral_tipo!='V') {
		$('btn_agregar').show();
		$('btn_agregar_csv').show();
	}
	buscar();
	traeDatosProvincias();
	traeDatosGrupos();
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

function traeDatosProvincias(){
	var url = serverRoot+'php/provincias.php';
	var pars = '';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmaBuscadorProvincias,
		onFailure: ErrorFunc
	});	
}

//pone los datos recuperados en el combo de nombres de provincias
function ArmaBuscadorProvincias(requester){
	$('frm_alta_domicilio_provincia').options.length = 1;
	xml_provincias = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('provincias');
	var nro = xml_provincias.length;
	for (i=0; i<nro; i++)
	{
		//tomo el id y el nombre de las provincias
		id					= xml_provincias[i].attributes.getNamedItem('id').nodeValue;
		display	= xml_provincias[i].attributes.getNamedItem('display').nodeValue;			
		//agregar al select las opciones
		var newOption1 = new Option(display, id);
		len1 = $('frm_alta_domicilio_provincia').options.length;
		$('frm_alta_domicilio_provincia').options[len1] = newOption1;
	}
	cambiaLocalidad('Buenos Aires');
}

function cambiaLocalidad(valor){
	provincia = valor;
	var url = serverRoot+'php/localidades.php?p='+provincia;
	var pars = '';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmaBuscadorLocalidades,
		onFailure: ErrorFunc
	});	
}

//pone los datos recuperados en el combo de nombres de localidades de una provincia
function ArmaBuscadorLocalidades(requester) {
	$('frm_alta_domicilio_localidad').options.length = 1;
	xml_localidades = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('localidades');
	var nro = xml_localidades.length;
	for (i=0; i<nro; i++)
	{
		//tomo el id y el nombre de las localidades
		id					= xml_localidades[i].attributes.getNamedItem('id').nodeValue;
		display	= xml_localidades[i].attributes.getNamedItem('display').nodeValue;			
		//agregar al select las opciones
		var newOption1 = new Option(display, id);
		len1 = $('frm_alta_domicilio_localidad').options.length;
		$('frm_alta_domicilio_localidad').options[len1] = newOption1;
		if(gbl_localidad!='')	$('frm_alta_domicilio_localidad').value = gbl_localidad;
	}
}

function cambiaEstado(idcliente,estado){
	if(confirm('\u00bfRealmente desea cambiar el estado de este supermercado?'))
	{
		var random = aleatorio(1,20);
		var pars = '?idc='+idcliente+'_'+random+'&e='+estado;
		var url = serverRoot+'php/cambia_estado.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'get', 
			parameters: pars, 
			onComplete: vuelveDelEstado,
			onFailure: ErrorFunc
		});	
	}
	else
		muestraResultado();
}

function vuelveDelEstado(requester){
	if (requester.responseXML!=null)
	{
		var result = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('resultado').nodeValue;
		var mensaje = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('mensaje').nodeValue;
		
		if(parseInt(result))
			buscar();
		else
			alert(mensaje);
	}
	else	alert('Resultado no v\u00e1lido');
}

function traeDatosGrupos(){
	var url = serverRoot+'php/grupos.php';
	var pars = '';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmaBuscadorGrupos,
		onFailure: ErrorFunc
	});	
}

//pone los datos recuperados en el combo de nombres de grupos
function ArmaBuscadorGrupos(requester){
	$('filtro_grupo').options.length = 1;
	$('frm_alta_grupo').options.length = 1;
	xml_grupos = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('grupos');
	var nro = xml_grupos.length;
	for (i=0; i<nro; i++)
	{
		//tomo el id y el nombre de las grupos
		id					= xml_grupos[i].attributes.getNamedItem('id').nodeValue;
		display	= xml_grupos[i].attributes.getNamedItem('display').nodeValue;			
		//agregar a los select las opciones
		var newOption1 = new Option(display, id);
		len1 = $('filtro_grupo').options.length;
		$('filtro_grupo').options[len1] = newOption1;
		var newOption2 = new Option(display, id);
		len2 = $('frm_alta_grupo').options.length;
		$('frm_alta_grupo').options[len2] = newOption2;
	}
}

function abre_importador_sups() {
	var urlopen = 'php/importacion_sups.php';
	window.open(urlopen);
}

function abre_importador_puntos(idcliente) {
	var urlopen = 'php/importacion_puntos.php?c='+idcliente;
	window.open(urlopen);
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
		b 	= $F('filtro_buscar');
		st	= $F('filtro_estado');
		g	= $F('filtro_grupo');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b+'&idclnt='+gbl_id+'&st='+st+'&g='+g;	
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
	$('frmtitulo').innerHTML = 'AGREGAR SUPERMERCADO';
	$('textoayuda').innerHTML = 'Ingrese los datos del nuevo supermercado.<br /><br />Preste atenci&#243;n a los campos obligatorios indicados con asterisco.<br /><br />&#160;';
	$('btneditar').hide();	
	$('btnagregar').show();
}

function showEditForm(idcliente) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DEL SUPERMERCADO';
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar los datos del supermercado seleccionado.<br /><br />Preste atenci&#243;n a los campos obligatorios indicados con asterisco.<br />&#160;';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idcliente').value = idcliente;
	traeDatos(idcliente);
}

//busca los datos del supermercado elegido en la DDBB
function traeDatos(idcliente){
	var pars = '?c='+idcliente;
	var url = serverRoot+'php/datos_cliente.php';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: poneDatos,
		onFailure: ErrorFunc
	});		
}

function poneDatos(requester)
{
	var grupo													= '';
	var codigo_cliente						= '';
	var codigo_unico							= '';
	var razon_social							= '';
	var nombre											= '';
	var apellido											= '';
	var clave													= '';
	var email													= '';
	var cuit														= '';
	var domicilio											= '';
	var domicilio_provincia			= '';
	var domicilio_localidad			= '';
	var domicilio_cp								= '';
	var tel_movil										= '';
	var tel_otro											= '';
	var acepta_basesycond	= '';
	var estado												= '';
			
	var xml_clientes = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];		
	if(xml_clientes.getElementsByTagName('grupo')[0].childNodes.length > 0)
		grupo = xml_clientes.getElementsByTagName('grupo')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('codigo_cliente')[0].childNodes.length > 0)
		codigo_cliente = xml_clientes.getElementsByTagName('codigo_cliente')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('codigo_unico')[0].childNodes.length > 0)
		codigo_unico = xml_clientes.getElementsByTagName('codigo_unico')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('razon_social')[0].childNodes.length > 0)
		razon_social = xml_clientes.getElementsByTagName('razon_social')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('nombre')[0].childNodes.length > 0)
		nombre = xml_clientes.getElementsByTagName('nombre')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('apellido')[0].childNodes.length > 0)
		apellido = xml_clientes.getElementsByTagName('apellido')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('clave')[0].childNodes.length > 0)
		clave = xml_clientes.getElementsByTagName('clave')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('email')[0].childNodes.length > 0)
		email = xml_clientes.getElementsByTagName('email')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('cuit')[0].childNodes.length > 0)
		cuit = xml_clientes.getElementsByTagName('cuit')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('domicilio')[0].childNodes.length > 0)
		domicilio = xml_clientes.getElementsByTagName('domicilio')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('domicilio_provincia')[0].childNodes.length > 0)
		domicilio_provincia = xml_clientes.getElementsByTagName('domicilio_provincia')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('domicilio_localidad')[0].childNodes.length > 0)
		domicilio_localidad = xml_clientes.getElementsByTagName('domicilio_localidad')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('domicilio_cp')[0].childNodes.length > 0)
		domicilio_cp = xml_clientes.getElementsByTagName('domicilio_cp')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('tel_movil')[0].childNodes.length > 0)
		tel_movil = xml_clientes.getElementsByTagName('tel_movil')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('tel_otro')[0].childNodes.length > 0)
		tel_otro = xml_clientes.getElementsByTagName('tel_otro')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('acepta_basesycond')[0].childNodes.length > 0)
		acepta_basesycond = xml_clientes.getElementsByTagName('acepta_basesycond')[0].childNodes[0].nodeValue;		
	if(xml_clientes.getElementsByTagName('estado')[0].childNodes.length > 0)
		estado = xml_clientes.getElementsByTagName('estado')[0].childNodes[0].nodeValue;		
	
	//pongo los valores en el formulario	
	if(grupo!='')													$('frm_alta_grupo').value 										= grupo;
	if(codigo_cliente!='')							
	{	
		$('frm_alta_codigo_cliente').value 				= codigo_cliente;
		//$('frm_alta_codigo_cliente').readOnly	= true;
	}	
	if(codigo_unico!='')								
	{	
		$('frm_alta_codigo_unico').value 				= codigo_unico;
		$('frm_alta_codigo_unico').readOnly	= true;
	}	
	if(estado!='')												$('frm_alta_estado').value 									= estado;
	if(razon_social!='')								$('frm_alta_razon_social').value 					= razon_social;
	if(nombre!='')												$('frm_alta_nombre').value 									= nombre;
	if(apellido!='')												$('frm_alta_apellido').value 									= apellido;
	if(clave!='')														$('frm_alta_clave').value 											= clave;
	if(email!='')														$('frm_alta_email').value 											= email;
	if(cuit!='')															$('frm_alta_cuit').value 												= cuit;
	if(domicilio!='')											$('frm_alta_domicilio').value 								= domicilio;
	if(domicilio_provincia!='')			$('frm_alta_domicilio_provincia').value	= domicilio_provincia;
	if(domicilio_localidad!='')			gbl_localidad = domicilio_localidad;
	cambiaLocalidad(domicilio_provincia);		
	if(domicilio_cp!='')									$('frm_alta_domicilio_cp').value 						= domicilio_cp;
	if(tel_movil!='')											$('frm_alta_tel_movil').value 								= tel_movil;
	if(tel_otro!='')												$('frm_alta_tel_otro').value 									= tel_otro;
	if(acepta_basesycond=='1')	$('acepta_basesycond').checked 					= true;
}

function verificarAlta() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		$('frm_alta').action = 'php/alta.php';
		$('frm_alta').target = 'cliente_upload_target';
		$('frm_alta').submit();
		Form.disable('frm_alta');				
	}	
}

function vuelveDelAlta(resultado) {
	Form.enable('frm_alta');				
	$('alta_verificando').hide();			
	$('alta_asistencia').show();				
	if(resultado == '0')
		alert('No se ha podido cargar este supermercado, por favor int\u00e9ntelo m\u00e1s tarde');
	if(resultado == '1') 
		Cancelar();
	if(resultado == '2')
		alert('Faltan datos en el env\u00edo');
	if(resultado == '3')
		alert('Ya existe otro supermercado activo con el mismo C\u00f3digo \u00danico');
}

function verificarEdicion() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	$('alta_asistencia').hide();
	$('alta_verificando').show();				
	$('frm_alta').action = 'php/modificar.php';
	$('frm_alta').target = 'cliente_upload_target';
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
		alert('Ha habido un error ya que no se encuentra el supermercado que desea modificar');	
	if(resultado == '0')
		alert('En este momento no se puede modificar este supermercado, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
  $('frm_alta_codigo_cliente').readOnly	= false;
	$('frm_alta_codigo_unico').readOnly		= false;
	Form.enable('frm_alta');				
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();  
  $('alta').hide();	
  muestraResultado();
}																	

function showDeleteForm(idcliente) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar este supermercado?')) 
	{
		var pars = '?idcliente='+idcliente;
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

//apertura de las planillas de excel
function showPlanillaSups(){
	b 	= $F('filtro_buscar');
	st	= $F('filtro_estado');
	document.location = 'php/planilla_clientes.php?b='+b+'&st='+st;	
}

function showPlanillaPuntos(){
	b 	= $F('filtro_buscar');
	st	= $F('filtro_estado');
	document.location = 'php/planilla_puntos.php?b='+b+'&st='+st;	
}

//------------------------------------------------------------------------------------------------------------------------------------
function aleatorio(inferior,superior){ 
	numPosibilidades = superior - inferior; 
	aleat = Math.random() * numPosibilidades; 
	aleat = Math.round(aleat); 
	return parseInt(inferior) + aleat; 
} 
