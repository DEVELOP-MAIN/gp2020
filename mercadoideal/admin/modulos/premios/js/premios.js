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
	var dpck_frm_alta_vigencia_desde = new DatePicker({
     relative	: 'frm_alta_vigencia_desde',
     language	: 'sp',
		 keepFieldEmpty: true
	});
  dpck_frm_alta_vigencia_desde.load();
	var dpck_frm_alta_vigencia_hasta = new DatePicker({
     relative	: 'frm_alta_vigencia_hasta',
     language	: 'sp',
		 keepFieldEmpty: true
	});
  dpck_frm_alta_vigencia_hasta.load();
	buscar();	
	traeDatosCampanias();
	//dameGrupos();
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

function traeDatosCampanias(){
	var url = serverRoot+'php/campanias.php';
	var pars = '';
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmaBuscadorCampanias,
		onFailure: ErrorFunc
	});	
}

//pone los datos recuperados en el combo de nombres de campañas
function ArmaBuscadorCampanias(requester){
	$('filtro_campania').options.length = 1;
	$('frm_alta_campania').options.length = 1;
	xml_campanias = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('campanias');
	var nro = xml_campanias.length;
	for (i=0; i<nro; i++)
	{
		//tomo el id y el nombre de las campanias
		id					= xml_campanias[i].attributes.getNamedItem('id').nodeValue;
		display	= xml_campanias[i].attributes.getNamedItem('display').nodeValue;			
		//agregar a los select las opciones
		var newOption1 = new Option(display, id);
		len1 = $('filtro_campania').options.length;
		$('filtro_campania').options[len1] = newOption1;
		var newOption2 = new Option(display, id);
		len2 = $('frm_alta_campania').options.length;
		$('frm_alta_campania').options[len2] = newOption2;
	}
}

function cambiaDestacado(idpremio,destacado){
	if(confirm('\u00bfRealmente desea hacer este cambio?'))
	{
		var random = aleatorio(1,20);
		var pars = '?idp='+idpremio+'_'+random+'&d='+destacado;
		var url = serverRoot+'php/cambia_destacado.php';
		var myAjax = new Ajax.Request(
		url, 
		{
			method: 'get', 
			parameters: pars, 
			onComplete: vuelveDelDestacado,
			onFailure: ErrorFunc
		});	
	}
	else
		muestraResultado();
}

function vuelveDelDestacado(requester){
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

function muestra_chances(valor){
	if(valor=='chances') $('input_chances').style.display = 'block'; else $('input_chances').style.display = 'none';
}

//genera el contenido del div que lista los grupos de clientes
function dameGrupos(){
	var pars = '';
	var url = serverRoot+'php/grupos.php';
	$(document.body).startWaiting('waiting');
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmarDivGrupos,
		onFailure: ErrorFunc
	});	
}

function dameGruposPremio(idpremio){
	var pars = '?idpremio='+idpremio;
	var url = serverRoot+'php/grupos_premio.php';
	$(document.body).startWaiting('waiting');
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: ArmarDivGrupos,
		onFailure: ErrorFunc
	});	
}

//procesa la informacion del webservice
function ArmarDivGrupos(requester){
	$('contenido').innerHTML += requester.responseText;
	$(document.body).stopWaiting();
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
		c = $F('filtro_campania');		
		d = $F('filtro_destacado');		
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b+'&c='+c+'&d='+d;	
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

// FUNCIONES DEL FORMULARIO DE ALTA -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function showAddForm() {
	$('frm_alta').reset();
	$('buscador').hide();
	$('ayuda').show();
	$('listado').hide();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'AGREGAR PREMIO';
	$('textoayuda').innerHTML = 'Ingrese los datos del nuevo premio.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br />';
	$('btneditar').hide();	
	$('btnagregar').show();
	dameGrupos();
}

function showEditForm(idpremio) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DEL PREMIO';
	$('textoayuda').innerHTML = 'Aqu&#237; puede modificar los datos del premio seleccionado.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br />';
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idpremio').value = idpremio;
	traeDatos(idpremio);
}

//busca los datos del premio elegido en la DDBB
function traeDatos(idpremio){
	var pars = '?c='+idpremio;
	var url = serverRoot+'php/datos_premio.php';
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
	var id												= '';
	var nombre							= '';	
	var nombre_ch				= '';	
	var chances							= '';	
	var tipo										= '';	
	var imagen							= '';	
	var detalle								= '';
	var detalle_ch					= '';
	var puntos								= '';
	var idcampania				= '';
	var stock									= '';
	var stock_real					= '';
	var vigente_desde	= '';
	var vigente_hasta		= '';
	var origen								= '';
	var estado 							= '';
			
	var xml_premios = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];			
	if(xml_premios.getElementsByTagName('id')[0].childNodes.length > 0)
		id	= xml_premios.getElementsByTagName('id')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('nombre')[0].childNodes.length > 0)
		nombre = xml_premios.getElementsByTagName('nombre')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('nombre_ch')[0].childNodes.length > 0)
		nombre_ch = xml_premios.getElementsByTagName('nombre_ch')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('tipo')[0].childNodes.length > 0)
		tipo = xml_premios.getElementsByTagName('tipo')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('imagen')[0].childNodes.length > 0)
		imagen = xml_premios.getElementsByTagName('imagen')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('detalle')[0].childNodes.length > 0)
		detalle = xml_premios.getElementsByTagName('detalle')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('detalle_ch')[0].childNodes.length > 0)
		detalle_ch = xml_premios.getElementsByTagName('detalle_ch')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('puntos')[0].childNodes.length > 0)
		puntos = xml_premios.getElementsByTagName('puntos')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('stock')[0].childNodes.length > 0)
		stock = xml_premios.getElementsByTagName('stock')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('stock_real')[0].childNodes.length > 0)
		stock_real = xml_premios.getElementsByTagName('stock_real')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('vigente_desde')[0].childNodes.length > 0)
		vigente_desde = xml_premios.getElementsByTagName('vigente_desde')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('vigente_hasta')[0].childNodes.length > 0)
		vigente_hasta = xml_premios.getElementsByTagName('vigente_hasta')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('idcampania')[0].childNodes.length > 0)
		idcampania = xml_premios.getElementsByTagName('idcampania')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('chances')[0].childNodes.length > 0)
		chances = xml_premios.getElementsByTagName('chances')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('estado')[0].childNodes.length > 0)
		estado = xml_premios.getElementsByTagName('estado')[0].childNodes[0].nodeValue;		
	if(xml_premios.getElementsByTagName('origen')[0].childNodes.length > 0)
		origen = xml_premios.getElementsByTagName('origen')[0].childNodes[0].nodeValue;		
		
	//pongo los puntoses en el formulario		
	if(nombre!='')				$('frm_alta_nombre').value 			= nombre;	
	if(nombre_ch!='')	$('frm_alta_nombre_ch').value	= nombre_ch;	
	if(tipo!='')							$('frm_alta_tipo').value 						= tipo;	
	if(imagen!='')				$('showImagen').innerHTML 		= '<a href="../../../archivos/'+imagen+'" target="_blank"><img src="../../../archivos/'+imagen+'" target="_blank" width="75" border="0"></a>';
	if(detalle!='')					$('frm_alta_detalle').value				= detalle;
	if(detalle_ch!='')		$('frm_alta_detalle_ch').value	= detalle_ch;
	if(puntos!='')				$('frm_alta_puntos').value 			= puntos;	
	if(origen!='')					$('frm_alta_origen').value 				= origen;	
	if(stock!='')						$('frm_alta_stock').value 					= stock;	
	if(stock_real!='')									
	{	
		$('stock_real').innerHTML 		= '<strong>[Stock real: '+stock_real+']</strong>';	
		$('stock_real').style.display	= 'block';	
	}	
	if(vigente_desde!='')	$('frm_alta_vigencia_desde').value	= vigente_desde;	
	if(vigente_hasta!='')		$('frm_alta_vigencia_hasta').value 	= vigente_hasta;	
	if(idcampania!='') 				$('frm_alta_campania').value 					= idcampania;	
	if(estado!='') 							$('frm_alta_estado').value 							= estado;	
	if(chances!='' && chances!=0 )	{
		$('input_chances').show();
		$('frm_alta_chances').value 	= chances;	
	}
	dameGruposPremio(id);	
}

function verificarAlta() {
	var grupos	= '';
	var grp				= 'grupo';
	var boxes 	= $('frm_alta').getElementsByTagName('input');
	var nro 			= boxes.length;
	for (var i = 0; i < nro; i++) 
	{
		myType 		= boxes[i].getAttribute('type');
		myName	= boxes[i].getAttribute('name');
		if(myType == 'checkbox') 
		{
			if(myName.indexOf(grp) != -1)
			//otra forma de checkear lo mismo: if(/grupo/.test(myName))
			{	
				if(boxes[i].checked) 
				{
					grupo = boxes[i].value;	
					grupos += grupo+'|';
				}
				$('idgrupos').value = grupos;
			}	
		}
	}
	if(grupos == '') 
		alert('Debe seleccionar al menos un grupo');
	else
	{	
		var valid = new Validation('frm_alta', {onSubmit:false});
		if(valid.validate()) {
			$('alta_asistencia').hide();
			$('alta_verificando').show();				
			$('frm_alta').action = 'php/alta.php';
			$('frm_alta').target = 'premio_upload_target';
			$('frm_alta').submit();
			Form.disable('frm_alta');				
		}
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
		alert('No se ha podido cargar este premio, por favor int\u00e9ntelo m\u00e1s tarde');
}

function verificarEdicion() {
	var grupos	= '';
	var grp				= 'grupo';
	var boxes 	= $('frm_alta').getElementsByTagName('input');
	var nro 			= boxes.length;
	for (var i = 0; i < nro; i++) 
	{
		myType 		= boxes[i].getAttribute('type');
		myName	= boxes[i].getAttribute('name');
		if(myType == 'checkbox') 
		{
			if(myName.indexOf(grp) != -1)
			//otra forma de checkear lo mismo: if(/grupo/.test(myName))
			{	
				if(boxes[i].checked) 
				{
					grupo = boxes[i].value;	
					grupos += grupo+'|';
				}
				$('idgrupos').value = grupos;
			}	
		}
	}
	if(grupos == '') 
		alert('Debe seleccionar al menos un grupo');
	else
	{
		var valid = new Validation('frm_alta', {onSubmit:false});
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		$('frm_alta').action = 'php/modificar.php';
		$('frm_alta').target = 'premio_upload_target';
		$('frm_alta').submit();
		Form.disable('frm_alta');
	}	
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
		alert('Ha habido un error ya que no se encuentra el premio que desea modificar');	
	if(resultado == '0')
		alert('En este momento no se puede modificar este premio, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
  $('showImagen').innerHTML = '';
	$('stock_real').innerHTML 		= '';	
	$('stock_real').style.display	= 'none';
	$('contenido').innerHTML 			= '';
	Form.enable('frm_alta');				
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();  
  $('alta').hide();	
  muestraResultado();
}																	

function showDeleteForm(idpremio) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar este premio?')) 
	{
		var pars = '?idpremio='+idpremio;
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

function seleccionar_todo(tipo){
	for (i=0;i<document.frm_alta.elements.length;i++)
		if(document.frm_alta.elements[i].type == 'checkbox')
			if(document.frm_alta.elements[i].id.indexOf(tipo) != -1)			
				document.frm_alta.elements[i].checked=1
} 

function deseleccionar_todo(tipo){
  for (i=0;i<document.frm_alta.elements.length;i++)
		if(document.frm_alta.elements[i].type == 'checkbox')
			if(document.frm_alta.elements[i].id.indexOf(tipo) != -1)			
				document.frm_alta.elements[i].checked=0
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

//apertura de la planilla de excel
function showPlanillaPremios(){
	b = $F('filtro_buscar');		
	c = $F('filtro_campania');		
	document.location = 'php/planilla_premios.php?b='+b+'&c='+c;	
}