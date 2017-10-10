// validad los formularios para creacion de un nuevo usuario
function validarCrearUsuario (){
	if (emailUs === "" || nombreUs === "" || contraUs === "" || nombreRealUs === "" || apellidoUs === "" ) {
		alert("Los campos indicados con (*) son obligatorios");
		return false;
	}
	if (emailUs.lenght > 255 || nombreUs.lenght > 255 || contraUs.lenght > 255 || nombreRealUs.lenght > 255 || apellidoUs.lenght > 255  ){
		alert("excedio el limite de caracteres en algun campo")
	return false
	}
}