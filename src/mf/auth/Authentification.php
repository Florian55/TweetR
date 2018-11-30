<?php

namespace mf\auth;
require_once 'AbstractAuthentification.php';

class Authentification extends AbstractAuthentification{
    
    const ACCES_LEVEL_NONE = -9999;
    protected $user_login = null;
    protected $acces_level = self::ACCES_LEVEL_NONE;
    protected $logged_in = false;
    
    
    function __construct() {
        if(isset($_SESSION['user_login'])){
            $this->user_login = $_SESSION['user_login'];
            $this->access_level = $_SESSION['access_level'];
            $this->logged_in = true;
        }
        else{
            $this->user_login = null;
            $this->access_level = self::ACCES_LEVEL_NONE;
            $this->logged_in = false;
        }
    }
    
    function updateSession($username, $level){
        $this->user_login = $username;
        $this->acces_level = $level;
        $_SESSION['user_login'] = $username;
        $_SESSION['access_level'] = $level;
        $this->logged_in = true;
    }
    
    function logout(){
        $_SESSION['user_login'] = '';
        $_SESSION['access_level'] = '';
        $this->user_login = null; 
        $this->acces_level = self::ACCES_LEVEL_NONE;
        $this->logged_in = false;
    }
    
    function checkAccessRight($requested){
        return $requested >= $this->acces_level; 
    }
    
    function login($username, $db_pass, $pass, $level){
        if($this->verifyPassword($pass, $db_pass)){
            $this->updateSession($username, $level);
        }else{
            throw new exception\AuthentificationException('Problème de connexion');
        }
    }

    function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    function verifyPassword($password, $hash){
        return password_verify($password, $hash); 
    }
  
}

