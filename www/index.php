<?php

namespace App;


use App\Core\Router;
use App\Core\Route;
use App\Core\Security;
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

//Réussir à récupérer l'URI
$router = new Router($_GET['url'] ?? "");

$router->group('/', function($router) {
    $router->get('/', 'main@home');
    $router->get('/login', 'user@login');
    $router->post('/login', 'user@login');
    //$router->get('/login-google', 'user@loginwithGoogle');
    $router->get('/login-fb', 'user@loginwithfb');
    $router->get('/logout', 'user@logout');
    $router->post('/logout', 'user@logout');
    $router->get('/register', 'user@register');
    $router->post('/register', 'user@register');
    $router->get('/forget', 'passwordrecovery@pwdforget');
    $router->post('/forget', 'passwordrecovery@pwdforget');
    $router->get('/confirmation', 'confirmation@confirmation');
    $router->post('/confirmation', 'confirmation@confirmation');
    $router->get('/reset-new-password', 'confirmation@confirmationPwd');
    $router->post('/reset-new-password', 'confirmation@confirmationPwd');
});

//$router->get('/template', 'main@template');
$router->get('/client_website', 'admin@client');


$router->group('/dashboard', function($router) {
    $router->get('/', 'admin@dashboard');
    $router->post('/', 'admin@dashboard');
    if (Security::isVGdmin()){
        $router->get('/clients', 'admin@getClientsOfSite');
        $router->post('/clients', 'admin@getClientsOfSite');
        $router->get('/sites', 'admin@getsite');
        $router->post('/sites', 'admin@getsite');
    }
    $router->get('/subscribe', 'admin@dashboard');
    $router->post('/subscribe', 'admin@dashboard');
    $router->get('/settings', 'admin@dashboard');
    $router->get('/settings/profile', 'admin@dashboard');
    $router->get('/history', 'admin@dashboard');
    $router->get('/articles', 'admin@getAllArticles');
    $router->get('/articles/:id', 'admin@setEditorView')
        ->with('id', '[0-9]+');
});

$router->get('/payment', 'payment@payment');
$router->get('/test', 'admin@test');
$router->post('/test', 'admin@test');
$router->get('/test2', 'admin@client');


//TEST CLIENT WEBSITE
$router->get('/blog/:id/', 'Blog@show')->with('id' ,'[0-9]+');
$router->get('/blog/:id/:article', 'Blog@show')
    ->with('id', '[0-9]+')
    ->with('article', '([a-z\-0-9]+)'); //TEST PRUPOSE ONLY


$router->get('/@:author', 'main@initContent')
    ->with('author', '([a-z\-0-9]+)');//TEST PRUPOSE ONLY

$router->get('/@:author/:slug', 'main@initContent')
    ->with('author', '([a-z\-0-9]+)')
    ->with('slug',  '([A-Za-z]+)');//TEST PRUPOSE ONLY

$router->get('/@:author/:slug/:pages', 'main@initContent')
    ->with('author', '([a-z\-0-9]+)')
    ->with('slug',  '([A-Za-z]+)')
    ->with('pages', '([A-Za-z]+)');//TEST PRUPOSE ONLY

$router->get('/@:author/:slug/:pages/:id', 'main@initContent')
    ->with('author', '([a-z\-0-9]+)')
    ->with('slug',  '([A-Za-z]+)')
    ->with('pages', '([A-Za-z]+)')
    ->with('id', '[0-9]+');//TEST PRUPOSE ONLY

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