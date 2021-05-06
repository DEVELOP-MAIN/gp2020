//esto se ejecuta automaticamente
var usr_nombre = '';
var usr_apellido	= '';
var usr_email 		= '';
var usr_fbid 			= '';
var usr_genero	= '';

//veo si esta logueado en FB
function statusChangeCallback(response) {
  if (response.status === 'connected') {
    //verificaAutomatico();
  }
}

//chequeo automatico
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}

//ejecucion automatica
window.fbAsyncInit = function() {
	FB.init({
  	appId     : '752024378269075',
  	cookie    : true,  // enable cookies to allow the server to access the session
  	xfbml      : true,  // parse social plugins on this page
  	version	: 'v2.6' // use version 2.2
	});
	FB.getLoginStatus(function(response) {
  	statusChangeCallback(response);
	});
};

// Load the SDK asynchronously
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = '//connect.facebook.net/en_US/sdk.js';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// esta logueado en fb y debo ver si registrado en PM Coach
function verificaAutomatico() {
 	//aca es cuando vengo del status y se que esta logueado
  FB.api('/me', 'get', { fields: 'id,name,gender,email,first_name,last_name' }, function(response) {
    usr_nombre = response.first_name;
		usr_apellido	= response.last_name;
		usr_email 		= response.email;
		usr_fbid 			= response.id;
		usr_genero	= response.gender;
		
		//con el id llamar debo chequear si esta registrado
		var url = 'php/logueoredes.php';
		$.ajax({
  		method: 'POST',
  		url: url,
  		data: { 
				usr_nombre: usr_nombre, 
				usr_apellido:usr_apellido, 
				usr_email:usr_email, 
				usr_fbid:usr_fbid, 
				usr_genero:usr_genero
			}
		})
		.done(function( msg ) {		
   		if(msg == 1 || msg == '1') {
   			document.location = 'user/home.php';
   		}
 		});
  });
}

// esta logueado en fb y debo ver si registrado en PM Coach
function verificaAPedido() {
 	//aca es cuando vengo del status y se que esta logueado
  FB.api('/me', 'get', { fields: 'id,name,gender,email,first_name,last_name' }, function(response) {
    usr_nombre	= response.first_name;
		usr_apellido	= response.last_name;
		usr_email 		= response.email;
		usr_fbid 			= response.id;
		usr_genero	= response.gender;

    check_registrado();
  });
}

//chequeo si existe y si si lo logueo, si no va al registro
function check_registrado() {
	/*harcodeadas para testear
	usr_nombre = 'Juan Pablo';
	usr_apellido	= 'Cappelli';
	usr_email 		= 'pablo@dixer.net';
	*/
	var url = 'php/logueoredes.php';
	$.ajax({
  	method: 'POST',
  	url: url,
  	data: {
			usr_nombre: usr_nombre,
			usr_apellido: usr_apellido,
			usr_email: usr_email,
			usr_fbid:usr_fbid 
		}
	})
	.done(function( msg ) {
   	if(msg == 1 || msg == '1') 
   		document.location = 'user/home.php';
   	else
   		document.location = 'login.php';
  });
}

//para la llamada desde el boton
function fb_login(){
	//check_registrado(); //esto es para probar abajo, despues comentar
	
	FB.login(function(response) {
		 if (response.authResponse) {
			 access_token = response.authResponse.accessToken; //get access token
			 user_id = response.authResponse.userID; //get FB UID
			 verificaAPedido();
		 } else {
				//user hit cancel button
				alert('El usuario cancel\u00f3 o no autoriz\u00f3.');
		 }
	}, {
		 scope: 'public_profile,email'
	});
}