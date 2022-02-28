<?php

namespace App\Controller;
use App\Core\View;
use App\Utils\DynamicSvg;

class Admin
{

    public function dashboard()
    {
       // echo "Ceci est un beau dashboard";
        $view = new View("back_home", "back");
        $icons = new DynamicSvg();
        $view->assign("icons", $icons);
       
    }

}