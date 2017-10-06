<?php

class InicioSesion {
    
    private static $instance;
    
    public function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function inicio(){
        $parameters = array();
        $view = new Home();
        $view->show('index.html.twig',$parameters);
    }
}