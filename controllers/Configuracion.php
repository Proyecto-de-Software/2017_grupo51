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
    
    public function accesoPagConfiguracion(){
        //Accede a la seccion de configuracion del sitio.
        if(UserController::getInstance()->sePuedeAccederASeccion('configuracion')){
            $layout = IndexController::getInstance()->layout();
            $layout['titulo'] = 'Bienvenido a la seccion de configuración del sitio.';
            Home::getInstance()->show('seccionConfiguracion.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function formularioConfiguracion(){
        $this->cargaPagina('Actualización de la configuracion del sitio.');
    }
    
    public function cargaPagina($mensaje){
        if(UserController::getInstance()->sePuedeAccederASeccion('configuracion')){
            $layout = IndexController::getInstance()->layout();
            $layout['titulo'] = $mensaje;
            $configuracion = ConfigurationModule::getInstance()->indexPageInfo();
            $layout['titulo_pagina'] = $configuracion->getTitle();
            $layout['mail_contacto'] = $configuracion->getMail();
            $layout['elementos_pagina'] = $configuracion->getPageNumberOfElements();
            $layout['pagina_activa'] = $configuracion->getActive();
            $layout['id_config'] = $configuracion->getId();
            Home::getInstance()->show('seccionConfiguracion.html.twig',$layout);
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function actualizar(){
        //Actualiza la informacion de configuracion
        if(UserController::getInstance()->sePuedeAccederASeccion('configuracion')){
            if($this->validarElementosAEnviar()){
                if($_POST['estadoSitio'] == 'true'){
                    $estado = 1;
                }else{
                    $estado = 0;
                }
                $valoresActualizados = array(
                    'tituloPagina' => $_POST['tituloPagina'],
                    'mailContacto' => $_POST['mailContacto'],
                    'elementosPagina' => $_POST['elementosPagina'],
                    'estado' => $estado,
                );
                ConfigurationModule::getInstance()->actualizar($valoresActualizados);
                $layout = IndexController::getInstance()->layout();
                $layout['titulo'] = 'Configuración actualizada.';
                Home::getInstance()->show('seccionConfiguracion.html.twig',$layout);
            }else{
                UserController::getInstance()->cerrarSesion();
                IndexController::getInstance()->index();
            }
        }else{
            UserController::getInstance()->cerrarSesion();
            IndexController::getInstance()->index();
        }
    }
    
    public function validarElementosAEnviar(){
        //Valida la informacion ingresada
        if(isset($_POST['tituloPagina'])&&isset($_POST['mailContacto'])&&isset($_POST['elementosPagina'])&&isset($_POST['estadoSitio'])){
            if((is_string($_POST['tituloPagina']))&& (filter_var($_POST['mailContacto'],FILTER_VALIDATE_EMAIL)) && (is_numeric($_POST['elementosPagina']))){
                if($_POST['estadoSitio'] == 'true' || $_POST['estadoSitio'] == 'false'){
                    return true;
        }}}
        return false;
    }
    
    public function mostrarInformacion(){
        //Carga la pagina que muestra la informacion de configuracion.
        $this->cargaPagina('Tabla de configuracion.');
    }
}