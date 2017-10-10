<?php
class UserController{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function nuevaSesion($usuario){
        // Se crea una nueva sesion y el usuario, caso de poseer mas de un rol, elige con cual entrar.
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['id_usuario'] = $usuario[0]['id'];
        }
        $roles = UserValidation::getInstance()->roles($usuario[0]['id']);
        if(count($roles) > 1){
            $parametros['roles'] = $roles;
            $parametros['id'] = $usuario[0]['id'];
            self::getInstance()->eleccionRolIngreso($parametros);
        }else{
            self::getInstance()->iniciarSesionComoRol($roles[0]['nombre'],$roles[0]['id']);
        }
    }
    
    public function eleccionRolIngreso($roles){
        $layout = IndexController::getInstance()->layout();
        $layout['roles'] = $roles;
        Home::getInstance()->show('eleccionRol.html.twig',$layout);
    }
    
    public function iniciarSesionComoRol($rol,$rol_id,$usuario_id){
        //Crea una sesion nueva con el usuario y rol ingresado
        if(self::getInstance()->chequeoAccesoValido($usuario_id)){
            session_start();
            $_SESSION['rolId'] = $rol_id;
            $_SESSION['rolNombre'] = $rol;
            $layout = IndexController::getInstance()->layout();
            $layout['rol'] = $rol_id;
            Home::getInstance()->show('paginaPrincipal.html.twig',$layout);
        }else{
            
        }    
    }
    
    public function chequeoAccesoValido($id){
        //Chequea si el usuario con id $id esta activo, de ser asi retornara true, caso contrario accede a 
        // otra pagina con un mensaje informando la situacion.
        
        if(UserValidation::getInstance()->estaActivo($id)){
            return true;
        }else{
            $layout = IndexController::getInstance()->layout();
            $layout['mensajeInactivo'] = 'El admisnitrador ha desactivado tu cuenta';
            Home::getInstance()->show('mensajeInactividad.html.twig',$layout);
            return false;
        }
    }
    
}