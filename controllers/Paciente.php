<?php

class Paciente{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function forPac(){
        $layout = IndexController::getInstance()->layout();
        
        Home::getInstance()->show('formPacientes.html.twig',$layout);
    }

    function ya_existe_paciente($numero_doc){
        $aux = PacienteValidation:: getInstance()->existePaciente($numero_doc);
        if (count($aux) == 0){
            return true;
        }else {
            return false;
        }
    }
}