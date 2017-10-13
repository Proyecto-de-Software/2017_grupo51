function permisoConfiguracion(accion,rolid,rolnombre){
    var permiso = chequePermisoValido(accion,rolid);
    if(!permiso){
        alert('Los usuarios con rol de ' + rolnombre + ' no poseen permisos para acceder a esta funcionalidad.'); 
    }
    return permiso;
}

function chequePermisoValido(accion,rol){
    var retorno = false;
    $.ajax({
        url: './index.php',
        data: { action : accion, rol : rol },
        async: false,
        success: function(res){
            if(res === 'true'){
                retorno = true;
            }
        }});
    return retorno;
}