
function validarEnvio(rol_nombre){
    //Funcion llamada desde submit de busqueda de usuarios
    var elemento = $("#seleccion").val();
    
    if(elemento == 0){
        alert('Por favor, selecciona una opcion de busqueda');
        return false;
    }else{
        return permisoConfiguracion(elemento,rol_nombre);
    }
}

function permisoConfiguracion(permiso,rolnombre){
    //Retorna si un usuario posee o no un permiso dado
    var tienePermiso = chequePermisoValido(permiso);
    if(!tienePermiso){
        alert('Los usuarios con rol de ' + rolnombre + ' no poseen permisos para acceder a esta funcionalidad.'); 
    }
    return tienePermiso;
}

function chequePermisoValido(permiso){
    //Chequea si un usuario posee un permiso dado
    var retorno = false;
    $.ajax({
        url: './index.php',
        data: { action : permiso },
        async: false,
        success: function(res){
            if(res){
                retorno = true;
            }
        }});
    return retorno;
}