<?php

class ClassLoader{
    
    //stock répertoire qui contient les fichiers des classes
    private $prefix;
    
    public function __construct($prefix) {
        $this->prefix = $prefix;
    }
    
    
    public function loadClass($classname){
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $classname) . ".php";
        $path = $this->prefix . DIRECTORY_SEPARATOR . $path;
       
        
        if(file_exists($path)){
           require_once ($path); 
           
        }
    }
    
    public function register(){
        spl_autoload_register([$this, 'loadClass']);
    }
   
}

