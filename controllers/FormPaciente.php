<?php

class FormPaciente{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function forPac(){
        $view = new Home();
        $view->show('formPacientes.html.twig',[]);
    }
}