<?php

namespace App\Controller;

use App\Core\View;

class Blog {

    public function show($id)
    {
        $view = new View("editor" , 'back');
    }

}