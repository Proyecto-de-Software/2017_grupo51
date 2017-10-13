<?php

class PacienteValidation extends PDORepository{
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function existePaciente($nroDoc){
        $answer = $this->queryList("SELECT id FROM paciente WHERE numero_documento=:nroDoc", ['nroDoc'=>$nroDoc]);
        return $answer;
    }
}