<?php

class UserModel extends PDORepository{
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function existeUsuario($mail,$contraseña){
        //Busca en la bd el usuario con mail y contraseña ingresado
        $answer = $this->queryList("SELECT id FROM usuario WHERE email=:mail AND password=:contra", ['mail'=>$mail,'contra'=>$contraseña]);
        return $answer;
    }
    
    public function chequearRol($id,$rol){
        //Busca si el usuario pasado por parametro posee un rol (por ej, Administrador)
        $answer = $this->queryList("SELECT * FROM usuario_tiene_rol ur INNER JOIN rol r ON(ur.rol_id=r.id) WHERE r.nombre=:rol AND ur.usuario_id=:id", ['rol'=>$rol,'id'=>$id]);
        return count($answer);
    }
    
    public function estaActivo($id){
        //Retorna el valor 'active' de un usuario
        $answer = $this->queryList("SELECT active FROM usuario WHERE id=:id_usr", ['id_usr'=>$id]);
        return $answer[0]['active'];
    }
    
    public function listadoUsuarios($id){
        //Retorna el listado de usuario sin el usuario pasado por parametro
        $answer = $this->queryList("SELECT id,email,username,active,updated_at,created_at,first_name,last_name FROM usuario WHERE id!=:id_mio", ['id_mio'=>$id]);
        return $answer;
    }
    
    public function actualizarEstado($id,$estado){
        //Actualiza el estado de un usuario
        $this->queryList("UPDATE usuario SET active=:estado WHERE id=:id_usuario", ['id_usuario'=>$id,'estado'=>$estado]);
    }
    
    public function eliminarUsuario($id){
        //Elimina el usuario pasado por parametro
        $this->queryList("DELETE FROM usuario WHERE id=:id_usuario", ['id_usuario'=>$id]);
    }
    
    public function buscarNombreUsuario($nombreUsuario,$id){
        //Busca usuarios por nombre
        $answer = $this->queryList("SELECT id,email,username,active,updated_at,created_at,first_name,last_name FROM usuario WHERE username LIKE :nombre AND id!=:miId", ['nombre'=>'%'.$nombreUsuario.'%','miId'=>$id]);
        return $answer;
    }
    
    public function buscarPorEstado($estado,$id){
        //Busca usuarios por estado (activo/bloqueado)
        $answer = $this->queryList("SELECT id,email,username,active,updated_at,created_at,first_name,last_name FROM usuario WHERE active=:estado AND id!=:miId", ['estado'=>$estado,'miId'=>$id]);
        return $answer;
    }
    
    public function obtenerUsuario($id_usuario){
        //Retorna los datos de un usuario
        $answer = $this->queryList("SELECT * FROM usuario WHERE id=:id_usuario", ['id_usuario'=>$id_usuario]);
        return $answer;
    }
    
    public function obtenerDatos($id_usuario){
        //Retorna un usuario junto a sus roles
        $answerUsuario = $this->obtenerUsuario($id_usuario);
        $answerRoles = RolesModel::getInstance()->roles($id_usuario);
        $finalAnswer = array(
            'usuario' => $answerUsuario,
            'rolesUsuario' => $answerRoles,
        );
        return $finalAnswer;
    }
    
    public function insertarUsuario($arregloDatos){
        //Inserta un nuevo usuario
        $this->queryList("INSERT INTO usuario (email, username, password, active, first_name, last_name) VALUES (:email_us, :username_us, :password_us, :numero, :first_name_us, :last_name_us)",['email_us'=>$arregloDatos[0], 'username_us'=>$arregloDatos[1], 'password_us'=>$arregloDatos[2], 'first_name_us'=>$arregloDatos[3],'numero'=> 1, 'last_name_us'=>$arregloDatos[4] ]);

    }


    public function insertarRolesUsuario($usr_id, $rol_id){
        //Inserta un nuevo rol a un usuario dado
        $this->queryList ("INSERT INTO usuario_tiene_rol (usuario_id, rol_id) VALUES (:usrid, :rolid)", ['usrid'=>$usr_id, 'rolid'=>$rol_id] );
    }
    
    public function usuariosConRoles(){
        //Retorna los usuarios junto a sus roles asignados
        $answer = $this->queryList("SELECT * FROM usuario u INNER JOIN usuario_tiene_rol ur ON(u.id=ur.usuario_id) INNER JOIN rol r ON(r.id=ur.rol_id)", []);
        return $answer;
    }
    
    
    public function usuarioPoseeRol($usuarioId,$rolId){
        //Retorna si el usuario posee o no un rol dado.
        $answer = $this->queryList("SELECT * FROM usuario_tiene_rol ur WHERE ur.rol_id=:rolId AND ur.usuario_id=:usuarioId", ['rolId'=>$rolId,'usuarioId'=>$usuarioId]);
        if(count($answer) == 0){
            return false;
        }
        return true;
    }
    
    public function obtenerIdUsuario($email){
        //Obtiene el id del usuario con mail pasado por parametro
        $answer = $this->queryList("SELECT id FROM usuario WHERE email=:mail", ['mail'=>$email]);
        return $answer[0]['id'];
    }
    
    public function actualizaUsuario($idusuario,$arregloDatos){
        //Actualiza un usuario
        $this->queryList("UPDATE usuario SET email=:email_us, username=:username_us, password=:password_us, active=:numero, first_name=:first_name_us, last_name=:last_name_us, updated_at=:fecha WHERE id=:idUsuario",['idUsuario'=>$idusuario,'email_us'=>$arregloDatos[0], 'username_us'=>$arregloDatos[1], 'password_us'=>$arregloDatos[2], 'first_name_us'=>$arregloDatos[3],'numero'=> 1, 'last_name_us'=>$arregloDatos[4], 'fecha' => $arregloDatos[5]]);
    }
    
    function usuario_existente ($email,$nombreUsr){
        //Retorna, en caso de existir, un usario
        $answer = $this->queryList("SELECT id FROM usuario WHERE email=:email OR username=:nombreUsr", ['email'=>$email,'nombreUsr'=>$nombreUsr]);
        return $answer;
    }
}
