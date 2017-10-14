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
    
    public function roles($id){
        //Retorna los roles asignados al usuario pasado por parametro
        $answer = $this->queryList("SELECT r.id,r.nombre FROM rol r INNER JOIN usuario_tiene_rol ur ON (r.id=ur.rol_id) INNER JOIN usuario u ON (ur.usuario_id=u.id) WHERE ur.usuario_id=:id", ['id'=>$id]);
        return $answer;
    }
    
    public function estaActivo($id){
        //Retorna el valor 'active' de un usuario
        $answer = $this->queryList("SELECT active FROM usuario WHERE id=:id_usr", ['id_usr'=>$id]);
        return $answer[0]['active'];
    }
    
    public function listadoUsuarios($id){
        $answer = $this->queryList("SELECT id,email,username,active,updated_at,created_at,first_name,last_name FROM usuario WHERE id!=:id_mio", ['id_mio'=>$id]);
        return $answer;
    }
    
    public function actualizarEstado($id,$estado){
        $this->queryList("UPDATE usuario SET active=:estado WHERE id=:id_usuario", ['id_usuario'=>$id,'estado'=>$estado]);
    }
    
    public function eliminarUsuario($id){
        $this->queryList("DELETE FROM usuario WHERE id=:id_usuario", ['id_usuario'=>$id]);
    }
    
    public function buscarNombreUsuario($nombreUsuario,$id){
        $answer = $this->queryList("SELECT id,email,username,active,updated_at,created_at,first_name,last_name FROM usuario WHERE username LIKE :nombre AND id!=:miId", ['nombre'=>'%'.$nombreUsuario.'%','miId'=>$id]);
        return $answer;
    }
    
    public function buscarPorEstado($estado,$id){
        $answer = $this->queryList("SELECT id,email,username,active,updated_at,created_at,first_name,last_name FROM usuario WHERE active=:estado AND id!=:miId", ['estado'=>$estado,'miId'=>$id]);
        return $answer;
    }
    
    public function obtenerUsuario($id_usuario){
        $answer = $this->queryList("SELECT email,password,username,active,updated_at,created_at,first_name,last_name FROM usuario WHERE id=:id_usuario", ['id_usuario'=>$id_usuario]);
        return $answer;
    }
    
    public function obtenerDatos($id_usuario){
        $answerUsuario = $this->obtenerUsuario($id_usuario);
        $answerRoles = $this->roles($id_usuario);
        $finalAnswer = array(
            'usuario' => $answerUsuario,
            'rolesUsuario' => $answerRoles,
        );
        return $finalAnswer;
    }
}