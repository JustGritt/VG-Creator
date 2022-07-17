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
    $router->get('/invitation', 'confirmation@invitation');
    $router->post('/invitation', 'confirmation@invitation');
    $router->get('/reset-new-password', 'confirmation@confirmationPwd');
    $router->post('/reset-new-password', 'confirmation@confirmationPwd');
});

//$router->get('/template', 'main@template');
$router->get('/client_website', 'admin@client');

$router->group('/dashboard', function (Router $router) {
    $router->get('/', 'admin@dashboard');
    $router->post('/', 'admin@dashboard');
    if (Security::isVGdmin()) {
        $router->get('/sites', 'admin@getAllSite');
        $router->post('/sites', 'admin@getsite');
    }
    $router->get('/subscribe', 'admin@dashboard');
    $router->post('/subscribe', 'admin@dashboard');

    $router->get('/settings', 'admin@setSettingsView');
    $router->post('/settings', 'admin@setSettingsView');

    $router->get('/history', 'admin@dashboard');

    // $router->get('/articles', 'admin@getAllArticles');
    $router->get('/articles', 'admin@getAllArticles', 'admin.allPost');
    $router->get('/articles/:id', 'post@createPost')
        ->with('id', '[0-9]+');
    $router->get('/articles/create', 'post@createPost', 'post.createPost');
    $router->post('/articles/create', 'post@createPost', 'post.createPost');
    $router->get('/articles-edit/:id_post', 'post@editShowPost', 'post.editShowPost')
        ->with('id_post', '[0-9]+');
    $router->post('/articles-edit/:id_post', 'post@editShowPost', 'post.editShowPost')
        ->with('id_post', '[0-9]+');

    $router->get('/clients', 'admin@setClientsView');
    $router->post('/clients', 'admin@setClientsView');
    $router->get('/clients/add', 'admin@addClient');
    $router->post('/clients/add', 'admin@addClient');
    $router->get('/clients/invite', 'admin@inviteClient');
    $router->post('/clients/invite', 'admin@inviteClient');

    $router->get('/comments', 'comment@showComments', 'comment.showComments');
    // $router->get('/comments-edit/:id', 'comment@editComment', 'comment.editComment')->with('id', '[0-9]+');
    $router->get('/comments/:id', 'comment@editComments', 'comment.changeStatus')->with('id', '[0-9]+');
    $router->post('/comments/:id', 'comment@editComments', 'comment.changeStatus')->with('id', '[0-9]+');
    // $router->get('/comments', 'admin@getAllComments');

    $router->get('/media', 'admin@setUploadMediaView');
    $router->post('/media', 'admin@setUploadMediaView');

    $router->get('/sites', 'admin@chooseMySite');
    $router->post('/sites', 'admin@chooseMySite');

    $router->get('/categorie', 'category@show');
    $router->post('/categorie', 'category@createCategory');
    //$router->get('/categorie/create', 'category@createCategory');
    //$router->post('/categorie/create', 'category@createCategory');

});


$router->get('/payment', 'payment@payment');
$router->get('/test', 'admin@test');
$router->post('/test', 'admin@test');
$router->get('/test2', 'admin@client');
$router->get('/comment', 'comment@comment');



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

