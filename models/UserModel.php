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
        $answerRoles = RolesModel::getInstance()->roles($id_usuario);
        $finalAnswer = array(
            'usuario' => $answerUsuario,
            'rolesUsuario' => $answerRoles,
        );
        return $finalAnswer;
    }
    
    public function insertarUsuario($arregloDatos){
        $result = $this->queryList("INSERT INTO usuario (email, username, password, active, first_name, last_name) VALUES (:email_us, :username_us, :password_us, :numero, :first_name_us, :last_name_us)",['email_us'=>$arregloDatos[0], 'username_us'=>$arregloDatos[1], 'password_us'=>$arregloDatos[2], 'first_name_us'=>$arregloDatos[3],'numero'=> 1, 'last_name_us'=>$arregloDatos[4] ]);

    }

    public function insertarPaciente($arregloDatosPac){

        $resultado = $this->queryList("INSERT INTO paciente (apellido, nombre, fecha_nacimiento, genero, tipo_documento, numero_documento, domicilo, tel_cel, obra_social) VALUES (:apellidoPac, :nombrePac, :fecha_nacimientoPac, :generoPac, :tipo_documentoPac, :numero_documentoPac, :domiciloPac, :tel_celPac, :obra_socialPac)",['apellidoPac'=>$arregloDatosPac[0], 'nombrePac'=>$arregloDatosPac[1], 'fecha_nacimientoPac'=>$arregloDatosPac[2], 'generoPac'=>$arregloDatosPac[3], 'tipo_documentoPac'=>$arregloDatosPac[4], 'numero_documentoPac'=>$arregloDatosPac[5], 'domiciloPac'=>$arregloDatosPac[6], 'tel_celPac'=>$arregloDatosPac[7], 'obra_socialPac'=>$arregloDatosPac[8] ]);

    }
    
    public function usuariosConRoles(){
        $answer = $this->queryList("SELECT * FROM usuario u INNER JOIN usuario_tiene_rol ur ON(u.id=ur.usuario_id) INNER JOIN rol r ON(r.id=ur.rol_id)", []);
        return $answer;
    }
    
    
    public function usuarioPoseeRol($usuarioId,$rolId){
        $answer = $this->queryList("SELECT * FROM usuario_tiene_rol ur WHERE ur.rol_id=:rolId AND ur.usuario_id=:usuarioId", ['rolId'=>$rolId,'usuarioId'=>$usuarioId]);
        if(count($answer) == 0){
            return false;
        }
        return true;
    }
}