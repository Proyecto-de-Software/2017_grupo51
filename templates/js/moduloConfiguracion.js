function validarInformacion(){
    
    var estructuraMail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    var titulo = $('#tituloPagina').val();
    var mail = $('#mailContacto').val();
    var elementosPagina = $('#elementosPagina').val();
    
    if(titulo == '' || mail == '' || elementosPagina == ''){
        alert('Hay campos vacios.');
        return false;
    }
    if (!estructuraMail.test(mail)){
        alert('Ingresa un mail valido.');
        return false;
    }
    if(isNaN(elementosPagina)){
        alert('Debes ingresar un numero de elementos por pagina de listado.');
        return false;
    }
    if(elementosPagina <= 0){
        alert('Debes ingresar un numero mayor a 0.');
        return false;
    }
    if(!document.formConfiguracion.estadoSitio[0].checked && !document.formConfiguracion.estadoSitio[1].checked){
    	alert("Seleccione una opcion en para el estado del sitio.");
    	return false;
    }
    return true;
}

