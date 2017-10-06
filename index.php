<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once('controllers/iniciarSesion.php');
require_once('controllers/IndexController.php');
require_once('views/TwigView.php');
require_once('views/Home.php');
require_once('controllers/FormPaciente.php');

    
if(!isset($_GET['action'])){
    IndexController::getInstance()->index();
}elseif ($_GET['action']=='inicio') {
    InicioSesion::getInstance()->inicio();
}elseif ($_GET['action']=='iniciarSesion'){
	IniciarSesion::getInstance()->iniciarS();
}elseif ($_GET['action']=='formPaciente');{
	FormPaciente::getInstance()->forPac();
}
