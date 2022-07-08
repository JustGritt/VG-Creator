<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Controller;
use App\Helpers\Utils;


class Error extends Controller
{
    public function show404(){
        $this->render("404", "error", [], "Errors");
        Utils::abort("404");
    }
}
