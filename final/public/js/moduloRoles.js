function poseeMasDeUnRol(id){
    //Chequea que el usuario posea mas de un rol para poder cambiar.
    var puedeCambiarRol = chequeoCantRoles(id);
    if(puedeCambiarRol){
        return true;
    }else{
        alert('Posees un unico rol asignado, no puedes cambiar.');
        return false;
    }
}

function chequeoCantRoles(id){
    //Consulta la cantidad de roles de un usuario.
    var retorno = false;
    $.ajax({
        url: './index.php',
        data: { action : 'cantidadRoles', idUsuario: id },
        async: false,
        success: function(res){
            if(res){
                retorno = true;
            }
        }});
    return retorno;
}

function puedeDesasignar(idUsuario){
    //Retorna si se puede o no desasignar un rol a un usuario
    var poseeMasDeUnRol = chequeoCantRoles(idUsuario);
    if(poseeMasDeUnRol){
        return true;
    }else{
        alert('El usuario no puede quedar sin roles asignados.');
        return false;
    }
}