<?php

class Usuario{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function creaUsr(){
        $layout = IndexController::getInstance()->layout();
        Home::getInstance()->show('crearUsr.html.twig',$layout);
    }
}