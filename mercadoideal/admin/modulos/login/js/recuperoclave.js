//DEFINICION VARIABLES
var serverRoot = '';

function showrecupero() {
	$('alerta2').hide();
	$('camposColocar').hide();
	new Effect.Appear($('recuperoClave'));
}

function hiderecupero() {
	$('alerta2').hide();
	new Effect.Appear($('camposColocar'));	
	$('recuperoClave').hide();
}

//valida el form y llama a enviar los datos
function enviarclave() 
{
	 if($('login_usuarioclave').value=="") 
	 {
	 		$('alerta2').innerHTML = 'Ingresá tu nombre de usuario';			
			new Effect.Appear($('alerta2'));				
	 } else   	
	   	checkClave();
	 
}

//chequea el loguin en el webservice y actua
function checkClave() 
{
	new Effect.Appear($('alerta2'));
	var u = $F('login_usuarioclave');	
	var url = serverRoot+'php/recupero_clave.xml?';
	var pars = 'u=' + u;
	
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: SuccessFunction,
		onFailure: ErrorFunction
	});
	
}

//error al conectar con el webservice
function ErrorFunction()
{
	$('alerta2').innerHTML = 'Error al conectar con el php';	
}

//procesa la informacion del webservice
function SuccessFunction(requester)
{
	if (requester.responseXML!=null) {
		var result = requester.responseXML.getElementsByTagName("resultadosGenerales")[0].attributes.getNamedItem("resultado").value;
		var mensaje = requester.responseXML.getElementsByTagName("resultadosGenerales")[0].getElementsByTagName("mensaje")[0].childNodes[0].nodeValue;
		$('alerta2').innerHTML = mensaje;		
	} else {
		$('alerta2').innerHTML = 'Hubo un problema con la conexion, intente nuevamente';
	}	
}