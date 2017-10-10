<?php

class AppController{
    private static $instance;
    private static $user;


    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();   
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public static function getUser(){
        return self::$user;
    }
    
    public function checkPermission($permission){
        //Se busca si el usuario con sesion iniciada tiene el permiso pasado por parametro permitido.
    }
    
    public function validarInicioSesion(){
        if(isset($_POST['usr'])){
            $datos['nombre_usuario'] = $_POST['usr'];
            $datos['contraseña_usuario'] = $_POST['contraseña'];
            $usuario = UserValidation::getInstance()->existeUsuario($datos['nombre_usuario'],$datos['contraseña_usuario']);
            if (count($usuario) == 0){
                IniciarSesion::getInstance()->iniciarS('no_existe');
            }elseif (!UserValidation::getInstance()->estaActivo($usuario[0]['id'])) {
                IniciarSesion::getInstance()->iniciarS('no_activo');
            }else{
                if(!AppController::getInstance()->comprobarPaginaActiva()){
                    if(UserValidation::getInstance()->chequearRol($usuario[0]['id'],'Administrador')){
                        UserController::getInstance()->nuevaSesion($usuario);
                    }else{
                        IndexController::getInstance()->index();
                    }
                }else{
                    UserController::getInstance()->nuevaSesion($usuario);
                }
        }}else{
            IndexController::getInstance()->index();
        }
    }


    public function comprobarPaginaActiva(){
        $configuracion = ConfigurationModule::getInstance()->indexPageInfo();
        return $configuracion->getActive();
    }
}