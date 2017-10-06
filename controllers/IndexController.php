<?php

class IndexController {
    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
        
    public function index(){
        $indexConfiguration = ConfigurationModule::getInstance()->indexPageInfo();
        $parameters['titulo_pagina'] = $indexConfiguration->getTitle();
        $parameters['mail_contacto'] = $indexConfiguration->getMail();
        $parameters['pagina_activa'] = $indexConfiguration->getActive();
        if(!$parameters['pagina_activa']){
            $parameters['sitioInhabilitado'] = 'Sitio no disponible por el momento.';
        }
        $view = new Home();
        $view->show('index.html.twig',$parameters);
    }
    
    
}

