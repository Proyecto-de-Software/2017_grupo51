function permisoConfiguracion(rol){
    $.ajax({
        url: './index.php',
        data: { action : 'permisoConfiguracion' },
        success: function(res){
            if(res == 'false'){
                alert('No tenes permiso');
            }
        }
        })
}