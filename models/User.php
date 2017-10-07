<?php

class User{
    private $mail;
    private $username;
    private $password;
    private $active;
    private $updated_at;
    private $created_at;
    private $first_name;
    private $last_name;
    private $user_rol;
    
    public function __construct($mail,$username,$password,$active,$updated_at,$created_at, $first_name, $last_name, $rol) {
        $this->mail = $mail;
        $this->username = $username;
        $this->password = $password;
        $this->active = $active;
        $this->updated_at = $updated_at;
        $this->created_at = $created_at;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->user_rol = $rol;
    }
    
    
}