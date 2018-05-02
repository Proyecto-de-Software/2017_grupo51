var pagMostrada = 1;

function mostrarTablas(id){
    //Funcion para realizar la paginacion de la tabla. Muestra las filas con el id pasado por parametro
    // y oculta las filas que se estaban mostrando previamente.
    $('[title='+pagMostrada+']').addClass('hide');
    $('[id=selec'+pagMostrada+']').removeClass('active');
    $('[title='+id+']').removeClass('hide');
    $('[id=selec'+id+']').addClass('active');
    pagMostrada = id;
}

function visibilidadInput(valor){
    //Muestra u oculta el input para ingresar el nombre de usuario a mostrar.
    if(valor == 'permisoBuscarNombreUsuario'){
        $("#buscador").removeClass('hide');
    }else{
        $("#buscador").addClass('hide');
    }
}

function confirmacion(mensaje){
    //Mensaje de confirmacion.
    return confirm(mensaje);
}

function obtenerDatos(){
    //Obtiene los datos necesarios para realizar la busqueda de usuarios.
    var seleccion = $("#seleccion").val();
    if(seleccion == 'permisoBuscarNombreUsuario'){
        var valorEntrada = $("#buscador").val();
        var letras=/^[a-zA-Z\s]*$/;
        if(valorEntrada == '' || valorEntrada.search(letras)){
            alert('Debes ingresar un nombre de usuario valido');
            return false;
        }
    }
    return true;
}
