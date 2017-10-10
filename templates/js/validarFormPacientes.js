//funcion para validar el Formulario de creacion de pacientes
function validarFormPac (){
	//fijarme que onda el traer el id de los campos del form, pasar parametros o ir a buscarlo directo con la ruta
	var apellido = document.getElementById("ApellidoP").value;
	var nombre = document.getElementById("NombreP").value;
	var fec = document.getElementById("FecNacP").value;
	var genero = document.getElementById("GeneroP").value;
	var tipoDoc = document.getElementById("TipoDocP").value;
	var varnumeroDoc = document.getElementById("NumDocP").value;
	var domicP = document.getElementById("DomicoP").value;
	var telefono = document.getElementById("TelefonoP").value;

	// el === es igualdad estricta
	if (apellido === "" || nombre === "" || fec === "" || genero === "" || tipoDoc === "" || varnumeroDoc === "" || domicP === "" ) {
		alert("Los campos indicados con (*) son obligatorios");
		return false;
	} else if( isNaN(telefono) || isNaN(varnumeroDoc)){
		alert("El telefono (y/o) Nro de documento ingresado NO es un numero");
		return false;
	} else if (apellido.lenght>30 || nombre.lenght>30) {
		//la validacion de mayor a 30 nola pide pero es para probar si se envia el form
		alert("El nombre (y/o) apellido ingresados exceden el maximo de caracteres");
		return false;
	}

}