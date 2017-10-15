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
    
    public function checkPermission($permission){
        //Se busca si el usuario con sesion iniciada tiene el permiso pasado por parametro permitido.
        //if(!isset($_SESSION)){session_start();}
        if(!isset($_SESSION)){session_start();}
            if(isset($_SESSION['rolId'])){
                $rol = $_SESSION['rolId'];
                if(ConfigurationModule::getInstance()->tienePermiso($rol,$permission)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
    }
    
    
    public function validarInicioSesion(){
        //Validacion del formulario para iniciar sesion
        //Pregunta por isset($_POST['usr']) por si se accede por url.
        if(isset($_POST['usr'])){
            $datos['nombre_usuario'] = $_POST['usr'];
            $datos['contraseña_usuario'] = $_POST['contraseña'];
            //Cheque que exista un usuario con los datos ingresados.
            $usuario = UserModel::getInstance()->existeUsuario($datos['nombre_usuario'],$datos['contraseña_usuario']);
            if (count($usuario) == 0){
                //Aviso que no existe usuario
                IniciarSesion::getInstance()->iniciarS('no_existe');
            }elseif (!UserModel::getInstance()->estaActivo($usuario[0]['id'])) {
                //Aviso que el usuario a sido bloqueado
                IniciarSesion::getInstance()->iniciarS('no_activo');
            }else{
                //Si la pagina no esta activa, chequea de que el usuario que quiera ingresar, sea Administrador.
                if(!AppController::getInstance()->comprobarPaginaActiva()){
                    if(UserModel::getInstance()->chequearRol($usuario[0]['id'],'Administrador')){
                        UserController::getInstance()->nuevaSesion($usuario[0]['id']);
                    }else{
                        IndexController::getInstance()->index();
                    }
                }else{
                    UserController::getInstance()->nuevaSesion($usuario[0]['id']);
                }
        }}else{
            IndexController::getInstance()->index();
        }
    }


    public function comprobarPaginaActiva(){
        //Retorna el valor de 'active' de la pagina.
        $configuracion = ConfigurationModule::getInstance()->indexPageInfo();
        return $configuracion->getActive();
    }
    
    public function volverAInicio(){
        //Vuelve a la pagina principal
        session_start();
        $layout = IndexController::getInstance()->layout();
        $layout['rol_nombre'] = $_SESSION['rolNombre'];
        Home::getInstance()->show('paginaPrincipal.html.twig',$layout);
    }
}