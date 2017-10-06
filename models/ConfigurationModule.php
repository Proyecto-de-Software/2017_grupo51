<?php

class ConfigurationModule extends PDORepository{
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function indexPageInfo(){
        $answer = $this->queryList("SELECT * FROM configuracion",[]);
        $final_answer = new Configuration($answer[0]['titulo_pagina'],$answer[0]['mail_contacto'],$answer[0]['elementos_pagina'],$answer[0]['pagina_activa']);
        return $final_answer;
    }
    
}