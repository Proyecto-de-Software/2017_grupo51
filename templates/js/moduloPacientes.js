function visibilidadTipoDocumento(seleccion){
    if(seleccion == 'buscarDocumentoPaciente'){
        $("#selectDoc").removeClass('hide');
    }else{
        $("#selectDoc").addClass('hide');
    }
}

function validarBusqueda(){
    var seleccion = $("#seleccion").val();
    if(seleccion == 0){
        alert('Debe seleccionar una opcion para buscar paciente.');
        return false;
    }else{
        var contenidoInputBusqueda = $("#buscador").val();
        if(contenidoInputBusqueda != ''){
            if(seleccion == 'buscarNombrePaciente' || seleccion == 'buscarApellidoPaciente'){
                var letras = /^[a-zA-Z\s]*$/;
                if(contenidoInputBusqueda.search(letras)){
                    alert('Debe ingresar solo letras.');
                    return false;
                }
            }else{
                var selectorDocumento = $("#selectDoc").val();
                if(selectorDocumento == 0){
                    alert('Selecciona un tipo de documento.');
                    return false;
                }else{
                    var numeros = /^[0-9]+$/;
                    if(contenidoInputBusqueda.search(numeros)){
                        alert('Debe ingresar solo numeros.');
                        return false;
                    }
                }
                
            }
        }else{
            return false;
        }
    }
    return true;
}