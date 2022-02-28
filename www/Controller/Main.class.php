<?php

namespace App\Controller;

use App\Core\View;

class Main {

    public function home()
    {
        echo "Page d'accueil";
        $view = new View("front_website");
    }


    public function front()
    {
        
    }



}