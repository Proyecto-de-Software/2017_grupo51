// validad los formularios para creacion de un nuevo usuario
function validarCrearUsuario (){
	//mismo caso que en el validador de formulario pacientes, no se si traer por parametro o ir a buscarlo derecho con ruta.
	//solucione de la forma de aca abajo, pero no me andan las pruebas, nunca me devuelve el false. 
	//Se siguen enviando los datos del form porq no devuelve el false.
	var email = document.getElementById("emailUs").value;
	var nombreUsuario = document.getElementById("nombreUs").value;
	var password = document.getElementById("contraUs").value;
	var nombrereal = document.getElementById("nombreRealUs").value;
	var apellido = document.getElementById("apellidoUs").value;
	var charPalMail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

	if (email === "" || nombreUsuario === "" || password === "" || nombrereal === "" || apellido === "" ) {
		alert("Los campos indicados con (*) son obligatorios");
		return false;
	}else if (emailUs.lenght > 255 || nombreUs.lenght > 255 || contraUs.lenght > 255 || nombreRealUs.lenght > 255 || apellidoUs.lenght > 255  ){
		alert("excedio el limite de caracteres en algun(os) campo(s)")
	return false;
	}else if(charPalMail.test(email) == false){
		alert(el Email ingresado no es valido);
		return false;
	}
}