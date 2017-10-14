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

    function crearTablaPaciente(){
        $arregloPac = array("0"=> $_POST["ApellidoP"], "1"=> $_POST["NombreP"],"2"=> $_POST["FecNacP"],"3"=> $_POST["GeneroP"] ,"4"=> $_POST["TipoDocP"], "5"=> $_POST["NroDocP"], "6"=> $_POST["DomicP"], "7"=> $_POST["TelefonoP"], "8"=> $_POST["ObraSocP"] );
        UserValidation::getInstance()->insertarPaciente($arregloPac);
    }
}