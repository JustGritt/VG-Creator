<?php

namespace  App\Core\Routing;

use App\Core\Exceptions\Routing\RouterException;
use App\Core\Exceptions\Routing\RouterNotFoundException;
use Closure;

class Router{

    private $url;
    private $routes = [];
    private $namedRoutes = [];
    private $path;
    private $groupPattern;
    private static $_instance = null;
    private  $current_route;

    public function __construct()
    {
        $this->url = $_GET['url'] ?? "";
    }

    /**
     * Singleton
     */
    public static function getInstance(): Router
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Router();
        }
        return self::$_instance;
    }

    /**
     * handle GET requests
     *
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return Route
     */
    public function get(string $path,  $callable, ?string $name=null):Route
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * handle PUT requests
     *
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return Route
     */
    public function put(string $path,  $callable, ?string $name=null):Route
    {
        return $this->add($path, $callable, $name, 'PUT');
    }



    /**
     * handle DELETE requests
     *
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return Route
     */
    public function delete(string $path,  $callable, ?string $name=null):Route
    {
        return $this->add($path, $callable, $name, 'DELETE');
    }

    /**
     * handle POST requests
     *
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return Route
     */
    public function post(string $path, $callable, string $name=null):Route
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * handle requests by dispatching them and,
     * also named them;
     *
     * @param string $path
     * @param  $callable
     * @param string|null $name
     * @param string|null $method
     * @return Route
     */
    private function add(string $path,  $callable, ?string $name , ?string $method): Route
    {
        if (!empty($this->groupPattern)) {
            $path = trim($this->groupPattern) . ltrim($path);
        }
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if (is_string($callable) && ($name === null)) {
            $name = $callable;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * Create a group to help to do sub routes with same base path
     *
     * @param $groupPattern
     * @param callable $routes
     * @return void
     */
    public function group($groupPattern, callable $routes):void
    {
        $this->groupPattern = $groupPattern;
        $routes($this);
        unset($this->groupPattern);
    }

    /**
     * Get url of a specific route name and add param to them
     *
     * @param string $name
     * @param array $params
     * @throws RouterException
     * @return string
     */
    public function url(string $name, array $params = []):string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException("No route matches this name");
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

    /**
     * Start the router
     *
     * @throws RouterException|RouterNotFoundException
     */
    public function run()
    {
        $method= $_SERVER['REQUEST_METHOD'];
        if(!isset($this->routes[$method])){
            throw new RouterException('This method isn\'t allowed');
        }
        foreach ($this->routes[$method] as $route){
            if( $route->match($this->url)){
                $this->current_route = $route;
                return $route->call();
            }
        }

        throw new RouterNotFoundException();
    }
}
