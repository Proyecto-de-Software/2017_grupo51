<?php

class RolesModel extends PDORepository{
    
    private static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();   
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public function obtenerRol($rolId){
        $answer = $this->queryList("SELECT nombre FROM rol WHERE id=:rolId", ['rolId'=>$rolId]);
        return $answer;
    }
    
    public function asignarRol($usuarioId,$rolId){
        $this->queryList("INSERT INTO usuario_tiene_rol (usuario_id,rol_id) VALUES (:idUsuario,:idRol)", ['idRol'=>$rolId,'idUsuario'=>$usuarioId]);
    }
    
    public function desasignarRol($usuarioId,$rolId){
        $this->queryList("DELETE FROM usuario_tiene_rol WHERE usuario_id=:idUsuario AND rol_id=:idRol", ['idRol'=>$rolId,'idUsuario'=>$usuarioId]);
    }
    
    public function roles($id){
        //Retorna los roles asignados al usuario pasado por parametro
        $answer = $this->queryList("SELECT r.id,r.nombre FROM rol r INNER JOIN usuario_tiene_rol ur ON (r.id=ur.rol_id) INNER JOIN usuario u ON (ur.usuario_id=u.id) WHERE ur.usuario_id=:id", ['id'=>$id]);
        return $answer;
    }
    
    public function listadoDeRoles(){
        $answer = $this->queryList("SELECT * FROM rol", []);
        return $answer;
    }
}