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
        //Muestra el formulario de creacion de un nuevo usuario.
        $layout = IndexController::getInstance()->layout();
        Home::getInstance()->show('crearUsr.html.twig',$layout);
    }
    
    public function nuevaSesion($usuario){
        // Se crea una nueva sesion y el usuario, caso de poseer mas de un rol, elige con cual entrar.
        //
        
        if(!isset($_SESSION['id_usuario'])){
            session_start();
            $_SESSION['id_usuario'] = $usuario;
        }
        //
        $roles = UserModel::getInstance()->roles($usuario);
        if(count($roles) > 1){
            $parametros['roles'] = $roles;
            $parametros['id'] = $usuario;
            $this->eleccionRolIngreso($parametros);
        }else{
            $this->iniciarSesionComoRol($roles[0]['nombre'],$roles[0]['id'],$usuario,false);
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
                IndexController::getInstance()->index();
                return;
            }
        }
        if($this->chequeoAccesoUsuarioActivo($usuario_id)){
            if($eligio_rol){session_start();}
            if(isset($_SESSION['id_usuario'])){ //Este if-else esta hecho en el caso de querer acceder directamente por url, se chequea que se haya creado la sesion
                $_SESSION['rolId'] = $rol_id;
                $_SESSION['rolNombre'] = $rol;
                $layout = IndexController::getInstance()->layout();
                $layout['rol_nombre'] = $rol;
                Home::getInstance()->show('paginaPrincipal.html.twig',$layout);
                return;
            }
            IndexController::getInstance()->index();  
            }           
    }
    
    public function cerrarSesion(){
        //Cierra sesion y accede al index
        if(!isset($_SESSION)){session_start();}
        session_destroy();
        if(isset($_SESSION['id_usuario'])){
            session_unset();
        }
    }
    public function chequeoAccesoUsuarioActivo($id){
        //Chequea si el usuario con id $id esta activo, de ser asi retornara true, caso contrario accede a 
        // otra pagina con un mensaje informando la situacion.
        
        if(UserModel::getInstance()->estaActivo($id)){
            return true;
        }else{
            $this->cerrarSesion();
            $layout = IndexController::getInstance()->layout();
            $layout['sitioInhabilitado'] = 'El admisnitrador ha desactivado tu cuenta';
            Home::getInstance()->show('index.html.twig',$layout);
            return false;
        }
    }
    
    public function seccionUsuarios(){
        //Accede a la seccion de usuarios.
        if($this->sePuedeAccederASeccion()){
            $parametros['mensaje'] = 'Bienvenido a la seccion de usuarios.';
            $this->accesoAPaginaUsuarios($parametros);
        }
    }
    
    public function sePuedeAccederASeccion(){
        //Chequea la posibilidad de acceder a una seccion.
        if(!isset($_SESSION['id_usuario'])){session_start();}
        if(!isset($_SESSION['id_usuario'])){
            IndexController::getInstance()->index();
            return false;
        }
        if(!AppController::getInstance()->comprobarPaginaActiva()){
            if(!AppController::getInstance()->checkPermission('configuracion')){
                $this->cerrarSesion();
                IndexController::getInstance()->index();
                return false;
            }
        }elseif(!$this->chequeoAccesoUsuarioActivo($_SESSION['id_usuario'])){
            return false;
        }
        return true;
    }
    
    public function listadoCompletoUsuarios(){
        //Retorna el listado de usuarios.
        if($this->sePuedeAccederASeccion()){
                $id_usuario = $_SESSION['id_usuario'];
                $listado = UserModel::getInstance()->listadoUsuarios($id_usuario);
                if(count($listado) == 0){
                    $parametros['mensaje'] = 'No hay usuarios registrados.';
                    $this->accesoAPaginaUsuarios($parametros);
                }else{
                    $parametros['listaUsuarios'] = $listado;
                    $parametros['mensaje'] = 'Listado de usuarios.';
                    $this->accesoAPaginaUsuarios($parametros);
                }
    }
}
    
    public function accesoAPaginaUsuarios($arreglo){
        //Muestra la pagina con el listado de usuarios
        $layout = IndexController::getInstance()->layout();
        $layout['titulo'] = $arreglo['mensaje'];
        $layout['rol_nombre'] = $_SESSION['rolNombre'];
        if(isset($arreglo['listaUsuarios'])){
            $layout['listaUsuarios'] = $arreglo['listaUsuarios'];
            $layout['elemPorPagina'] = ConfigurationModule::getInstance()->elementosPorPagina();
        }elseif(isset($arreglo['tuUsuario'])){
            $layout['tuUsuario'] = $arreglo['tuUsuario'];
        }
        Home::getInstance()->show('seccionUsuarios.html.twig',$layout);
    }
    
    public function modificarEstado($id,$estado){
        //Cambia el estado del usuario pasado por parametro (activo/bloqueado)
        if($this->sePuedeAccederASeccion()){
            UserModel::getInstance()->actualizarEstado($id,!$estado);
            $this->listadoCompletoUsuarios();
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function eliminarUsuario($id){
        //Elimina el usuario pasado por parametro
        if($this->sePuedeAccederASeccion()){
            UserModel::getInstance()->eliminarUsuario($id);
            $this->listadoCompletoUsuarios();
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function buscarPorNombreUsuario($nombreUsuario){
        //Busqueda de usuarios por nombre de usuario
        if($this->sePuedeAccederASeccion()){
            $usuarios = UserModel::getInstance()->buscarNombreUsuario($nombreUsuario,$_SESSION['id_usuario']);
            if(count($usuarios) > 0){
                $parametros['mensaje'] = 'Usuarios con nombre de usuario contiene: '.$nombreUsuario;
                $parametros['listaUsuarios'] = $usuarios;
            }else{
                $parametros['mensaje'] = 'No hay usuarios cuyo nombre de usuario contengan: '.$nombreUsuario;
            }
            $this->accesoAPaginaUsuarios($parametros);
        }
    }
    
    public function buscarPorEstado($estado){
        //Busqueda de usuarios por estado.
        if($this->sePuedeAccederASeccion()){
            $usuarios = UserModel::getInstance()->buscarPorEstado($estado,$_SESSION['id_usuario']);
            if(count($usuarios) > 0){
                if($estado){
                    $parametros['mensaje'] = 'Usuarios activos.';
                }else{
                    $parametros['mensaje'] = 'Usuarios bloqueados.';
                }
                $parametros['listaUsuarios'] = $usuarios;
            }else{
                if($estado){
                    $parametros['mensaje'] = 'No hay usuarios activos.';
                }else{
                    $parametros['mensaje'] = 'No hay usuarios bloqueados.';
                }
            }
            $this->accesoAPaginaUsuarios($parametros);
        }
    }
    
    public function verMiUsuario(){
        if($this->sePuedeAccederASeccion()){
            $usuario = UserModel::getInstance()->obtenerDatos($_SESSION['id_usuario']);
            $arreglo = array(
                'mensaje' => 'Tu usuario',
                'tuUsuario' => $usuario
            );
            $this->accesoAPaginaUsuarios($arreglo);
        }
    }
}