//funcion para validar el Formulario de creacion de pacientes
function validarFormPac ();{
	//fijarme que onda el traer el id de los campos del form, pasar parametros o ir a buscarlo directo con la ruta
	// el === es igualdad estricta
	if (ApellidoP === "" || NombreP === "" || FecNacP=== "" || GeneroP === "" || TipoDocP === "" || NumDocP === "" || DomicP === "" ) {
		alert("Los campos indicados con (*) son obligatorios");
		return false;
	}
	if( isNaN(telefonoP) || isNaN(NumDocP)){
		alert("El telefono (y/o) Nro de documento ingresado NO es un numero");
		return false;
	}

}