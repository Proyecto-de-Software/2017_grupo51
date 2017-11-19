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
    
    public function fechaNacimiento($idPaciente){
        //Retorna la fecha de nacimiento del paciente
        $answer = $this->queryList("SELECT fecha_nacimiento FROM paciente WHERE id=:idPac", ['idPac' => $idPaciente]);
        return $answer[0]['fecha_nacimiento'];
    }

    public function obtenerControles($idPaciente){
        //Retorna los controles de un paciente
        $answer = $this->queryList("SELECT fecha,id FROM control_salud WHERE id_paciente=:paciente", ['paciente' => $idPaciente]);
        return $answer;
    }
    
    public function obtenerControlCompleto($idControl){
        //Retorna el detalle de un control
        $answer = $this->queryList("SELECT cs.id,cs.fecha,cs.peso,cs.vacunas_completas,cs.vacunas_observaciones,cs.maduracion_acorde,cs.maduracion_observaciones,cs.examen_fisico_normal,cs.examen_fisico_observaciones,cs.percentilo_cefalico,cs.percentilo_perimetro_cefalico,cs.talla,cs.alimentacion, cs.observaciones_generales,p.nombre,p.apellido,u.first_name,u.last_name FROM control_salud cs INNER JOIN usuario u ON(cs.id_usuario_registro=u.id) INNER JOIN paciente p ON(p.id=cs.id_paciente) WHERE cs.id=:control", ['control' => $idControl]);
        return $answer;
    }
    
    public function eliminarControl($idControl){
        //Elimina un control dado
        $this->queryList("DELETE FROM control_salud WHERE id=:control", ['control' => $idControl]);
    }
    
    public function datosMascotas(){
        //Retorna los datos de mascotas de todos los pacientes
        $answer = $this->queryList("SELECT mascota FROM paciente",[]);
        return $answer;
    }
    
    public function datosElectricidad(){
        //Retorna los datos de electricidad de todos los pacientes
        $answer = $this->queryList("SELECT electricidad FROM paciente",[]);
        return $answer;
    }
    
    public function datosHeladera(){
        //Retorna los datos de heladera de todos los pacientes
        $answer = $this->queryList("SELECT heladera FROM paciente",[]);
        return $answer;
    }
    
    public function agua(){
        //Retorna los datos de tipo de agua de todos los pacientes
        $answer = $this->queryList("SELECT tipo_agua FROM paciente",[]);
        return $answer;
    }
    
    public function viviendas(){
        //Retorna los datos de tipo de vivienda de todos los pacientes
        $answer = $this->queryList("SELECT tipo_vivienda FROM paciente",[]);
        return $answer;
    }
    
    public function calefaccion(){
        //Retorna los datos de tipo de calefaccion de todos los pacientes
        $answer = $this->queryList("SELECT tipo_calefaccion FROM paciente",[]);
        return $answer;
    }
    
    public function obtenerPeso($idPaciente,$fechaNacimiento,$nuevafecha){
        //Retorna los pesos registrados de un paciente
        $answer = $this->queryList("SELECT fecha,peso FROM control_salud WHERE id_paciente=:paciente AND fecha BETWEEN :primerFecha AND :segundaFecha", ['paciente' => $idPaciente,'primerFecha' => $fechaNacimiento,'segundaFecha' => $nuevafecha]);
        return $answer;
    }
    
    public function obtenerTalla($idPaciente,$fechaNacimiento,$nuevafecha){
        //Retorna las tallas registradas de un paciente
        $answer = $this->queryList("SELECT fecha,talla FROM control_salud WHERE talla IS NOT NULL AND id_paciente=:paciente AND fecha BETWEEN :primerFecha AND :segundaFecha", ['paciente' => $idPaciente,'primerFecha' => $fechaNacimiento,'segundaFecha' => $nuevafecha]);
        return $answer;
    }
    
    public function obtenerPercentilos($idPaciente,$fechaNacimiento,$nuevafecha){
        //Retorna los percentilos registrados de un paciente
        $answer = $this->queryList("SELECT fecha,percentilo_perimetro_cefalico FROM control_salud WHERE percentilo_perimetro_cefalico IS NOT NULL AND id_paciente=:paciente AND fecha BETWEEN :primerFecha AND :segundaFecha", ['paciente' => $idPaciente,'primerFecha' => $fechaNacimiento,'segundaFecha' => $nuevafecha]);
        return $answer;
    }
    
    public function insertarControlPac($Arreglo_datos){
        $this->queryList("INSERT INTO control_salud (fecha, peso, vacunas_completas, vacunas_observaciones, maduracion_acorde, maduracion_observaciones, examen_fisico_normal, examen_fisico_observaciones, percentilo_cefalico, percentilo_perimetro_cefalico, talla, alimentacion, observaciones_generales, id_paciente, id_usuario_registro) VALUES ( :fecha_new, :peso_new, :vac_comp, :obs_vac, :maduracion_new, :obs_mad, :examen_fis, :obs_exam, :PC, :PPC, :talla_new, :alimentacion_new, :obs_gen, :id_pac, :id_usr )",[ 'fecha_new'=>$Arreglo_datos[0], 'peso_new'=>$Arreglo_datos[1], 'vac_comp'=>$Arreglo_datos[2], 'obs_vac'=>$Arreglo_datos[3], 'maduracion_new'=>$Arreglo_datos[4], 'obs_mad'=>$Arreglo_datos[5], 'examen_fis'=>$Arreglo_datos[6], 'obs_exam'=>$Arreglo_datos[7], 'PC'=>$Arreglo_datos[8], 'PPC'=>$Arreglo_datos[9], 'talla_new'=>$Arreglo_datos[10] , 'alimentacion_new'=>$Arreglo_datos[11], 'obs_gen'=>$Arreglo_datos[12], 'id_usr'=>$Arreglo_datos[13], 'id_pac'=>$Arreglo_datos[14] ]);
        $id = $this->queryList("SELECT id FROM control_salud ORDER BY id DESC LIMIT 1", []);
        return $id[0]['id'];
    }

    public function actualizarControlPac($Arreglo_datos, $idcontrol){
        $this->queryList("UPDATE control_salud SET peso=:peso_new, vacunas_completas=:vac_comp, vacunas_observaciones=:obs_vac, maduracion_acorde=:maduracion, maduracion_observaciones=:obs_mad, examen_fisico_normal=:examen_fis, examen_fisico_observaciones=:obs_exam, percentilo_cefalico=:PC , percentilo_perimetro_cefalico=:PPC, talla=:talla_new, alimentacion=:alimentacion_new, observaciones_generales=:obs_gen, id_usuario_registro=:id_usr WHERE id=:idcontrol ",[ 'peso_new'=>$Arreglo_datos[0], 'vac_comp'=>$Arreglo_datos[1], 'obs_vac'=>$Arreglo_datos[2], 'maduracion'=>$Arreglo_datos[3], 'obs_mad'=>$Arreglo_datos[4], 'examen_fis'=>$Arreglo_datos[5], 'obs_exam'=>$Arreglo_datos[6], 'PC'=>$Arreglo_datos[7], 'PPC'=>$Arreglo_datos[8] , 'talla_new'=>$Arreglo_datos[9] , 'alimentacion_new'=>$Arreglo_datos[10] , 'obs_gen'=>$Arreglo_datos[11], 'id_usr'=>$Arreglo_datos[12], 'idcontrol'=>$idcontrol ]);
    }
    
    public function obtenerControlAModificar($idControl){
        $answer = $this->queryList("SELECT * FROM control_salud WHERE id=:controlId", ['controlId' => $idControl]);
        return $answer;
    }

}