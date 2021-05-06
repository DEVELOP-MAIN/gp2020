var clicenboton = false;

function onSignIn(googleUser) {
  var usr_nombre = '';
  var usr_apellido	= '';	  
  var usr_email 		= '';
  var profile = googleUser.getBasicProfile();
  
	/*
	  alert('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
	  alert('Name: ' + profile.getName());
	  alert('G. Name: ' + profile.getGivenName());
	  alert('F. Name: ' + profile.getFamilyName());	  
	  alert('Image URL: ' + profile.getImageUrl());
	  alert('Email: ' + profile.getEmail());
		*/
	  usr_gid = profile.getId();
	  usr_nombre = profile.getGivenName();
	  usr_apellido = profile.getFamilyName();	  
	  usr_email = profile.getEmail();
	  usr_foto = profile.getImageUrl();
		
		//tiro un ajax al php que verifica, registra y loguea
	  //con el id llamar debo chequear si esta registrado
		var url = 'php/logueoredes.php';
		$.ajax({
  		method: 'POST',
  		url: url,
  		data: {
				usr_nombre: usr_nombre, 
				usr_apellido: usr_apellido, 
				usr_email: usr_email,
				usr_foto: usr_foto,
				usr_gid: usr_gid
			}
		})
		.done(function(msg) {   	
			if(msg == 1 || msg == '1') {   			
				document.location = 'user/home.php';
			}	
		});		  	
}

function signOut() {	
	FB.getLoginStatus(function(response) {      
	  if (response.status === 'connected') {
		FB.logout(function(response) {			
			//ahora los aco del googleUser
			var auth2 = gapi.auth2.getAuthInstance();
			auth2.signOut().then(function () {});
			document.location = '../logout.php';
		});	
	  }},true);
   
    //ahora los aco del googleUser
	var auth2 = gapi.auth2.getAuthInstance();
	auth2.signOut().then(function () {document.location = '../logout.php';});	
}