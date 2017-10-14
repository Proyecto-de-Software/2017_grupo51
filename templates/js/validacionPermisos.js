//Funcion llamada desde submit de busqueda de usuarios
function validarEnvio(rol_nombre){
    var elemento = $("#seleccion").val();
    
    if(elemento == 0){
        alert('Por favor, selecciona una opcion de busqueda');
        return false;
    }else{
        return permisoConfiguracion(elemento,rol_nombre);
    }
}

function permisoConfiguracion(permiso,rolnombre){
    var tienePermiso = chequePermisoValido(permiso);
    if(!tienePermiso){
        alert('Los usuarios con rol de ' + rolnombre + ' no poseen permisos para acceder a esta funcionalidad.'); 
    }
    return tienePermiso;
}

function chequePermisoValido(permiso){
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