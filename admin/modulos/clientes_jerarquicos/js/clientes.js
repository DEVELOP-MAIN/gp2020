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
	traeDatosEjecutivos();
	traeDatosJefes();
	traeDatosGerentes();
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

function traeDatosEjecutivos(){
	var url = serverRoot+'php/ejecutivos.php';
	var pars = '';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmaBuscadorEjecutivos,
		onFailure: ErrorFunc
	});	
}

//pone los datos recuperados en el combo de nombres de usuarios con rango de ejecutivo
function ArmaBuscadorEjecutivos(requester){
	$('filtro_ejecutivo').options.length = 1;
	xml_ejecutivos = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('ejecutivos');
	var nro = xml_ejecutivos.length;
	for (i=0; i<nro; i++){
		//tomo el id y el nombre de los usuarios con rango ejecutivo
		id			= xml_ejecutivos[i].attributes.getNamedItem('id').nodeValue;
		display	= xml_ejecutivos[i].attributes.getNamedItem('display').nodeValue;			
		//agregar al select las opciones
		var newOption1 = new Option(display, id);
		len1 = $('filtro_ejecutivo').options.length;
		$('filtro_ejecutivo').options[len1] = newOption1;
	}
}

function traeDatosJefes(){
	var url = serverRoot+'php/jefes.php';
	var pars = '';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmaBuscadorJefes,
		onFailure: ErrorFunc
	});	
}

//pone los datos recuperados en el combo de nombres de usuarios con rango de jefe
function ArmaBuscadorJefes(requester){
	$('filtro_jefe').options.length = 1;
	xml_jefes = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('jefes');
	var nro = xml_jefes.length;
	for (i=0; i<nro; i++){
		//tomo el id y el nombre de los usuarios con rango jefe
		id			= xml_jefes[i].attributes.getNamedItem('id').nodeValue;
		display	= xml_jefes[i].attributes.getNamedItem('display').nodeValue;
		//agregar al select las opciones
		var newOption1 = new Option(display, id);
		len1 = $('filtro_jefe').options.length;
		$('filtro_jefe').options[len1] = newOption1;
	}
}

function traeDatosGerentes(){
	var url = serverRoot+'php/gerentes.php';
	var pars = '';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmaBuscadorGerentes,
		onFailure: ErrorFunc
	});	
}

//pone los datos recuperados en el combo de nombres de usuarios con rango de gerente
function ArmaBuscadorGerentes(requester){
	$('filtro_gerente').options.length = 1;
	xml_gerentes = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('gerentes');
	var nro = xml_gerentes.length;
	for (i=0; i<nro; i++){
		//tomo el id y el nombre de los usuarios con rango gerente
		id			= xml_gerentes[i].attributes.getNamedItem('id').nodeValue;
		display	= xml_gerentes[i].attributes.getNamedItem('display').nodeValue;
		//agregar al select las opciones
		var newOption1 = new Option(display, id);
		len1 = $('filtro_gerente').options.length;
		$('filtro_gerente').options[len1] = newOption1;
	}
}

function abre_importador_usuarios() {
	var urlopen = 'php/importacion_usuarios.php';
	window.open(urlopen);
}

function muestra_otras_regiones(valor){
	if(valor=='gerente'){
		$('otras_regiones').innerHTML = '<tr class="textoPetroleo12"><td colspan="2" align="left"><strong>Otras Regiones</strong>&#160;(separe con coma)</td></tr><tr class="textoPetroleo12"><td colspan="2" align="left" valign="top"><input id="frm_alta_otras_regiones" name="frm_alta_otras_regiones" type="text" class="textINPUT"></td></tr>';
		$('otras_regiones').style.display = 'block';
	}	else {
		$('otras_regiones').innerHTML = '';
		$('otras_regiones').style.display = 'none';
	}
}

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------------
function limpiar(){
	gbl_id = '';
	buscar();
}

// realiza la búsqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) {
		b 	= $F('filtro_buscar');
		rg	= $F('filtro_region');
		ej	= $F('filtro_ejecutivo');
		jf	= $F('filtro_jefe');
		gr	= $F('filtro_gerente');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b+'&idclnt='+gbl_id+'&rg='+rg+'&ej='+ej+'&jf='+jf+'&gr='+gr;
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
	$('frmtitulo').innerHTML = 'AGREGAR USUARIO';
	$('textoayuda').innerHTML = 'Ingrese los datos del nuevo usuario.<br /><br />Preste atenci&#243;n a los campos obligatorios indicados con asterisco.<br /><br />&#160;';
	$('btneditar').hide();	
	$('btnagregar').show();
}

function showEditForm(idcliente) {
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DEL USUARIO';
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar los datos del usuario seleccionado.<br /><br />Preste atenci&#243;n a los campos obligatorios indicados con asterisco.<br />&#160;';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idcliente').value = idcliente;
	traeDatos(idcliente);
}

//busca los datos del usuario elegido en la DDBB (tabla 'socios')
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

function poneDatos(requester){
	var region		= '';
	var codigo		= '';
	var rango			= '';
	var ejecutivo	= '';
	var jefe			= '';
	var gerente		= '';
	var nombre		= '';
	var apellido	= '';
	var email			= '';
	var clave			= '';
	var direccion	= '';
	var telefono	= '';

	var xml_clientes = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];
	if(xml_clientes.getElementsByTagName('region')[0].childNodes.length > 0)
		region = xml_clientes.getElementsByTagName('region')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('codigo')[0].childNodes.length > 0)
		codigo = xml_clientes.getElementsByTagName('codigo')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('rango')[0].childNodes.length > 0)
		rango = xml_clientes.getElementsByTagName('rango')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('ejecutivo')[0].childNodes.length > 0)
		ejecutivo = xml_clientes.getElementsByTagName('ejecutivo')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('jefe')[0].childNodes.length > 0)
		jefe = xml_clientes.getElementsByTagName('jefe')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('gerente')[0].childNodes.length > 0)
		gerente = xml_clientes.getElementsByTagName('gerente')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('nombre')[0].childNodes.length > 0)
		nombre = xml_clientes.getElementsByTagName('nombre')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('apellido')[0].childNodes.length > 0)
		apellido = xml_clientes.getElementsByTagName('apellido')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('email')[0].childNodes.length > 0)
		email = xml_clientes.getElementsByTagName('email')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('clave')[0].childNodes.length > 0)
		clave = xml_clientes.getElementsByTagName('clave')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('direccion')[0].childNodes.length > 0)
		direccion = xml_clientes.getElementsByTagName('direccion')[0].childNodes[0].nodeValue;
	if(xml_clientes.getElementsByTagName('telefono')[0].childNodes.length > 0)
		telefono = xml_clientes.getElementsByTagName('telefono')[0].childNodes[0].nodeValue;

	//pongo los valores en el formulario
	if(rango!='')	{
		$('frm_alta_rango').value = rango;
		//muestra_otras_regiones(rango);
	}
	if(region!=''){
		var otras_regiones = '';
		var vars = region.split(',');
  	var nro = vars.length;
		$('frm_alta_region').value = vars[0];
		if(nro>1){
			for (var i=1;i<nro;i++) {
				otras_regiones += vars[i]+',';
			}
			$('frm_alta_otras_regiones').value = otras_regiones;
		}
	}
	if(codigo!='')		$('frm_alta_codigo').value 		= codigo;
	if(ejecutivo!='')	$('frm_alta_ejecutivo').value	= ejecutivo;
	if(jefe!='')			$('frm_alta_jefe').value			= jefe;
	if(gerente!='')		$('frm_alta_gerente').value 	= gerente;
	if(nombre!='')		$('frm_alta_nombre').value 		= nombre;
	if(apellido!='')	$('frm_alta_apellido').value 	= apellido;
	if(email!='')			$('frm_alta_email').value 		= email;
	if(clave!='')			$('frm_alta_clave').value 		= clave;
	if(direccion!='')	$('frm_alta_direccion').value = direccion;
	if(telefono!='')	$('frm_alta_telefono').value 	= telefono;
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
		alert('No se ha podido cargar este usuario, por favor int\u00e9ntelo m\u00e1s tarde');
	if(resultado == '1') 
		Cancelar();
	if(resultado == '2')
		alert('Faltan datos en el env\u00edo');
	if(resultado == '3')
		alert('Ya existe otro usuario activo con el mismo C\u00f3digo \u00danico');
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
		alert('Ha habido un error ya que no se encuentra el usuario que desea modificar');	
	if(resultado == '0')
		alert('En este momento no se puede modificar este usuario, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
  Form.enable('frm_alta');
  $('ayuda').hide();
  Form.enable($('frm_buscador'));
  $('frm_alta').reset(); 
  $('alta').hide();
  muestraResultado();
}

function showDeleteForm(idcliente) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar este usuario?')) {
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
	cantidad = 50;
	buscar();
}

function resetPagina(){
	pagina = 0;	
}

//apertura de las planillas de excel
function showPlanillaUsuarios(){
	b 	= $F('filtro_buscar');
	rg	= $F('filtro_region');
	ej	= $F('filtro_ejecutivo');
	jf	= $F('filtro_jefe');
	gr	= $F('filtro_gerente');
	document.location = 'php/planilla_clientes.php?b='+b+'&rg='+rg+'&ej='+ej+'&jf='+jf+'&gr='+gr;
}

function showPlanillaPuntos(){
	b 	= $F('filtro_buscar');
	rg	= $F('filtro_region');
	ej	= $F('filtro_ejecutivo');
	jf	= $F('filtro_jefe');
	gr	= $F('filtro_gerente');
	document.location = 'php/planilla_puntos.php?b='+b+'&rg='+rg+'&ej='+ej+'&jf='+jf+'&gr='+gr;
}

//------------------------------------------------------------------------------------------------------------------------------------
function aleatorio(inferior,superior){ 
	numPosibilidades = superior - inferior; 
	aleat = Math.random() * numPosibilidades; 
	aleat = Math.round(aleat); 
	return parseInt(inferior) + aleat; 
} 
