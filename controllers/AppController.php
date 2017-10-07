<?php

class AppController{
    private static $instance;
    private static $user;
    private static $num = 0;


    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();   
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public static function getUser(){
        if (!isset(self::$user)){
            // Aca se crea al usuario
        }
        return self::$user;
    }
    
    public function validarInicioSesion(){
        //if(AppController::getInstance()->comprobarPaginaActiva()){
        $datos['nombre_usuario'] = $_POST['usr'];
        $datos['contraseña_usuario'] = $_POST['contraseña'];
        $existeUsuario = UserValidation::getInstance()->existeUsuario($datos['nombre_usuario'],$datos['contraseña_usuario']);
        
        if (!$existeUsuario){
            //pop up
        }elseif (4==4) {
            //
        }
    }
    
    public function comprobarPaginaActiva(){
        $configuracion = ConfigurationModule::getInstance()->indexPageInfo();
        return $configuracion->getActive();
    }
}