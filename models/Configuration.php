<?php

class Configuration{
    
    private $titulo_pagina;
    private $mail_contacto;
    private $elementos_pagina;
    private $pagina_activa;
    private $id;


    public function __construct($titulo,$mail,$cantidad_elementos,$activa,$id) {
        $this->titulo_pagina = $titulo;
        $this->mail_contacto = $mail;
        $this->elementos_pagina = $cantidad_elementos;
        $this->pagina_activa = $activa;
        $this->id = $id;
    }


    public function getTitle() {
        return $this->titulo_pagina;
    }
    
    public function getMail() {
        return $this->mail_contacto;
    }
    
    public function getPageNumberOfElements() {
        return $this->elementos_pagina;
    }
    
    public function getActive() {
        return $this->pagina_activa;
    }
    
    public function getId(){
        return $this->id;
    }
}