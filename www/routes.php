<?php

namespace App;

use App\Core\Routing\Router;
use App\Core\Security;

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

$router->group('/dashboard', function (Router $router) {
    $router->get('/', 'admin@dashboard');
    $router->post('/', 'admin@dashboard');
    if (Security::isVGdmin()) {
        $router->get('/clients', 'admin@setClientsView');
        $router->post('/clients', 'admin@setClientsView');
        $router->get('/clients/add', 'admin@addClient', 'admin.addClient');
        $router->post('/clients/add', 'admin@addClient');
        $router->get('/sitesvg', 'admin@getSites');
        $router->post('/sitesvg', 'admin@banSites');
    }
    $router->get('/subscribe', 'admin@dashboard');
    $router->post('/subscribe', 'admin@dashboard');

    $router->get('/settings', 'admin@setSettingsView');
    $router->post('/settings', 'admin@setSettingsView');
    $router->delete('/settings/:id', 'admin@deleteAccount', 'admin.deleteAccount')->with('id', '[0-9]+');

    $router->get('/categories', 'category@show', 'category.show');
    $router->post('/categories', 'category@createCategory', 'category.createCategory');
    $router->delete('/categories/:id', 'category@deleteCategory', 'category.deleteCategory')->with('id', '[0-9]+');

    $router->get('/media', 'admin@setUploadMediaView', 'admin.uploadMedia');
    $router->post('/media', 'admin@setUploadMediaView', 'admin.uploadMedia');
    $router->delete('/media/delete/:id', 'admin@deleteMedia' , 'admin.deleteMedia')->with('id', '[0-9]+');

    $router->get('/history', 'admin@dashboard');
    $router->get('/articles', 'post@getAllArticles', 'admin.allPost');
    $router->get('/articles/:id', 'post@createPost')->with('id', '[0-9]+');
    $router->delete('/articles/:id', 'post@deletePost', 'post.deletePost')->with('id', '[0-9]+');

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
    $router->get('/comments/:id', 'comment@editComments', 'comment.changeStatus')->with('id', '[0-9]+');
    $router->post('/comments/:id', 'comment@editComments', 'comment.changeStatus')->with('id', '[0-9]+');

    $router->get('/changeAccount', 'admin@chooseMySite');
    $router->post('/changeAccount', 'admin@chooseMySite');


    // sites
    $router->get('/sites', 'site@showAll', 'post.showAll');
    $router->get('/sites/create', 'site@createSite', 'site.createSite');
    $router->post('/sites/create', 'site@createSite', 'site.createSite');

    $router->get('/sites/:id_site/edit/client_website/:slug', 'site@editClient', 'site.editClient')->with('id_site', '[0-9]+')->with('slug', '[A-Za-z0-9]+');
    $router->get('/sites/:id_site/client_website/:slug', 'site@showClient', 'site.showClient')->with('id_site', '[0-9]+')->with('slug', '[A-Za-z0-9]+');

    $router->put('/sites/:id_site/update/page/:id_page', 'site@updateDataContent', 'site.updateDataContent')->with('id_site', '[0-9]+')->with('id_page', '[0-9]+');
    $router->put('/sites/:id', 'site@setStatusSite', 'site.setStatusSite')->with('id', '[0-9]+');

    $router->post('/sites/:id_site/page', 'site@handleSite', 'site.handleSite.Create')->with('id_site', '[0-9]+');
    $router->post('/sites/:id_site/page/:id_page', 'site@handleUpdate','site.handleSite.Update')->with('id_site', '[0-9]+')->with('id_page', '[0-9]+');
    $router->delete('/sites/:id_site/page/:id_page', 'site@handleSite', 'site.handleSite.Delete')->with('id_site', '[0-9]+')->with('id_page', '[0-9]+');

    $router->get('/comments', 'admin@getAllComments');
    $router->get('/media', 'media@setuploadmediaview' , 'dashboard.media');
    $router->post('/media', 'media@setuploadmediaview' , 'dashboard.media');
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

