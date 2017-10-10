<?php
class UserController{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function creaUsr(){
        $layout = IndexController::getInstance()->layout();
        Home::getInstance()->show('crearUsr.html.twig',$layout);
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
            $this->eleccionRolIngreso($parametros);
        }else{
            $this->iniciarSesionComoRol($roles[0]['nombre'],$roles[0]['id'],$usuario[0]['id'],false);
        }
    }
    
    public function eleccionRolIngreso($roles){
        //Carga la vista para la eleccion de roles de un usuario
        $layout = IndexController::getInstance()->layout();
        $layout['roles'] = $roles;
        Home::getInstance()->show('eleccionRol.html.twig',$layout);
    }
    
    public function iniciarSesionComoRol($rol,$rol_id,$usuario_id,$eligio_rol){
        //Crea una sesion nueva con el usuario y rol ingresado
        if(!AppController::getInstance()->comprobarPaginaActiva()){
            if(!RolesController::getInstance()->permitirAccesoSitioInhabilitado($rol_id)){
                $this->cerrarSesion();
                return;
            }
        }
        if($this->chequeoAccesoUsuarioActivo($usuario_id)){
            if($eligio_rol){session_start();}
            if(isset($_SESSION['id_usuario'])){ //Este if-else esta hecho en el caso de querer acceder directamente por url, se chequea que se haya creado la sesion
                $_SESSION['rolId'] = $rol_id;
                $_SESSION['rolNombre'] = $rol;
                $layout = IndexController::getInstance()->layout();
                $layout['rol_id'] = $rol_id;
                $layout['rol_nombre'] = $rol;
                Home::getInstance()->show('paginaPrincipal.html.twig',$layout);
                return;
            }
            IndexController::getInstance()->index();  
            }           
    }
    
    public function cerrarSesion(){
        session_start();
        session_destroy();
        if(isset($_SESSION['id_usuario'])){
            session_unset();
        }
        IndexController::getInstance()->index();
    }
    public function chequeoAccesoUsuarioActivo($id){
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