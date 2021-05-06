var varroot  					= '/admin';
var gral_perfil 				= '';
var gral_nombre 		= '';
var gral_apellido		= '';
var gral_id  						= '';
var gral_tipo  				= '';
var gral_dia  					= '';
var gral_mes 				= '';
var gral_ano  				= '';
var gral_hora  				= '';
var gral_minuto 		= '';
var gral_segundo	= '';
var timer								= '';
var first_time  				= true;
var pagina  						= 0;
var cantidad					= 10;

function GeneralErrorDisplay(requester) 
{
	var mensaje = requester.responseXML.getElementsByTagName("resultadosGenerales")[0].getElementsByTagName("mensaje")[0].childNodes[0].nodeValue;
	var content = "<table width='100%' height='100%' border='0' cellpadding='8' cellspacing='0'>";
				content += "<tr>";
	  			content += "<td height='100%' bgcolor='#FFFFFF' align='center'>";
						content += "<table width='40%' height='400' border='0' cellpadding='0' cellspacing='0'>";
	    			 content += "<tr>";
	      				content += "<td align='center' class='tituloError'>Error en el sistema<BR><BR><font class='cuerpoError'>"+mensaje+"</td>";	        			
	    			content += "</tr>";
	    			 content += "<tr>";
	        			content += "<td align='center' valign='middle'>&#160;</td>";	        			
	    			content += "</tr>";
	  			content += "</table>";
	  		content += "</td>";
	    content += "</tr>";
	  content += "</table>";
	$('contenidoGeneral').innerHTML = content;
}

// return the value of the radio button that is checked
// return an empty string if none are checked, or
// there are no radio buttons
function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

// set the radio button with the given value as being checked
// do nothing if there are no radio buttons
// if the given value does not exist, all the radio buttons
// are reset to unchecked
function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}

//calcular la edad de una persona
//recibe la fecha como un string en formato español
//devuelve un entero con la edad. Devuelve false en caso de que la fecha sea incorrecta o mayor que el dia actual
function calcular_edad(fecha){
	//calculo la fecha de hoy
	hoy=new Date();
	
	//calculo la fecha que recibo
	//La descompongo en un array
	var array_fecha = fecha.split("/");
	//si el array no tiene tres partes, la fecha es incorrecta
	if (array_fecha.length!=3)
		 return false;

	//compruebo que los ano, mes, dia son correctos
	var ano
	ano = parseInt(array_fecha[2]);
	if (isNaN(ano))
		 return false

	var mes
	mes = parseInt(array_fecha[1]);
	if (isNaN(mes))
		 return false

	var dia
	dia = parseInt(array_fecha[0]);
	if (isNaN(dia))
		 return false


	//si el año de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4
	if (ano<=99)
		 ano +=1900

	//resto los años de las dos fechas
	edad=hoy.getFullYear()- ano - 1; //-1 porque no se si ha cumplido años ya este año

	//si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido
	if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0
		 return edad
	if (hoy.getMonth() + 1 - mes > 0)
		 return edad+1

	//entonces es que eran iguales. miro los dias
	//si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido
	if (hoy.getUTCDate() - dia >= 0)
		 return edad + 1

	return edad
} 

function findPosX(obj)
{
  var curleft = 0;
  if(obj.offsetParent)
      while(1) 
      {
        curleft += obj.offsetLeft;
        if(!obj.offsetParent)
          break;
        obj = obj.offsetParent;
      }
  else if(obj.x)
      curleft += obj.x;
  return curleft;
}

function findPosY(obj)
{
  var curtop = 0;
  if(obj.offsetParent)
      while(1)
      {
        curtop += obj.offsetTop;
        if(!obj.offsetParent)
          break;
        obj = obj.offsetParent;
      }
  else if(obj.y)
      curtop += obj.y;
  return curtop;
}

function poneReloj(){

if(first_time) 
{
	timer = new Date();
	timer.setHours(gral_hora);
	timer.setMinutes(gral_minuto);
	timer.setSeconds(gral_segundo);
	first_time = false;
} else {
	timer.setSeconds(timer.getSeconds()+1);
}

var hours=timer.getHours();
var minutes=timer.getMinutes();
var seconds=timer.getSeconds();

if (minutes<=9)
 minutes='0'+minutes
if (seconds<=9)
 seconds='0'+seconds
//change font size here to your desire
myclock=hours+':'+minutes+':'+seconds
$('reloj').innerHTML=myclock
setTimeout('poneReloj()',1000)
}

function poneFecha()
{
	monthnm = new Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre','Noviembre', 'Diciembre');
	dow_full = new Array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
			
	var dte = new Date();	
	dte.setDate(gral_dia);
	dte.setMonth(gral_mes-1);
	dte.setYear(gral_ano);
	ndia = dow_full[dte.getDay()];
	dia = dte.getDate();
	mes = monthnm[dte.getMonth()];
	ano = dte.getFullYear();
	$('fecha').innerHTML= ndia+' <strong>'+dia+'</strong> de '+mes+' de '+ano+' &#160;';
}

//resetar la paginacion
function resetPaginado() 
{
	pagina = "";
	cantidad = "";
}

function IsNumeric(strString)
//  check for valid numeric strings	
{
	var strValidChars = '0123456789.';
	var strChar;
	var blnResult = true;

	if (strString.length == 0) return false;

	//  test strString consists of valid characters listed above
	for (i = 0; i < strString.length && blnResult == true; i++)
		{
		strChar = strString.charAt(i);
		if (strValidChars.indexOf(strChar) == -1)
			 {
			 blnResult = false;
			 }
		}
	return blnResult;
}