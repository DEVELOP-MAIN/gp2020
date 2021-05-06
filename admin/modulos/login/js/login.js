//DEFINICION VARIABLES
var serverRoot = varroot+'/modulos/login/';

//valida el form y llama a enviar los datos
function enviar()
{
	 if($('login_usuario').value=="" || $('login_clave').value=="")
	 {
	 		$('alerta').innerHTML = 'Ingresá tu nombre de usuario y tu clave';			
			new Effect.Appear($('alerta'));			
	 }
	 else   	
	   	checkLogin();
}

//chequea el loguin en el webservice y actua
function checkLogin(){
	var u = $F('login_usuario');
	var p = $F('login_clave');
	var url = serverRoot+'php/login.php?';
	var pars = 'u=' + u + '&p=' + p;
	$('contenedor').startWaiting('bigWaiting');
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'get', 
		parameters: pars, 
		onComplete: SuccessFunc,
		onFailure: ErrorFunc
	});
}

//error al conectar con el webservice
function ErrorFunc(){
	$('alerta').innerHTML = 'Error al conectar con el php';	
}

//procesa la informacion del webservice
function SuccessFunc(requester)
{
	if (requester.responseXML!=null)
	{
		var result = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('resultado').nodeValue;
		if(parseInt(result)== 1) 
			document.location = '../clientes/';
		else
		{
			$('contenedor').stopWaiting();
			var mensaje = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('mensaje')[0].childNodes[0].nodeValue;
			$('alerta').innerHTML = mensaje;			
			new Effect.Appear($('alerta'));
		}
	}
	else
	{
		$('contenedor').stopWaiting();
		$('alerta').innerHTML = 'Hubo un problema con la conexion, intente nuevamente';
		new Effect.Appear($('alerta'));
	}	
}