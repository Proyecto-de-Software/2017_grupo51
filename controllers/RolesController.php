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
        if(UserController::getInstance()->sePuedeAccederASeccion('roles_seccion')){
            $layout = IndexController::getInstance()->layout();
            $layout['titulo'] = 'Bienvenido a la seccion de roles.';
            $layout['id_usuario'] = $_SESSION['id_usuario'];
            $layout['rol_nombre'] = $_SESSION['rolNombre'];
            Home::getInstance()->show('seccionRoles.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }

    
    public function cambiarRol(){
        //Accede a la pagina de eleccion de roles
        if(UserController::getInstance()->sePuedeAccederASeccion('roles_seccion')){
            UserController::getInstance()->nuevaSesion($_SESSION['id_usuario']);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function puedeCambiarDeRoles($idUsuario){
        //Consulta y chequea la cantidad de roles de un usuario
        $roles = RolesModel::getInstance()->roles($idUsuario);
        if(count($roles) == 1){
            return false;
        }else{
            return true;
        }
    }
    
    public function manejoDeRolesUsuarios(){
        //Obtiene cada usuario con los roles asignados y no asignados, carga la pagina asignar/desasignar rol
        if(UserController::getInstance()->sePuedeAccederASeccion('usuario_index')){
            $layout = IndexController::getInstance()->layout();
            $usuarios = UserModel::getInstance()->listadoUsuarios($_SESSION['id_usuario']);
            if(count($usuarios) > 0){
                $roles = RolesModel::getInstance()->listadoDeRoles();
                $arregloAEnviar = [];
                foreach ($usuarios as $usuario){
                    $arregloAEnviar[$usuario['id']] = [];
                    foreach ($roles as $rol) {
                        $arregloAEnviar[$usuario['id']][$rol['id']] = UserModel::getInstance()->usuarioPoseeRol($usuario['id'],$rol['id']); 
                    }
                }
                $layout['titulo'] = 'Listado de usuarios con sus roles.';
                $layout['usuariosYroles'] = $arregloAEnviar;
                $layout['usuarios'] = $usuarios;
                $layout['elemPorPagina'] = ConfigurationModule::getInstance()->elementosPorPagina();
                $layout['roles'] = $roles;
            }else{
                $layout['titulo'] = 'No hay usuarios registrados.';
            }
            Home::getInstance()->show('seccionRoles.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function asignacionRol($usuarioId,$rolId,$asignarRol){
        //Si asignarRol es falso, desasigna rol. Si es true, asigna rol
        if(UserController::getInstance()->sePuedeAccederASeccion('roles_usuario_update')){
            $existeUsuario = UserController::getInstance()->existeUsuarioConId($usuarioId);
            $existeRol = $this->existeRolConId($rolId);
            if($existeRol && $existeUsuario){
                if($asignarRol){
                    RolesModel::getInstance()->asignarRol($usuarioId,$rolId);
                }else{
                    RolesModel::getInstance()->desasignarRol($usuarioId,$rolId);
                }
                $this->manejoDeRolesUsuarios();
            }else{
                IndexController::getInstance()->index();
            }
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function existeRolConId($rolId){
        //Retorna si existe usuario con el id pasado por parametro.
        $existe = RolesModel::getInstance()->obtenerRol($rolId);
        if(count($existe) == 0){
            return false;
        }else{
            return true;
        }
    }
}