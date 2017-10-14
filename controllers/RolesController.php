<?php

class RolesController{
    private static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public function permitirAccesoSitioInhabilitado($rol){
        //Retorna si el rol pasado por parametro tiene permiso de acceder a la seccion de configuracion.
        return ConfigurationModule::getInstance()->tienePermiso($rol,'configuracion');
    }
    
    public function accesoPagRoles(){
        //Accede a la seccion de roles.
        if(UserController::getInstance()->sePuedeAccederASeccion()){
            $layout = IndexController::getInstance()->layout();
            $layout['titulo'] = 'Bienvenido a la seccion de roles.';
            $layout['id_usuario'] = $_SESSION['id_usuario'];
            Home::getInstance()->show('seccionRoles.html.twig',$layout);
        }
    }

    
    public function cambiarRol(){
        //Accede a la pagina de eleccion de roles
        if(UserController::getInstance()->sePuedeAccederASeccion()){
            UserController::getInstance()->nuevaSesion($_SESSION['id_usuario']);
        }
    }
    
    public function puedeCambiarDeRoles($idUsuario){
        //Consulta y chequea la cantidad de roles de un usuario
        $roles = UserModel::getInstance()->roles($idUsuario);
        if(count($roles) == 1){
            return false;
        }else{
            return true;
        }
    }
}