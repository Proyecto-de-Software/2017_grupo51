//funcion para validar el Formulario de creacion de pacientes
function validarFormPac (){


	var apellido = document.getElementById("ApellidoP").value;
	var nombre = document.getElementById("NombreP").value;
	var fec = document.getElementById("FecNacP").value;
	var genero = document.getElementById("GeneroP").value;
	var tipoDoc = document.getElementById("TipoDocP").value;
	var numeroDoc = document.getElementById("NroDocP").value;
	var domicP = document.getElementById("DomicP").value;
	var telefono = document.getElementById("TelefonoP").value;
	var letras=/^[A-Za-z\_\-\.\s\xF1\xD1]+$/;

	if ( (apellido == "") || (nombre == "") || (fec == "" ) || (genero == "") || (tipoDoc == "") || (numeroDoc == "") || (domicP == "") ){
		alert("Los campos indicados con (*) son obligatorios");
		return false;
	} else if( isNaN(telefono) || isNaN(numeroDoc)){
		alert("El telefono (y/o) Nro de documento ingresado NO es un numero");
		return false;
	} else if (apellido.lenght>30 || nombre.lenght>30) {
		alert("El nombre (y/o) apellido ingresados exceden el maximo de caracteres");
		return false;
	}else if ( (! letras.test(nombre)) || (! letras.test(apellido)) ){
        alert("El nombre debe tener solo letras");
        return false;
    }
}