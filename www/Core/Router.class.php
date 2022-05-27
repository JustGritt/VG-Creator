<?php 

namespace App\Core;


class Router {
    
    public $url;
    public $routes = [];

    public function __construct($url) {
        $this->url = trim($url , '/');
    }

    public function get($path, $action) { 
        $this->routes['GET'][] = new Route($path, $action);
    }

    public function post($path, $action) {
        $this->routes['POST'][] = new Route($path, $action);
    }

    public function run(){
        $request_method = $_SERVER['REQUEST_METHOD'];
        if(!isset($this->routes[$request_method])){
            return header('HTTP/1.0 405 Method Not Allowed');
        }
        foreach($this->routes[$request_method] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        //throw new \Exception("La route ".$this->url." n'existe pas");
        return header('HTTP/1.0 404 Not Found');
    }


  
} 