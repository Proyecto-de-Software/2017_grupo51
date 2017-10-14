<?php


class IndexController{

    private static $instance;
    
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function layout(){
        //Funcion que construye el layout de la pagina, igual para todas.
        $indexConfiguration = ConfigurationModule::getInstance()->indexPageInfo();
        $parameters['titulo_pagina'] = $indexConfiguration->getTitle();
        $parameters['mail_contacto'] = $indexConfiguration->getMail();
        $parameters['pagina_activa'] = $indexConfiguration->getActive();
        if(!$parameters['pagina_activa']){
            $parameters['sitioInhabilitado'] = 'Sitio no disponible por el momento.';
        }
        return $parameters;
    }
        
    public function index(){
        //Accede al index del sitio.
        $layout = self::layout();
        Home::getInstance()->show('index.html.twig',$layout);
    }
    
    
}

