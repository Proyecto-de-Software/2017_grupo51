<?php

class Configuracion {
    
    private static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public function permisoConfiguracion(){
        $rol = $_GET['rol'];
        if(ConfigurationModule::getInstance()->tienePermiso($rol)){
            return 'true';
        }else{
            return 'false';
        }
    }
    
    public function ejecutar(){
        echo 'En configuracion!!!!!!!!!';
    }
}