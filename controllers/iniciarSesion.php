<?php

class IniciarSesion {
    private static $instance;
    
    public function __construct() {
        
    }
    public static function getInstance() {
    	if (!isset ($instance)) {
    		self::$instance=new self();
    	}
    	return self::$instance;
    }
    public function iniciarS() {
        $layout = IndexController::getInstance()->layout();
        if (isset($layout['pagina_activa'])){
            $layout['pagina_activa'] = 1;
        };
    	$view= new Home();
    	$view->show("iniciarSesion.html.twig",$layout);
    }
}