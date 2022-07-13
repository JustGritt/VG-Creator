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
    public function render($view, $template,array $params, ?string $path= ""):void
    {
        $this->view =  new View($view, $template, $path);
        foreach ($params as $key => $value){
          $this->view->assign($key, $value);
      }
    }

}