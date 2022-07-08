<?php

namespace App\Controller;

use App\Core\View;

class Blog
{

    public function show($id)
    {

        /*
        if (empty($_SESSION['id']) && empty($_SESSION['token'])) {
            header("Location: " . DOMAIN . "/login");
        }*/

        $view = new View("editor", 'back');
    }

    public function showArticles()
    {
        $view = new View("articles", 'back');
    }
}
