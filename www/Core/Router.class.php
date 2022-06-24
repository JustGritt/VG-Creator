<?php

namespace App\Core;

class Router {

    private $url;
    private $routes = [];
    private $namedRoutes = [];
    private $groupPattern = '';

    public function __construct($url){
        $this->url = $url;
    }

    public function get($path, $callable, $name = null): Route
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null): Route
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add($path, $callable, $name, $method){
        if(!empty($this->groupPattern)){
            $path = trim($this->groupPattern) . ltrim($path);
        }
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if(is_string($callable) && $name === null){
            $name = $callable;
        }
        if($name){
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function group($groupPattern, \Closure $routes){
        $this->groupPattern = $groupPattern;
        $routes($this);
        unset($this->groupPattern);
    }
    public function run(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){

            return header('HTTP/1.1 405 Method Not Allowed');
        }
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        return header('HTTP/1.1 404 Not Found');
    }

    public function url($name, $params = []){
        if(!isset($this->namedRoutes[$name])){
            throw new \RuntimeException("No route matches this name");
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

// URI -> Route définie 
// URI -> Existe pas -> 404

// 




}