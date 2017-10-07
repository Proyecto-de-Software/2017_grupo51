
// funcion para validar el correo
function caracteresCorreoValido(email, div){
    
    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

    if (caract.test(email) == false){
        $(div).hide().removeClass('hide').slideDown('fast');
        return false;
    }else{
        $(div).hide().addClass('hide').slideDown('slow');
        return true;
    }
}

function algo(){
    alert('No podes entrar');
}