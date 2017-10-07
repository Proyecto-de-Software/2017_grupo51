<?php
require_once('controllers/AppController.php');
class UserController{
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function iniciarSesion(){
        
    }
    
}