<?php

namespace App;


use App\Core\Router;
use App\Core\Route;
use App\Core\Security;
use App\Core\FlashMessage;
require "conf.inc.php";

session_start();

function myAutoloader($class)
{
    // $class => CleanWords();
    $class = str_replace("App\\","",$class);
    $class = str_replace("\\", "/",$class);
    if(file_exists($class.".class.php")){
        include $class.".class.php";
    }
}

spl_autoload_register("App\myAutoloader");
$flash_message = new FlashMessage();

require "routes.php";
