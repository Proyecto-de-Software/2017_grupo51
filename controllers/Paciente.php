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
}