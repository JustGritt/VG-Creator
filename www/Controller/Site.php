<?php

namespace App\Controller;

use App\Core\Controller;

class Site extends Controller
{
    public function __construct()
    {
        $this->render("post", "back");
    }
}