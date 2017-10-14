<?php

class PacienteModel extends PDORepository{
    
    private static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();   
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public function obtenerPacientes(){
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente", []);
        return $answer;
    }
    
    public function obtenerDatosCompletos($idPaciente){
        $answer = $this->queryList("SELECT * FROM paciente WHERE id=:id_paciente", ['id_paciente'=>$idPaciente]);
        return $answer;
    }
    
    public function eliminarPaciente($idPaciente){
        $this->queryList("DELETE FROM paciente WHERE id=:id_paciente", ['id_paciente'=>$idPaciente]);
    }
    
    public function buscarNombrePaciente($nombrePaciente){
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente WHERE nombre LIKE :nombrePac", ['nombrePac'=>'%'.$nombrePaciente.'%']);
        return $answer;
    }
    
    public function buscarApellidoPaciente($apellidoPaciente){
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente WHERE apellido LIKE :apellidoPac", ['apellidoPac'=>'%'.$apellidoPaciente.'%']);
        return $answer;
    }
    
    public function buscarDocumentoPaciente($numero,$doc){
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente WHERE tipo_documento=:tipoDoc AND numero_documento LIKE :numeroDoc", ['tipoDoc'=>$doc,'numeroDoc'=>'%'.$numero.'%']);
        return $answer;
    }
}