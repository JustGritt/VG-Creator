<?php

namespace App\Core;

use App\Utils;
use App\Utils\Utils as UtilsUtils;

class Router
{


    private $url;
    private $routes = [];
    private $namedRoutes = [];
    private $groupPattern = '';

    /**
     * Singleton
     */
    private static $_instance = null;

    /**
     * Redirect user
     */
    public $redirect = null;

    /**
     * The route action array.
     *
     * @var array
     */
    public $action;


    /*
    private function __construct()
    {
        die();
        //echo $_GET['url'] ?? "";
        $this->url = $_GET['url'] ?? "";
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Router();
        }
        return self::$_instance;
    }*/

    /**
     * Get or set the middlewares attached to the route.
     *
     * @param  array|string|null  $middleware
     * @return $this|array
     */

    /*
    public function middleware($middleware = null)
    {
        if (is_null($middleware)) {
            return (array) ($this->action['middleware'] ?? []);
        }

        if (!is_array($middleware)) {
            $middleware = func_get_args();
        }

        foreach ($middleware as $index => $value) {
            $middleware[$index] = (string) $value;
            $middlewareClass =  new Middleware();
            $middleware_methods = get_class_methods($middlewareClass);
            if (in_array($value, $middleware_methods)) {
                //var_dump("qsqsqs");
                if($middlewareClass->$value($_REQUEST)); 
               
            }
        }
        $this->action['middleware'] = array_merge(
            (array) ($this->action['middleware'] ?? []),
            $middleware
        );

        return $this;
    }


    public function get($path, $callable, $name = null): Route
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null): Route
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add($path, $callable, $name, $method)
    {
        if (!empty($this->groupPattern)) {
            $path = trim($this->groupPattern) . ltrim($path);
        }
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if (is_string($callable) && $name === null) {
            $name = $callable;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function group($groupPattern, \Closure $routes)
    {
        $this->groupPattern = $groupPattern;
        $routes($this);
        unset($this->groupPattern);
    }



    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {

            return header('HTTP/1.1 405 Method Not Allowed');
        }
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }
        return header('HTTP/1.1 404 Not Found');
    }



    public function url($name, $params = [])
    {

        if (!isset($this->namedRoutes[$name])) {
            throw new \RuntimeException("No route matches this name");
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }*/


}
