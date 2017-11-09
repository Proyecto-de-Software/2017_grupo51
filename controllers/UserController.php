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
        if($this->sePuedeAccederASeccion('usuario_new')){
            $parametros['roles'] = RolesModel::getInstance()->listadoDeRoles();
            $this->formularioUsuarios($parametros);
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function formularioUsuarios($arreglo){
        //Carga el formulario de usuario
        $layout = IndexController::getInstance()->layout();
        if(isset($arreglo['usuario'])){
            $layout['usuario'] = $arreglo['usuario'];
            $layout['titulo'] = 'Actualizar informacion.';
        }else{
            $layout['roles'] = $arreglo['roles'];
            $layout['titulo'] = 'Ingrese datos del usuario nuevo.';
        }
        Home::getInstance()->show('crearUsr.html.twig',$layout);
    }
    
    public function modificarUsuario(){
        //Obtiene los datos del usuario para modificar y carga el formulario de usuario
        if($this->sePuedeAccederASeccion('usuario_update')){
            $parametros['usuario'] = UserModel::getInstance()->obtenerUsuario($_SESSION['id_usuario']);
            $this->formularioUsuarios($parametros);
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function actualizarUsuario(){
        if($this->sePuedeAccederASeccion('usuario_update')){
            $_POST['check'] = [0];
            if($this->validarDatos()){
                $arreglo = array("0"=> $_POST["emailUs"], "1"=> $_POST["nombreUs"],"2"=> $_POST["contraUs"],"3"=> $_POST["nombreRealUs"] ,"4"=> $_POST["ApellidoUs"] , "5" => date("Y-m-d H:i:s"));
                UserModel::getInstance()->actualizaUsuario($_SESSION['id_usuario'],$arreglo);
                $this->verMiUsuario();
            }
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function nuevaSesion($usuario){
        // Se crea una nueva sesion y el usuario, caso de poseer mas de un rol, elige con cual entrar.
        //
        
        if(!isset($_SESSION['id_usuario'])){
            session_start();
            $_SESSION['id_usuario'] = $usuario;
        }
        //
        $roles = RolesModel::getInstance()->roles($usuario);
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
        if($this->sePuedeAccederASeccion('usuario_index')){
            $this->paginaPrincipalUsuarios('listar_usuarios');
        }elseif($this->sePuedeAccederASeccion('usuario_show')){
            $this->paginaPrincipalUsuarios('mi_usuario');
        }else{
            $this->paginaPrincipalUsuarios('Bienvenido a la seccion de usuarios.');
        }
    }
    
    public function paginaPrincipalUsuarios($mensaje){
        //Accede a la seccion de usuarios.
        if($this->sePuedeAccederASeccion('usuario_seccion')){
            if($mensaje == 'listar_usuarios'){
                $this->listadoCompletoUsuarios();
            }else if($mensaje == 'mi_usuario'){
                $this->verMiUsuario();
            }else{
                $parametros['mensaje'] = $mensaje;
                $this->accesoAPaginaUsuarios($parametros);
            }
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function sePuedeAccederASeccion($permission){
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
        if(!AppController::getInstance()->checkPermission($permission)){
            return false;
        }
        return true;
    }
    
    public function listadoCompletoUsuarios(){
        //Retorna el listado de usuarios.
        if($this->sePuedeAccederASeccion('usuario_index')){
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
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
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
        if($this->sePuedeAccederASeccion('configuracion')){
            UserModel::getInstance()->actualizarEstado($id,!$estado);
            $this->listadoCompletoUsuarios();
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function eliminarUsuario($id){
        //Elimina el usuario pasado por parametro
        if($this->sePuedeAccederASeccion('usuario_destroy')){
            UserModel::getInstance()->eliminarUsuario($id);
            $this->listadoCompletoUsuarios();
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function buscarPorNombreUsuario($nombreUsuario){
        //Busqueda de usuarios por nombre de usuario
        if($this->sePuedeAccederASeccion('usuario_index')){
            $usuarios = UserModel::getInstance()->buscarNombreUsuario($nombreUsuario,$_SESSION['id_usuario']);
            if(count($usuarios) > 0){
                $parametros['mensaje'] = 'Usuarios con nombre de usuario contiene: '.$nombreUsuario;
                $parametros['listaUsuarios'] = $usuarios;
            }else{
                $parametros['mensaje'] = 'No hay usuarios cuyo nombre de usuario contengan: '.$nombreUsuario;
            }
            $this->accesoAPaginaUsuarios($parametros);
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function buscarPorEstado($estado){
        //Busqueda de usuarios por estado.
        if($this->sePuedeAccederASeccion('usuario_index')){
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
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function verMiUsuario(){
        //Muestra el perfil completo de el usuario logeado
        if($this->sePuedeAccederASeccion('usuario_show')){
            $usuario = UserModel::getInstance()->obtenerDatos($_SESSION['id_usuario']);
            $arreglo = array(
                'mensaje' => 'Tu usuario',
                'tuUsuario' => $usuario
            );
            $this->accesoAPaginaUsuarios($arreglo);
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }

    public function ya_existe_usuario($mail,$nameUsr){
        //valida contra base de datos
        $aux = UserModel::getInstance()->usuario_existente($mail, $nameUsr);
        if (count($aux) == 0){
            return true;
        }else {
            return false;
        }
    }

    public function crearTabla(){
        //Crea un usuario nuevo en la bd
        if($this->sePuedeAccederASeccion('usuario_new')){
            if($this->validarDatos()){
                $arreglo = array("0"=> $_POST["emailUs"], "1"=> $_POST["nombreUs"],"2"=> $_POST["contraUs"],"3"=> $_POST["nombreRealUs"] ,"4"=> $_POST["ApellidoUs"]);
                UserModel::getInstance()->insertarUsuario($arreglo);
                $idUsuarioRegistrado = UserModel::getInstance()->obtenerIdUsuario($_POST['emailUs']);
                foreach ($_POST['check'] as $value) {
                    UserModel::getInstance()->insertarRolesUsuario($idUsuarioRegistrado, $value);
                    }
                $this->paginaPrincipalUsuarios('Usuario creado exitosamente.');
            }
        }else{
            $this->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function validarDatos(){
        //Valida los datos de usuario ingresados
        $retorno = true;
        if(isset($_POST["emailUs"]) && isset($_POST["nombreUs"]) && isset($_POST["contraUs"]) && isset($_POST["nombreRealUs"]) && isset($_POST["ApellidoUs"]) && isset($_POST['check'])){
            if(filter_var($_POST["emailUs"],FILTER_VALIDATE_EMAIL) && is_string($_POST["nombreUs"]) && is_string($_POST["contraUs"]) && is_string($_POST["nombreRealUs"]) && is_string($_POST["ApellidoUs"]) && is_array($_POST['check'])){
                foreach ($_POST['check'] as $value) {
                    if(!is_numeric($value)){
                        $retorno = false;
                        break;
                    }
                }
            }else{
                $retorno = false;
            }
        }else{
            $retorno = false;
        }
        return $retorno;
    }


    public function existeUsuarioConId($usuarioId){
        //Retorna si existe usuario con el id pasado por parametro.
        $existe = UserModel::getInstance()->obtenerUsuario($usuarioId);
        if(count($existe) == 0){
            return false;
        }else{
            return true;
        }
    }
}
