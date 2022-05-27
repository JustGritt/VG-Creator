<?php

namespace App;


use App\Core\Router;
use App\Core\Route;

require "conf.inc.php";

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

//Réussir à récupérer l'URI
$url = $_SERVER['REQUEST_URI'];
$router = new Router($url);

$router->get('/', 'main@home');
$router->get('/template', 'main@template');
$router->get('/login', 'user@login');
$router->post('/login', 'user@login');
$router->get('/login-fb', 'user@loginwithfb');
$router->get('/logout', 'user@logout');
$router->post('/logout', 'user@logout');
$router->get('/register', 'user@register');
$router->post('/register', 'user@register');
$router->get('/forget', 'passwordrecovery@pwdforget');
$router->post('/confirmation', 'confirmation@confirmation');
$router->post('reset-new-password', 'confirmation@confirmationPwd');
$router->get('/dashboard', 'admin@dashboard');
$router->get('/dashboard/subscribe', 'admin@dashboard');
$router->get('/dashboard/settings', 'admin@dashboard');
$router->get('/client_website', 'admin@client');
$router->get('/payment', 'payment@payment');
$router->get('/blog/:id', 'Blog@show'); //TEST PRUPOSE ONLY 
$router->run();



/*
$uri = $_SERVER["REQUEST_URI"];

$routeFile = "routes.yml";
if(!file_exists($routeFile)){
    die("Le fichier ".$routeFile." n'existe pas");
}

$routes = yaml_parse_file($routeFile);
$global_uri = strtok($uri, '?');

if (empty($routes[$global_uri]) ||  empty($routes[$global_uri]["controller"])  ||  empty($routes[$global_uri]["action"])) {
    var_dump($global_uri);
    //die("Erreur 404");
}


$controller = ucfirst(strtolower($routes[$global_uri]["controller"]));
$action = strtolower($routes[$global_uri]["action"]);
*/


/*
if( empty($routes[$uri]) ||  empty($routes[$uri]["controller"])  ||  empty($routes[$uri]["action"])){
    die("Erreur 404");
}

$controller = ucfirst(strtolower($routes[$uri]["controller"]));
$action = strtolower($routes[$uri]["action"]);
*/

/*
 *
 *  Vérfification de la sécurité, est-ce que la route possède le paramètr security
 *  Si oui est-ce que l'utilisation a les droits et surtout est-ce qu'il est connecté ?
 *  Sinon rediriger vers la home ou la page de login
 *
 */

/*
$controllerFile = "Controller/".$controller.".class.php";
if(!file_exists($controllerFile)){
    die("Le controller ".$controllerFile." n'existe pas");
}
//Dans l'idée on doit faire un require parce vital au fonctionnement
//Mais comme on fait vérification avant du fichier le include est plus rapide a executer
include $controllerFile;

$controller = "App\\Controller\\".$controller;
if( !class_exists($controller)){
    die("La classe ".$controller." n'existe pas");
}
// $controller = User ou $controller = Global
$objectController = new $controller();

if( !method_exists($objectController, $action)){
    die("L'action ".$action." n'existe pas");
}
// $action = login ou logout ou register ou home
$objectController->$action();
*/