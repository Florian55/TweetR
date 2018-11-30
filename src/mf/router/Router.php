<?php

namespace mf\router;

//use mf\router\AbstractRouter as AbstractRouter;

/**
 * Description of Router
 *
 * @author fharb
 */
class Router extends AbstractRouter{
    
    function __construct() {
        parent::__construct();
    }

    
    public function addRoute($name, $url, $ctrl, $mth, $acces_level) {
        self::$routes[$url] = [$ctrl, $mth, $acces_level];
        self::$aliases[$name] = $url;
    }

    public function run() {
        //je récupère l'url demandé
        $url = $this->http_req->path_info;
        
        $auth = new \mf\auth\Authentification(); 
        // si cette url existe dans le tableau $route..
        if(array_key_exists($url, self::$routes)){
            if($auth->checkAccessRight(self::$routes[$url][2])){
                //récupère la valeur dans le tableau (fournie par path_info)
                $route = self::$routes[$url];
            }
            else{
                //url par défault
               $urldefault = self::$aliases['default'];
               $route = self::$routes[$urldefault];
            }
        }else{
            //url par défault
           $urldefault = self::$aliases['default'];
           $route = self::$routes[$urldefault];
        }
       
        $c = $route[0];
        $m = $route[1];
        
        $ctrl = new $c();
        return $ctrl->$m();
    }

    public function setDefaultRoute($url) {
        self::$aliases['default'] = $url;
    }

    public function urlFor($route_name, $param_list = array()) {
        //je récupère le script_name de Http_Request.php
        $script_name = $this->http_req->script_name;
        
        //je récupère l'alias de route_name
        $path_info = self::$aliases[$route_name];
        // je lie ces deux variables pour construire l'url
        $url = $script_name . $path_info;
        
        //condition en cas de paramètres dans l'url
        if(count($param_list) > 0){
            $url .= '?';                                                //j'ajoute à l'url '?' pour lire les requêtes
            $query_string_element = [];                                 //je crée un tableau vide
            foreach ($param_list as $v){                        //parcours le tableau des paramètres de l'url
                $url .= 'id=';
                $p = implode('=', (array)$v);                                  //je rassemble les éléments de ce tableau et les lie avec un '='
                $query_string_element[] = $p;                           //j'ajoute les éléments de $p au tableau $query_string_element
            }
            $query_string = implode('&amp;', $query_string_element);    //dans le cas ou il y aurait plusieurs paramètres, j'ajoute un &
            $url .= $query_string;                                      
        }
        //je retourne l'url avec ou sans condition
        return $url;
    }
    
    static function executeRoute($alias){
        //je récupère l'url demandé
        $url = self::$aliases[$alias];
        $route = self::$aliases[$url];
        
        // si cette url existe dans le tableau $route..
        if(isset(self::$routes[$url])){
            //récupère la valeur dans le tableau (fournie par path_info)
            $route = self::$routes[$url];
        }else{
            //url par défault
           $urldefault = self::$aliases['default'];
           $route = self::$routes[$urldefault];
        }
       
        $c = $route[0];
        $m = $route[1];
        
        $ctrl = new $c();
        return $ctrl->$m();
    }
  
}
