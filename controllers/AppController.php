<?php

class AppController{
    private static $instance;
    private static $user;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public static function getUser(){
        if (!isset(self::$user)){
            // Aca se crea al usuario
        }
        return self::$user;
    }
}