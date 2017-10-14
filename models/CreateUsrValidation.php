<?php

class CreateUsrValidation extends PDORepository{
    
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function usuario_existente ($email,$nombreUsr){
		$answer = $this->queryList("SELECT id FROM usuario WHERE email=:email OR username=:nombreUsr", ['email'=>$email,'nombreUsr'=>$nombreUsr]);
        return $answer;
    }
}