<?php 

namespace App\Core;


class Route {

    public $path;
    public $action;
    public $matches;

    public function __construct($path, $action) {
        $this->path = trim($path, '/');
        $this->action = $action;
    }

      
    public function match($url) {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $pathToMatch = "#^$path$#";
        if (preg_match($pathToMatch, $url, $matches)) {
            $this->matches = $matches;
            return true; 
        }else{
            return false;
        }
    }

    public function call() {
        $params = explode('@', $this->action);
        $controller = $params[0];

        $controllerFile = "Controller/".$controller.".class.php";
        if(!file_exists($controllerFile)){
            die("Le controller ".$controllerFile." n'existe pas");
        }

        $controller_sanitaze = "App\\Controller\\".ucfirst(strtolower($controller));
        $controller = new $controller_sanitaze();
       

        $method = $params[1];
        if( !method_exists($controller, $method)){
            die("L'action ".$method." n'existe pas");
        }
    
        return isset($this->matches[1]) ? $controller->$method($this->matches[1]) : $controller->$method(); 
    }

   
}