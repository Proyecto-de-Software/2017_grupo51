<?php

class IniciarSesion {
    private static $instance;
    
    public function __construct() {
        
    }
    public static function getInstance() {
    	if (!isset(self::$instance)) {
    		self::$instance=new self();
    	}
    	return self::$instance;
    }
    
    public function iniciarS($mensaje) {
        $layout = IndexController::getInstance()->layout();
        if (isset($layout['pagina_activa'])){
            $layout['pagina_activa'] = 1;
        }
        if ($mensaje != ''){
            $layout['mensaje'] = $mensaje;
        }
        Home::getInstance()->show("iniciarSesion.html.twig",$layout);
    }
    
}