<?php

namespace App;


use App\Core\Router;
use App\Core\Observer\Newsletter;
use App\Core\Observer\ForumNewsLetterObserver;
use App\Core\Security;
use App\Core\FlashMessage;
use App\Core\ProviderInterface;
use App\Core\Oauth\ProviderFactory;
require "conf.inc.php";

session_start();

function myAutoloader($class)
{
    // $class => CleanWords();
    $class = str_replace("App\\","",$class);
    $class = str_replace("\\", "/",$class);
    if(file_exists($class.".class.php")){
        include $class.".class.php";
    }else if(file_exists($class.".php")){
        include $class.".php";
    }
}

spl_autoload_register("App\myAutoloader");
$flash_message = new FlashMessage();

$newsletter =  Newsletter::getInstance();
$forumNewsLetter = new ForumNewsLetterObserver();
$newsletter->attach($forumNewsLetter);

require "routes.php";



