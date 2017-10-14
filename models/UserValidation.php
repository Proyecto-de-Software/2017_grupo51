<?php

class UserValidation extends PDORepository{
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function existeUsuario($mail,$contraseña){
        $answer = $this->queryList("SELECT id FROM usuario WHERE email=:mail AND password=:contra", ['mail'=>$mail,'contra'=>$contraseña]);
        return $answer;
    }
    
    public function chequearRol($id,$rol){
        $answer = $this->queryList("SELECT * FROM usuario_tiene_rol ur INNER JOIN rol r ON(ur.rol_id=r.id) WHERE r.nombre=:rol AND ur.usuario_id=:id", ['rol'=>$rol,'id'=>$id]);
        return count($answer);
    }
    
    public function roles($id){
        $answer = $this->queryList("SELECT r.id,r.nombre FROM rol r INNER JOIN usuario_tiene_rol ur ON (r.id=ur.rol_id) INNER JOIN usuario u ON (ur.usuario_id=u.id) WHERE ur.usuario_id=:id", ['id'=>$id]);
        return $answer;
    }
    
    public function estaActivo($id){
        $answer = $this->queryList("SELECT active FROM usuario WHERE id=:id_usr", ['id_usr'=>$id]);
        return $answer[0]['active'];
    }
    public function insertarUsuario($arregloDatos){
        $result = $this->queryList("INSERT INTO usuario (email, username, password, active, first_name, last_name) VALUES (:email_us, :username_us, :password_us, :numero, :first_name_us, :last_name_us)",['email_us'=>$arregloDatos[0], 'username_us'=>$arregloDatos[1], 'password_us'=>$arregloDatos[2], 'first_name_us'=>$arregloDatos[3],'numero'=> 1, 'last_name_us'=>$arregloDatos[4] ]);

    }

    public function insertarPaciente($arregloDatosPac){

        $resultado = $this->queryList("INSERT INTO paciente (apellido, nombre, fecha_nacimiento, genero, tipo_documento, numero_documento, domicilo, tel_cel, obra_social) VALUES (:apellidoPac, :nombrePac, :fecha_nacimientoPac, :generoPac, :tipo_documentoPac, :numero_documentoPac, :domiciloPac, :tel_celPac, :obra_socialPac)",['apellidoPac'=>$arregloDatosPac[0], 'nombrePac'=>$arregloDatosPac[1], 'fecha_nacimientoPac'=>$arregloDatosPac[2], 'generoPac'=>$arregloDatosPac[3], 'tipo_documentoPac'=>$arregloDatosPac[4], 'numero_documentoPac'=>$arregloDatosPac[5], 'domiciloPac'=>$arregloDatosPac[6], 'tel_celPac'=>$arregloDatosPac[7], 'obra_socialPac'=>$arregloDatosPac[8] ]);

    }
}