<?php

namespace App\Core;

 class Controller
{

    public $view;
    /**
     * Simplify render to controller
     *
     * @param $view
     * @param $template
     * @param array $params
     * @param string|null $path
     * @return void
     */
    public function render($view, $template,array $params=[], ?string $path= ""):void
    {
        if(isset($this->view) ){
            $this->view->setView($view);
            $this->view->setTemplate($template);
            $this->view->setPath($path);
        }else{
            $this->view =  new View($view, $template, $path);
        }

        $this->view->assign('view_name',ucfirst($this->view));
        foreach ($params as $key => $value){
            $this->view->assign($key, $value);
        }
    }

     /**
      * @param string $decription
      */
     public function setDecription(string $decription): void
     {
         $this->view->assign('description',$decription);
     }
}