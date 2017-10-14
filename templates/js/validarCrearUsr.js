// validad los formularios para creacion de un nuevo usuario
function validarCrearUsuario (){

	var email = document.getElementById("emailUs").value;
	var nombreUsuario = document.getElementById("nombreUs").value;
	var password = document.getElementById("contraUs").value;
	var nombrereal = document.getElementById("nombreRealUs").value;
	var apellido = document.getElementById("apellidoUs").value;
	
	var charPalMail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
	var letras=/^[A-Za-z\_\-\.\s\xF1\xD1]+$/;


	if ((email == "") || (nombreUsuario == "") || (password == "") || (nombrereal == "") || (apellido == "")) {
		alert("Los campos indicados con (*) son obligatorios");
		return false;
	}else if (email.lenght > 255 || nombreUsuario.lenght > 255 || password.lenght > 255 || nombrereal.lenght > 255 || apellido.lenght > 255  ){
		alert("Excedio el limite de caracteres en algun(os) campo(s)");
                return false;
	}else if(charPalMail.test(email) == false){
		alert("El email ingresado no es valido");
		return false;
	}else if ( (! letras.test(nombrereal)) || (! letras.test(apellido)) ){
        alert("El nombre debe tener solo letras");
        return false;
    }else if(!document.getElementById('check1').checked && !document.getElementById('check2').checked && !document.getElementById('check3').checked ){
    	alert("Selecciona al menos un Rol");
    	return false;
    }
}

function existe_Usr(){
	var email = document.getElementById("emailUs").value;
	var nameUsr = document.getElementById("nombreUs").value;
	var retorno = validarEmail_nombre(email, nameUsr);

	if (!retorno){
		alert("ya existe un usuario con ese Email (y/o) Nombre de Uuario");
	}
	return retorno;
}

function validarEmail_nombre (email, nombreUs){
	
	var aux = false;
	$.ajax({
		url: "./index.php", 
		data: { action: "existe_Usuario", email_usuario: email, nombre_deUsuario: nombreUs},
		async: false,
		success: function (result){
			if (result){
				aux = true;
			}
		}
	});

	return aux;

}