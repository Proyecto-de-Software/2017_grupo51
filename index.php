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
require_once('models/User.php');
require_once('models/ConfigurationModule.php');
require_once('models/UserValidation.php');
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
        UserController::getInstance()->iniciarSesionComoRol($_GET['nombrerol'],$_GET['idrol'],$_GET['idusuario'],true);
    }else{
        IndexController::getInstance()->index();
    }
}elseif($_GET['action']=='permisoConfiguracion'){
    echo Configuracion::getInstance()->permisoConfiguracion();
}elseif($_GET['action'] == 'accesoConfiguracion'){
    Configuracion::getInstance()->ejecutar();
}elseif($_GET['action'] == 'cerrarSesion'){
    UserController::getInstance()->cerrarSesion();
}
