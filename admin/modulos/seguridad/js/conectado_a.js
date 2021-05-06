var seguridadRoot	= varroot+'/modulos/seguridad/';
var gral_id 			= '';
var gral_tipo 		= '';

//DEFINICION VARIABLES GLOBALES
function validaconeccion(){
	var url = seguridadRoot+'php/conectado_a.php';
	var pars = '';
	var myAjax = new Ajax.Request(
	url,
	{
		method: 'get', 
		parameters: pars, 
		onComplete: verificaConeccion,
		onFailure: ErrorFunc
	});
}

//funcion de exito al traer los datos de la busqueda
function verificaConeccion(requester){	
	var result = requester.responseXML.getElementsByTagName('resultadosGenerales')[0].attributes.getNamedItem('resultado').nodeValue;
	if(parseInt(result)){
		//pongo los datos de bienvenida
		gral_usuario	= requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('usuario')[0].attributes.getNamedItem('usuario').nodeValue;
		gral_tipo 				= requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('usuario')[0].attributes.getNamedItem('tipo').nodeValue;
		gral_dia 				= requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('usuario')[0].attributes.getNamedItem('dia').nodeValue;
		gral_mes 			= requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('usuario')[0].attributes.getNamedItem('mes').nodeValue;
		gral_ano				= requester.responseXML.getElementsByTagName('resultadosGenerales')[0].getElementsByTagName('usuario')[0].attributes.getNamedItem('ano').nodeValue;
		$('loggeduser').innerHTML = gral_usuario;
				
		admin = "<div id='nav'>";
			
			admin += "<ul id='MenuBar1' class='MenuBarHorizontal'>";
				admin += "<li>";
					admin += "<a class='MenuBarItemSubmenu' href='#'>CONFIGURACI&#211;N</a>";
          admin += "<ul>";
						admin += "<li><a href='../operadores/' class='extizq'>Operadores</a></li>";
          admin += "</ul>";
				admin += "</li>";
			admin += "</ul>";

			admin += "<ul id='MenuBar2' class='MenuBarHorizontal'>";
				admin += "<li>";
					admin += "<a class='MenuBarItemSubmenu' href='#'>GESTI&#211;N</a>";
          admin += "<ul>";
          	admin += "<li><a href='../canjes/' class='extizq'>Canjes</a></li>";
          	//admin += "<li><a href='../concursos/' class='extizq'>Concursos</a></li>";
          	//admin += "<li><a href='../mensajes/' class='extizq'>Mensajes</a></li>";
          	admin += "<li><a href='../noticias/' class='extizq'>Novedades</a></li>";
           	admin += "<li><a href='../campanias/' class='extizq'>Categorias de premios</a></li>";
          	admin += "<li><a href='../premios/' class='extizq'>Premios</a></li>";
          	admin += "<li><a href='../millas/' class='extizq'>Millas</a></li>";
						admin += "<li><a href='../seguimiento/' class='extizq'>Seguimiento</a></li>";
          	//admin += "<li><a href='../grupos/' class='extizq'>Grupos de Usuarios</a></li>";
          	admin += "<li><a href='../clientes/' class='extizq'>Socios</a></li>";
          admin += "</ul>";
				admin += "</li>";
			admin += "</ul>";

			/*
			admin += "<ul id='MenuBar5' class='MenuBarHorizontal'>";
				admin += "<li>";
					admin += "<a class='MenuBarItemSubmenu' href='#'>REPORTES</a>"; 
					admin += "<ul>";
          	admin += "<li><a href='../clientes/php/planilla_premios.php?b=&s=&e=&st=&p=' class='extizq'>Supermercados registrados</a></li>"; 
          	admin += "<li><a href='../compras/' class='extizq'> Compras registradas</a></li>"; 
          	admin += "<li><a href='../seguimiento_actividad/' class='extizq'> Seguimiento de actividad</a></li>";
          admin += "</ul>";	
				admin += "</li>";
			admin += "</ul>";
			*/
			
		admin += "</div>";
		
		operario_clientes = "<div id='nav'>";
			
			operario_clientes += "<ul id='MenuBar2' class='MenuBarHorizontal'>";
				operario_clientes += "<li>";
					operario_clientes += "<a class='MenuBarItemSubmenu' href='#'> GESTI&#211;N</a>";
          operario_clientes += "<ul>";
           	operario_clientes += "<li><a href='../mensajes/' class='extizq'> Mensajes</a></li>";
          	operario_clientes += "<li><a href='../clientes/' class='extizq'> Supermercados</a></li>";          	
          operario_clientes += "</ul>";
				operario_clientes += "</li>";
			operario_clientes += "</ul>";
			
		operario_clientes += "</div>";
		
		//menu del visualizador
		operario_visualizador = "<div id='nav'>";			
			operario_visualizador += "<ul id='MenuBar2' class='MenuBarHorizontal'>";
				operario_visualizador += "<li>";
					operario_visualizador += "<a class='MenuBarItemSubmenu' href='../clientes/'> SUPERMERCADOS </a>";          
				operario_visualizador += "</li>";
			operario_visualizador += "</ul>";			
		operario_visualizador += "</div>";
		
		operario_premios = "<div id='nav'>";
			
			operario_premios += "<ul id='MenuBar2' class='MenuBarHorizontal'>";
				operario_premios += "<li>";
					operario_premios += "<a class='MenuBarItemSubmenu' href='#'> GESTI&#211;N</a>";
          operario_premios += "<ul>";
           	operario_premios += "<li><a href='../premios/' class='extizq'> Premios</a></li>";          	
          operario_premios += "</ul>";
				operario_premios += "</li>";
			operario_premios += "</ul>";
			
		operario_premios += "</div>";
		
		if(gral_tipo=='A') $('menu').innerHTML = admin;
		if(gral_tipo=='C') $('menu').innerHTML = operario_clientes;	
		if(gral_tipo=='P') $('menu').innerHTML = operario_premios;	
		if(gral_tipo=='D') $('menu').innerHTML = delivery;	
		if(gral_tipo=='V') $('menu').innerHTML = operario_visualizador;	
				
		var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"../../SpryAssets/SpryMenuBarDownHover.gif", imgRight:"../../SpryAssets/SpryMenuBarRightHover.gif"});
		var MenuBar2 = new Spry.Widget.MenuBar("MenuBar2", {imgDown:"../../SpryAssets/SpryMenuBarDownHover.gif", imgRight:"../../SpryAssets/SpryMenuBarRightHover.gif"});
		var MenuBar3 = new Spry.Widget.MenuBar("MenuBar3", {imgDown:"../../SpryAssets/SpryMenuBarDownHover.gif", imgRight:"../../SpryAssets/SpryMenuBarRightHover.gif"});
		var MenuBar4 = new Spry.Widget.MenuBar("MenuBar4", {imgDown:"../../SpryAssets/SpryMenuBarDownHover.gif", imgRight:"../../SpryAssets/SpryMenuBarRightHover.gif"});
		var MenuBar5 = new Spry.Widget.MenuBar("MenuBar5", {imgDown:"../../SpryAssets/SpryMenuBarDownHover.gif", imgRight:"../../SpryAssets/SpryMenuBarRightHover.gif"});
				
		poneFecha();		
		iniciaModulo();
	}
	else
	{
		//lo saco
		document.location = '../login';
	}	
}

//error al conectar con el webservice
function ErrorFunc()
{
	//lo saco
	document.location = '../login';
}