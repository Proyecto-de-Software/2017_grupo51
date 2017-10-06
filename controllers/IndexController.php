<?php

class IndexController{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
        
    public function index(){
        $view = new Home();
        $view->show('index.html.twig');
    }
    
    
}

