<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\PaginatedQuery;
use App\Core\View;

class Site extends Controller
{
    public function __construct()
    {
        $this->render("site", "back");

    }

    public function client()
    {
        $this->render("front_template", "empty");
    }

    public function showAll()
    {
        parent::setDecription('Retrouvez ici, vos différents sites, choisissez et commencez à éditer.');
    }

    public function chooseMySite(){
        var_dump($_SESSION);
        $site = new Site();
        $pagination = new PaginatedQuery(
            $site->getQueryAllsiteByIdUser($_SESSION['id']),
            $site->getCountAllSiteByIdUser($_SESSION['id']),
            10);

        $result = $pagination->getItems();

        $view = new View('login-step-2', 'back');

        $view->assign('site', $result);
        $view->assign('previous', $pagination->previousLink('sites'));
        $view->assign('next', $pagination->nextLink('sites'));

        if(!empty($_POST )) {

            $_SESSION['id_site'] = $_POST['id_site'];
            $_SESSION['role'] = $_POST['role'];
            //$_SESSION[strtoupper($_POST['site'])] = $_POST['role'];

            header('Location: ' . DOMAIN . '/dashboard');
            return;
        }
    }
}