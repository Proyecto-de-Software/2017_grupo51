<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once('controllers/IndexController.php');
require_once('controllers/AppController.php');
require_once('controllers/Configuracion.php');
require_once('controllers/Paciente.php');
require_once('controllers/IniciarSesion.php');
require_once('controllers/RolesController.php');
require_once('controllers/UserController.php');
require_once('models/PDORepository.php');
require_once('models/ConfigurationModule.php');
require_once('models/UserModel.php');
require_once('models/RolesModel.php');
require_once('models/PacienteModel.php');
require_once('models/Configuration.php');
require_once('views/TwigView.php');
require_once('views/Home.php');





if(!isset($_GET['action'])){
    IndexController::getInstance()->index();
}elseif ($_GET['action']=='inicio') {
    InicioSesion::getInstance()->inicio();
}elseif ($_GET['action']=='iniciarSesion'){
    IniciarSesion::getInstance()->iniciarS('');
}elseif ($_GET['action']=='formPaciente'){
    Paciente::getInstance()->forPac();
}elseif($_GET['action']=='nuevaSesion'){
    AppController::getInstance()->validarInicioSesion();
}elseif ($_GET['action']=='createUsr'){
    UserController::getInstance()->creaUsr();
}elseif ($_GET['action']=='ingresoAlSitio'){
    if((isset($_GET['idrol']))&&(isset($_GET['nombrerol']))&&(isset($_GET['idusuario']))){
        if(is_numeric($_GET['idrol'])&& is_string($_GET['nombrerol'])&& is_numeric($_GET['idusuario'])){
            UserController::getInstance()->iniciarSesionComoRol($_GET['nombrerol'],$_GET['idrol'],$_GET['idusuario'],true);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
    }
}elseif($_GET['action']=='permisoConfiguracion'){
    echo AppController::getInstance()->checkPermission('configuracion');
}elseif($_GET['action'] == 'accesoConfiguracion'){
    Configuracion::getInstance()->accesoPagConfiguracion();
}elseif($_GET['action'] == 'cerrarSesion'){
    UserController::getInstance()->cerrarSesion();
    IndexController::getInstance()->index();
}elseif($_GET['action'] == 'usuarios'){
    UserController::getInstance()->seccionUsuarios();
}elseif($_GET['action'] == 'volverAInicio'){
    AppController::getInstance()->volverAInicio();
}elseif($_GET['action'] == 'permisoListadoUsuario'){
    echo AppController::getInstance()->checkPermission('usuario_index');
}elseif($_GET['action'] == 'permisoNuevoControl'){
    echo AppController::getInstance()->checkPermission('control_new');
}elseif ($_GET['action'] == 'permisoVerUsuario') {
    echo AppController::getInstance()->checkPermission('usuario_show');
}elseif($_GET['action'] == 'permisoListarControles'){
    echo AppController::getInstance()->checkPermission('control_index');
}elseif($_GET['action'] == 'permisoEliminarControl'){
    echo AppController::getInstance()->checkPermission('control_delete');
}elseif($_GET['action'] == 'permisoBuscarNombreUsuario'){
    echo AppController::getInstance()->checkPermission('busqueda_usuario_nombreusuario');
}elseif(($_GET['action'] == 'permisoBuscarActivos')||($_GET['action'] == 'permisoBuscarBloqueados')){
    echo AppController::getInstance()->checkPermission('busqueda_usuario_activos');
}elseif($_GET['action'] == 'listadoCompletoUsuarios'){
    UserController::getInstance()->listadoCompletoUsuarios();
}elseif ($_GET['action'] == 'cambiarEstadoUsuario') {
    if((isset($_GET['id']))&&(isset($_GET['estado']))){
        if(is_numeric($_GET['id']) &&  is_numeric($_GET['estado'])){
            UserController::getInstance()->modificarEstado($_GET['id'],$_GET['estado']);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }else{    
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'eliminarUsuario'){
    if(isset($_GET['id'])){
        if(is_numeric($_GET['id'])){
            UserController::getInstance()->eliminarUsuario($_GET['id']);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'buscarUsuarios'){
    if((isset($_POST['busquedaUsuario'])) && ($_POST['busquedaUsuario'] != "0")){
        if($_POST['busquedaUsuario'] == 'permisoBuscarNombreUsuario'){
            if(is_string($_POST['nombreUsuario'])){
                UserController::getInstance()->buscarPorNombreUsuario($_POST['nombreUsuario']);
            }else{
                UserController::getInstance()->cerrarSesion();
                IndexController::getInstance()->index();
            }
        }elseif($_POST['busquedaUsuario'] == 'permisoBuscarActivos'){
            UserController::getInstance()->buscarPorEstado(1);
        }elseif($_POST['busquedaUsuario'] == 'permisoBuscarBloqueados'){
            UserController::getInstance()->buscarPorEstado(0);
        }
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'permisoCrearUsuario'){
    echo AppController::getInstance()->checkPermission('usuario_new');
}elseif($_GET['action'] == 'roles'){
    RolesController::getInstance()->accesoPagRoles();
}elseif($_GET['action'] == 'cambioRoles'){
    RolesController::getInstance()->cambiarRol();
}elseif($_GET['action'] == 'cantidadRoles'){
    if(isset($_GET['idUsuario'])){
        if(is_string($_GET['idUsuario'])){
            echo RolesController::getInstance()->puedeCambiarDeRoles($_GET['idUsuario']);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'verMiUsuario'){
    UserController::getInstance()->verMiUsuario();
}elseif($_GET['action'] == 'pacientes'){
    Paciente::getInstance()->accesoPagPacientes();
}elseif($_GET['action'] == 'permisoCrearPaciente'){
    echo AppController::getInstance()->checkPermission('paciente_new');
}elseif($_GET['action'] == 'permisoListarPaciente'){
    echo AppController::getInstance()->checkPermission('paciente_index');
}elseif($_GET['action'] == 'listarPacientes'){
    Paciente::getInstance()->listadoCompletoPacientes();
}elseif($_GET['action'] == 'permisoVerDatosCompletosPaciente'){
    echo AppController::getInstance()->checkPermission('paciente_show');
}elseif($_GET['action'] == 'permisoEliminarPaciente'){
    echo AppController::getInstance()->checkPermission('paciente_destroy');
}elseif($_GET['action'] == 'verDatosCompletosPaciente'){
    if(isset($_GET['id'])){
        if(is_numeric($_GET['id'])){
            Paciente::getInstance()->verDatosCompletosPaciente($_GET['id']);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'eliminarPaciente'){
    if(isset($_GET['id'])){
        if(is_numeric($_GET['id'])){
            Paciente::getInstance()->eliminarPaciente($_GET['id']);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'buscarPaciente'){
    if((isset($_POST['busquedaPaciente'])) && ($_POST['busquedaPaciente']!= "0")){
        if(isset($_POST['buscaPaciente'])){
            if($_POST['busquedaPaciente'] == 'buscarNombrePaciente' ){
                Paciente::getInstance()->buscarPorNombre($_POST['buscaPaciente']);
            }elseif ($_POST['busquedaPaciente'] == 'buscarApellidoPaciente' ) {
                Paciente::getInstance()->buscarPorApellido($_POST['buscaPaciente']);
            }elseif($_POST['busquedaPaciente'] == 'buscarDocumentoPaciente'){
                if((isset($_POST['selectDoc'])) && ($_POST['selectDoc'] != "0")){
                    Paciente::getInstance()->buscarPorDocumento($_POST['buscaPaciente'],$_POST['selectDoc']);
                }else{
                    UserController::getInstance()->cerrarSesion();
                    IndexController::getInstance()->index();
                }
            }else{
                UserController::getInstance()->cerrarSesion();
                IndexController::getInstance()->index();
            }
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action']== 'existePaciente'){
    if(isset($_GET["numero_doc"])){
        echo Paciente::getInstance()-> ya_existe_paciente($_GET ["numero_doc"]);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }

}elseif($_GET['action']== 'existe_Usuario'){
    if(!isset($_GET["email_usuario"]) || !isset($_GET["nombre_deUsuario"])){
        IndexController::getInstance()->index();
    }else {
        echo UserController::getInstance()-> ya_existe_usuario($_GET["email_usuario"], $_GET["nombre_deUsuario"]);
    }
}elseif($_GET['action']=='CrearUsuario'){
    UserController::getInstance()->crearTabla();
}elseif($_GET['action']=='CrearPaciente'){
    Paciente::getInstance()->crearTablaPaciente(false,0);
}elseif($_GET['action'] == 'permisoSeccionUsuarios'){
    echo AppController::getInstance()->checkPermission('usuario_seccion');
}elseif($_GET['action'] == 'permisoSeccionPacientes'){
    echo AppController::getInstance()->checkPermission('paciente_seccion');
}elseif($_GET['action'] == 'permisoSeccionRoles'){
    echo AppController::getInstance()->checkPermission('roles_seccion');
}elseif($_GET['action'] == 'permisoHistoriaClinica'){
    echo AppController::getInstance()->checkPermission('paciente_historia');
}elseif($_GET['action'] == 'actualizarConfiguracion'){
    Configuracion::getInstance()->formularioConfiguracion();
}elseif($_GET['action'] == 'enviarConfiguracion'){
    Configuracion::getInstance()->actualizar();
}elseif($_GET['action'] == 'mostrarConfiguracion'){
    Configuracion::getInstance()->mostrarInformacion();
}elseif($_GET['action'] == 'manejoDeRoles'){
    RolesController::getInstance()->manejoDeRolesUsuarios();
}elseif($_GET['action'] == 'desasignarRol'){
    if(isset($_GET['rolId']) && isset($_GET['usuarioId'])){
        RolesController::getInstance()->asignacionRol($_GET['usuarioId'],$_GET['rolId'],false);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'asignarRol'){
    if(isset($_GET['rolId']) && isset($_GET['usuarioId'])){
        RolesController::getInstance()->asignacionRol($_GET['usuarioId'],$_GET['rolId'],true);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'modificarUsuario'){
    UserController::getInstance()->modificarUsuario();
}elseif($_GET['action'] == 'actualizarUsuario'){
    UserController::getInstance()->actualizarUsuario();
}elseif($_GET['action'] == 'modificarPaciente'){
    if(isset($_GET['idPaciente']) && is_numeric($_GET['idPaciente'])){
        Paciente::getInstance()->modificarPaciente($_GET['idPaciente']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'actualizarPaciente'){
    if(isset($_GET['idPaciente']) && is_numeric($_GET['idPaciente'])){
        Paciente::getInstance()->crearTablaPaciente(true,$_GET['idPaciente']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'historiaClinica'){
    if(isset($_GET['idPaciente']) && is_numeric($_GET['idPaciente']) && (isset($_GET['nacimiento'])) ){
        Paciente::getInstance()->verHistoriaClinica($_GET['idPaciente'],$_GET['nacimiento']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'graficoPercentiloCefalico'){
    if(isset($_GET['idPaciente']) && (isset($_GET['sexo'])) && (isset($_GET['nacimiento'])) && (($_GET['sexo'] == 'Masculino') || ($_GET['sexo'] == 'Femenino')) && is_numeric($_GET['idPaciente'])){
        Paciente::getInstance()->verGraficoPercentiloCefalico($_GET['idPaciente'],$_GET['sexo'],$_GET['nacimiento']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'graficoTalla'){
    if(isset($_GET['idPaciente']) && (isset($_GET['sexo'])) && (isset($_GET['nacimiento'])) && (($_GET['sexo'] == 'Masculino') || ($_GET['sexo'] == 'Femenino')) && is_numeric($_GET['idPaciente'])){
        Paciente::getInstance()->verGraficoTalla($_GET['idPaciente'],$_GET['sexo'],$_GET['nacimiento']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'graficoPeso'){
    if(isset($_GET['idPaciente']) && (isset($_GET['sexo'])) && (isset($_GET['nacimiento'])) && (($_GET['sexo'] == 'Masculino') || ($_GET['sexo'] == 'Femenino')) && is_numeric($_GET['idPaciente'])){
        Paciente::getInstance()->verGraficoPeso($_GET['idPaciente'],$_GET['sexo'],$_GET['nacimiento']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'verControlCompleto'){
    if(isset($_GET['idControl']) && (is_numeric($_GET['idControl'])) && (isset($_GET['nacimiento'])) ){
        Paciente::getInstance()->verControlCompleto($_GET['idControl'],$_GET['nacimiento']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'eliminarControl'){
    if( (isset($_GET['idControl'])) && (is_numeric($_GET['idControl'])) && (isset($_GET['nacimiento'])) && (isset($_GET['idPaciente'])) && (is_numeric($_GET['idPaciente'])) ){
        Paciente::getInstance()->eliminarControl($_GET['idControl'],$_GET['nacimiento'],$_GET['idPaciente']);
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}elseif($_GET['action'] == 'accesoGraficosDemograficos'){
    Paciente::getInstance()->listadoGraficosDemograficos();
}elseif($_GET['action'] == 'verGraficoMascotas'){
    Paciente::getInstance()->verGraficoMascotas();
}elseif($_GET['action'] == 'verGraficoElectricidad'){
    Paciente::getInstance()->verGraficoElectricidad();
}elseif($_GET['action'] == 'verGraficoHeladera'){
    Paciente::getInstance()->verGraficoHeladera();
}elseif($_GET['action'] == 'verGraficoAgua'){
    Paciente::getInstance()->verGraficoAgua();
}elseif($_GET['action'] == 'verGraficoVivienda'){
    Paciente::getInstance()->verGraficoVivienda();
}elseif($_GET['action'] == 'verGraficoCalefaccion'){
    Paciente::getInstance()->verGraficoCalefaccion();
}elseif($_GET['action'] == 'nuevoControl'){
    if(isset($_GET['idPaciente']) && is_numeric($_GET['idPaciente']) ){
        //Aca va el llamado a la funcion de crear control
    }else{
        UserController::getInstance()->cerrarSesion();
        IndexController::getInstance()->index();
    }
}
