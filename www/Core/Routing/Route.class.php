<?php

namespace  App\Core\Routing;

use http\Params;

class Route{


    private $path;
    private  $callable;
    private $matches=[];
    private $params=[];


    /**
     * @param string $path
     * @param string|Closure $callable
     */
    public function __construct(string $path,$callable)
    {
        $this->path = trim($path,'/');;
        $this->callable = $callable;
    }

    /**
     * Match url with regex
     *
     * @param string $url
     * @return bool
     */
    public function match(string $url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    private function paramMatch($match): string
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    /**
     * Get url of a specific route name and concat with param to them
     *
     * @param array $params
     * @return string
     */
    public function getUrl(array $params):string
    {
        $path = $this->path;
        foreach ($params as $key=> $param){
            $path = str_replace(":$key", $param, $path);
        }
        return $path;
    }


    /**
     * Allow user to use custom regex to find params
     *
     * @param $param
     * @param $regex
     * @return Route
     */
    public function with($param, $regex):Route
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * Launch function closure with matches params, or
     * dispatch to controller to handle through application
     *
     * @return Route
     */
    public function call()
    {
        if (is_string($this->callable)) {
            $params = explode('@', $this->callable);
            $controller = "App\\Controller\\" . ucfirst(strtolower($params[0]));
            $controller = new $controller();
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}