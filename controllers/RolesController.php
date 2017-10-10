<?php

class RolesController{
    private static $instance;
    
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        
    }
    
    public function permitirAccesoSitioInhabilitado($rol){
        return ConfigurationModule::getInstance()->tienePermiso($rol);
    }
}