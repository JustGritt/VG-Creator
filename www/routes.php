<?php

namespace App;

//use App\Core\Router;
use App\Core\Routing\Router;
use App\Core\Security;
use App\Core\View;

//Réussir à récupérer l'URI
//$router =  Router::getInstance();

$router =  Router::getInstance();


$router->group('/', function (Router $router) {
    $router->get('/', 'main@home', 'home');
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


$router->group('/dashboard', function (Router $router) {
    $router->get('/', 'admin@dashboard');
    $router->post('/', 'admin@dashboard');
    if (Security::isVGdmin()) {
        $router->get('/clients', 'admin@dashboard');
        $router->post('/clients', 'admin@dashboard', 'admin.clients');
        $router->get('/clients/add', 'admin@addClient', 'admin.addClient');
        $router->post('/clients/add', 'admin@addClient');
        $router->get('/sites', 'admin@getAllSite');
        $router->post('/sites', 'admin@getsite');
    }
    $router->get('/subscribe', 'admin@dashboard');
    $router->post('/subscribe', 'admin@dashboard');
    $router->get('/settings', 'admin@dashboard');
    $router->post('/settings', 'admin@dashboard');
    $router->get('/history', 'admin@dashboard');
    $router->get('/articles', 'post@getAllArticles', 'admin.allPost');
    $router->get('/articles/:id', 'post@createPost')->with('id', '[0-9]+');
    $router->delete('/articles/:id', 'post@deletePost', 'post.deletePost')->with('id', '[0-9]+');

    $router->get('/articles/create', 'post@createPost', 'post.createPost');
    $router->post('/articles/create', 'post@createPost', 'post.createPost');

    $router->get('/articles-edit/:id_post', 'post@editShowPost', 'post.editShowPost')->with('id_post', '[0-9]+');
    $router->post('/articles-edit/:id_post', 'post@editShowPost', 'post.editShowPost')->with('id_post', '[0-9]+');

    $router->get('/clients', 'admin@dashboard');
    $router->post('/clients', 'admin@dashboard');
    $router->get('/clients/edit', 'admin@editClient');
    $router->post('/clients/edit', 'admin@editClient');

    // sites
    $router->get('/sites', 'site@showAll', 'post.showAll');
    $router->get('/client_website', 'site@client');


    $router->get('/comments', 'admin@getAllComments');
    $router->get('/media', 'media@setuploadmediaview' , 'dashboard.media');
    $router->post('/media', 'media@setuploadmediaview' , 'dashboard.media');
});


$router->get('/payment', 'payment@payment');
$router->get('/test', 'admin@test');
$router->post('/test', 'admin@test');
$router->get('/test2', 'admin@client');


//TEST CLIENT WEBSITE
$router->get('/blog/:id/', 'Blog@show')
    ->with('id', '[0-9]+');
$router->get('/blog/:id/:article', 'Blog@show')
    ->with('id', '[0-9]+')
    ->with('article', '([a-z\-0-9]+)'); //TEST PRUPOSE ONLY

$router->get('/@:author', 'main@initContent')
    ->with('author', '([A-Za-z\-0-9]+)'); //TEST PRUPOSE ONLY

$router->get('/@:author/:slug', 'main@initContent')
    ->with('author', '([A-Za-z\-0-9]+)')
    ->with('slug',  '([A-Za-z]+)'); //TEST PRUPOSE ONLY

$router->get('/@:author/:slug/:pages', 'main@initContent')
    ->with('author', '([A-Za-z\-0-9]+)')
    ->with('slug',  '([A-Za-z]+)')
    ->with('pages', '([A-Za-z]+)'); //TEST PRUPOSE ONLY

$router->get('/@:author/:slug/:pages/:id', 'main@initContent')
    ->with('author', '([A-Za-z\-0-9]+)')
    ->with('slug',  '([A-Za-z]+)')
    ->with('pages', '([A-Za-z]+)')
    ->with('id', '[0-9]+'); //TEST PRUPOSE ONLY



    $router->run();

