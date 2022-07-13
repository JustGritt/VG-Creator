<?php

namespace App\Core;

class View
{
    private $view;
    private $template;
    private $data=[];
    private $path;

    /**
     * Set view properties in construct
     *
     * @param $view
     * @param string $template
     * @param string $path
     */
    public function __construct($view, string $template="front", string $path="View/")
    {
        $this->setView($view);
        $this->setTemplate($template);
        $this->path = $path;
    }

    public function setView($view):void
    {
        $this->view = strtolower($view);
    }

    public function setTemplate($template):void
    {
        $this->template = strtolower($template);
    }


    public function __toString():string
    {
        return "La vue est : ". $this->view;
    }

    public function includePartial($partial, $data):void
    {
        if(!file_exists("View/Partial/".$partial.".partial.php")){
            die("Le partial ".$partial." n'existe pas");
        }
        include "View/Partial/".$partial.".partial.php";
    }

    public function assign($key, $value):void
    {
        $this->data[$key]=$value;
    }

    public function un_assign($key):void
    {
        if(isset($this->data[$key])) unset($this->data[$key]);
    }

    public function __destruct()
    {
        extract($this->data);
        if(file_exists($this->path.$this->template.".tpl.php")){
            include $this->path.$this->template.".tpl.php";
        }else{
            include "View/Templates/".$this->template.".tpl.php";
        }
    }
}