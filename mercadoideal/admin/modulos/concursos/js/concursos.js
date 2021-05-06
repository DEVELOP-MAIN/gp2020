//DEFINICION VARIABLES
var serverRoot	= '';
var pagina  			= 0;
var cantidad		= 10;

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
	
	var dpck_frm_alta_fecha_desde = new DatePicker({
		relative	: 'frm_alta_fecha_desde',
		language	: 'sp',
		keepFieldEmpty: true
	});
  dpck_frm_alta_fecha_desde.load();
	
	var dpck_frm_alta_fecha_hasta = new DatePicker({
		relative	: 'frm_alta_fecha_hasta',
		language	: 'sp',
		keepFieldEmpty: true
	});
  dpck_frm_alta_fecha_hasta.load();
	
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
	$('listado').innerHTML = 'Error al conectar con el php';	
}

// FUNCIONES DEL LISTADO ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// realiza la búsqueda
function buscar() {	
	var valid = new Validation('frm_buscador', {onSubmit:false});
  if(valid.validate()) 
	{			
		b = $F('filtro_buscar');
		d = $F('filtro_fecha_desde');
		h = $F('filtro_fecha_hasta');
		var pars = '?cant='+cantidad+'&pag='+pagina+'&b='+b+'&d='+d+'&h='+h;	
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
	$('frmtitulo').innerHTML = 'AGREGAR CONCURSO';
	$('textoayuda').innerHTML = 'Ingrese los datos del nuevo concurso.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br /><br />Puede tambi&#233;n desde aqu&#237; agregar una imagen.<br /><br />&#160;';
	$('btneditar').hide();	
	$('btnagregar').show();
}

function showEditForm(idconcurso) {	
	$('listado').hide();
	$('buscador').hide();
	$('ayuda').show();
	$('alta').show();	
	$('frmtitulo').innerHTML = 'EDITAR DATOS DEL CONCURSO';
	$('textoayuda').innerHTML = "Aqu&#237; puede modificar los datos del concurso seleccionado.<br /><br />Preste atenci&#243;n a los campos obligatorios marcados con asterisco.<br /><br />Puede tambi&#233;n desde aqu&#237; agregar una imagen.<br /><br />&#160;";
	Form.disable($('frm_buscador'));
	$('btneditar').show();	
	$('btnagregar').hide();
	$('idconcurso').value = idconcurso;
	traeDatos(idconcurso);
}

//busca los datos del concurso elegido en la DDBB
function traeDatos(idconcurso){
	var pars = '?c='+idconcurso;
	var url = serverRoot+'php/datos_concurso.php';
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
	var titulo											= '';
	var descripcion						= '';
	var imagen									= '';
	var chances_minimas	= '';
	var aviso_legal						= '';
	var fecha_desde					= '';
	var fecha_hasta					= '';
		
	var xml_concursos = requester.responseXML.getElementsByTagName('resultadosGenerales')[0];		
	if(xml_concursos.getElementsByTagName('titulo')[0].childNodes.length > 0)
		titulo = xml_concursos.getElementsByTagName('titulo')[0].childNodes[0].nodeValue;		
	if(xml_concursos.getElementsByTagName('descripcion')[0].childNodes.length > 0)
		descripcion = xml_concursos.getElementsByTagName('descripcion')[0].childNodes[0].nodeValue;		
	if(xml_concursos.getElementsByTagName('imagen')[0].childNodes.length > 0)
		imagen = xml_concursos.getElementsByTagName('imagen')[0].childNodes[0].nodeValue;		
	if(xml_concursos.getElementsByTagName('chances_minimas')[0].childNodes.length > 0)
		chances_minimas = xml_concursos.getElementsByTagName('chances_minimas')[0].childNodes[0].nodeValue;		
	if(xml_concursos.getElementsByTagName('aviso_legal')[0].childNodes.length > 0)
		aviso_legal = xml_concursos.getElementsByTagName('aviso_legal')[0].childNodes[0].nodeValue;		
	if(xml_concursos.getElementsByTagName('fecha_desde')[0].childNodes.length > 0)
		fecha_desde = xml_concursos.getElementsByTagName('fecha_desde')[0].childNodes[0].nodeValue;		
	if(xml_concursos.getElementsByTagName('fecha_hasta')[0].childNodes.length > 0)
		fecha_hasta = xml_concursos.getElementsByTagName('fecha_hasta')[0].childNodes[0].nodeValue;		
	
	//pongo los valores en el formulario	
	if(titulo!='')											$('frm_alta_titulo').value = titulo;
	if(descripcion!='')						$('frm_alta_descripcion').value = descripcion;
	if(imagen!='')									$('showImagen').innerHTML	= '<a href="../../../archivos/'+imagen+'" target="_blank"><img src="../../../archivos/'+imagen+'" target="_blank" width="75" border="0"></a>';
	if(chances_minimas!='')	$('frm_alta_chances_minimas').value	= chances_minimas;
	if(aviso_legal!='')						$('frm_alta_aviso_legal').value = aviso_legal;
	if(fecha_desde!='')					$('frm_alta_fecha_desde').value = fecha_desde;
	if(fecha_hasta!='')					$('frm_alta_fecha_hasta').value = fecha_hasta;
}

function verificarAlta() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	if(valid.validate()) {
		$('alta_asistencia').hide();
		$('alta_verificando').show();				
		$('frm_alta').action = 'php/alta.php';
		$('frm_alta').target = 'concurso_upload_target';
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
		alert('No se ha podido cargar este concurso, por favor int\u00e9ntelo m\u00e1s tarde');
}

function verificarEdicion() {
	var valid = new Validation('frm_alta', {onSubmit:false});
	$('alta_asistencia').hide();
	$('alta_verificando').show();				
	$('frm_alta').action = 'php/modificar.php';
	$('frm_alta').target = 'concurso_upload_target';
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
		alert('Ha habido un error ya que no se encuentra el concurso que desea modificar');	
	if(resultado == '0')
		alert('En este momento no se puede modificar este concurso, por favor int\u00e9ntelo m\u00e1s tarde');
}

function Cancelar() {
	$('showImagen').innerHTML = '';
	Form.enable('frm_alta');				
  $('ayuda').hide();	
  Form.enable($('frm_buscador'));
  $('frm_alta').reset();  
  $('alta').hide();	
  muestraResultado();
}																	

function showDeleteForm(idconcurso) {
	if(confirm('\u00bfEst\u00e1 seguro de eliminar este concurso?')) 
	{
		var pars = '?idconcurso='+idconcurso;
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