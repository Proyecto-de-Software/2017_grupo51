<?php

class CreateUsuario{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function creaUsr(){
        $view = new Home();
        $view->show('crearUsr.html.twig',[]);
    }
}