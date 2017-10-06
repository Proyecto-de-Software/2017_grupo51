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
    	$view= new Home();
    	$view->show("iniciarSesion.html.twig",[]);
    }
}