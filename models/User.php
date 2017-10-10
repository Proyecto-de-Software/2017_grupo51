<?php

class User{
    private $id;
    private $mail;
    private $username;
    private $password;
    private $active;
    private $updated_at;
    private $created_at;
    private $first_name;
    private $last_name;
    private $rol;


    public function __construct($id,$mail,$username,$password,$active,$updated_at,$created_at, $first_name, $last_name) {
        $this->id = $id;
        $this->mail = $mail;
        $this->username = $username;
        $this->password = $password;
        $this->active = $active;
        $this->updated_at = $updated_at;
        $this->created_at = $created_at;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
    
    public function getActive(){
        return $this->active;
    }
    
    public function getFirstName(){
        return $this->first_name;
    }
    
    public function getRol() {
        return $this->rol;
    }
    
    public function roles(){
        return UserValidation::getInstance()->roles($this->id);
    }
    
    public function setRol($rol){
        $this->rol = $rol;
    }
}