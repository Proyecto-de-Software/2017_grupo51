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
        //Retorna todos los pacientes
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente", []);
        return $answer;
    }
    
    public function obtenerDatosCompletos($idPaciente){
        //Retorna los datos completos del paciente pasado por parametro
        $answer = $this->queryList("SELECT * FROM paciente WHERE id=:id_paciente", ['id_paciente'=>$idPaciente]);
        return $answer;
    }
    
    public function eliminarPaciente($idPaciente){
        //Elimina el paciente con id pasado por parametro
        $this->queryList("DELETE FROM paciente WHERE id=:id_paciente", ['id_paciente'=>$idPaciente]);
    }
    
    public function buscarNombrePaciente($nombrePaciente){
        //Busca paciente por nombre
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente WHERE nombre LIKE :nombrePac", ['nombrePac'=>'%'.$nombrePaciente.'%']);
        return $answer;
    }
    
    public function buscarApellidoPaciente($apellidoPaciente){
        //Busca paciente por apellido
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente WHERE apellido LIKE :apellidoPac", ['apellidoPac'=>'%'.$apellidoPaciente.'%']);
        return $answer;
    }
    
    public function buscarDocumentoPaciente($numero,$doc){
        //Busca paciente por tipo y numero de documento
        $answer = $this->queryList("SELECT id,apellido,nombre,fecha_nacimiento,tipo_documento,numero_documento FROM paciente WHERE tipo_documento=:tipoDoc AND numero_documento LIKE :numeroDoc", ['tipoDoc'=>$doc,'numeroDoc'=>'%'.$numero.'%']);
        return $answer;
    }
    
    public function obtenerIdPacienteConDoc($tipoDoc,$nroDoc){
        //Obtiene el id del paciente con el tipo y numero de documento pasados por parametro.
        $answer = $this->queryList("SELECT id FROM paciente WHERE tipo_documento=:tipoDoc AND numero_documento=:nroDoc", ['tipoDoc'=>$tipoDoc , 'nroDoc'=>$nroDoc]);
        return $answer[0]['id'];
    }
    
    public function insertarPaciente($arregloDatosPac){
        //Inserta un nuevo paciente
        $this->queryList("INSERT INTO paciente (apellido, nombre, fecha_nacimiento, genero, tipo_documento, numero_documento, domicilio, tel_cel, obra_social, heladera, electricidad, tipo_vivienda, mascota, tipo_calefaccion, tipo_agua) VALUES (:apellidoPac, :nombrePac, :fecha_nacimientoPac, :generoPac, :tipo_documentoPac, :numero_documentoPac, :domicilioPac, :tel_celPac, :obra_socialPac, :heladPac, :elecPac, :viviPac, :mascotaPac, :calePac, :aguaPac)",['apellidoPac'=>$arregloDatosPac[0], 'nombrePac'=>$arregloDatosPac[1], 'fecha_nacimientoPac'=>$arregloDatosPac[2], 'generoPac'=>$arregloDatosPac[3], 'tipo_documentoPac'=>$arregloDatosPac[4], 'numero_documentoPac'=>$arregloDatosPac[5], 'domicilioPac'=>$arregloDatosPac[6], 'tel_celPac'=>$arregloDatosPac[7], 'obra_socialPac'=>$arregloDatosPac[8], 'heladPac'=>$arregloDatosPac[9], 'elecPac'=>$arregloDatosPac[10], 'viviPac'=>$arregloDatosPac[12], 'mascotaPac'=>$arregloDatosPac[11], 'calePac'=>$arregloDatosPac[13], 'aguaPac'=>$arregloDatosPac[14] ]);    
    }
    
    public function actualizarPaciente($arregloDatosPac,$idpaciente){
        //Actualiza el paciente pasado por parametro
        $this->queryList("UPDATE paciente SET apellido=:apellidoPac, nombre=:nombrePac, fecha_nacimiento=:fecha_nacimientoPac, genero=:generoPac, tipo_documento=:tipo_documentoPac, numero_documento=:numero_documentoPac, domicilio=:domicilioPac, tel_cel=:tel_celPac, obra_social=:obra_socialPac, heladera=:heladPac, electricidad=:elecPac, tipo_vivienda=:viviPac, mascota=:mascotaPac, tipo_calefaccion=:calePac, tipo_agua=:aguaPac WHERE id=:idPaciente",['apellidoPac'=>$arregloDatosPac[0], 'nombrePac'=>$arregloDatosPac[1], 'fecha_nacimientoPac'=>$arregloDatosPac[2], 'generoPac'=>$arregloDatosPac[3], 'tipo_documentoPac'=>$arregloDatosPac[4], 'numero_documentoPac'=>$arregloDatosPac[5], 'domicilioPac'=>$arregloDatosPac[6], 'tel_celPac'=>$arregloDatosPac[7], 'obra_socialPac'=>$arregloDatosPac[8], 'heladPac'=>$arregloDatosPac[9], 'elecPac'=>$arregloDatosPac[10], 'viviPac'=>$arregloDatosPac[12], 'mascotaPac'=>$arregloDatosPac[11], 'calePac'=>$arregloDatosPac[13], 'aguaPac'=>$arregloDatosPac[14], 'idPaciente'=>$idpaciente ]);
    }
    
    public function existePaciente($nroDoc){
        //Retorna, en caso de existir, el paciente con documento pasado por parametro
        $answer = $this->queryList("SELECT id FROM paciente WHERE numero_documento=:nroDoc", ['nroDoc'=>$nroDoc]);
        return $answer;
    }
}