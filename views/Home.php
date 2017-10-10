<?php

class Home extends TwigView {
    private static $instance;


    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function show($path, $parameters){
        self::getTwig()->display($path, $parameters);

    }
}