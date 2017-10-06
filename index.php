<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once('controllers/IndexController.php');
require_once('controllers/FormPaciente.php');
require_once('controllers/IniciarSesion.php');
require_once('models/PDORepository.php');
require_once('models/ConfigurationModule.php');
require_once('models/Configuration.php');
require_once('views/TwigView.php');
require_once('views/Home.php');
require_once('controllers/CreateUsuario.php');


    
if(!isset($_GET['action'])){
    IndexController::getInstance()->index();
}elseif ($_GET['action']=='inicio') {
    InicioSesion::getInstance()->inicio();
}elseif ($_GET['action']=='iniciarSesion'){
	IniciarSesion::getInstance()->iniciarS();
}elseif ($_GET['action']=='formPaciente'){
	FormPaciente::getInstance()->forPac();
}elseif ($_GET['action']=='createUsr'){
	CreateUsuario::getInstance()->creaUsr();
}
