<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\FlashMessage;
use App\Core\Security;
use App\Core\Sql;
use App\Core\Uploader;
use App\Core\Verificator;
use App\Core\View;
use App\Core\PaginatedQuery;
use App\Model\Site;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Core\QueryBuilder;
use App\Core\Handler;
use App\Model\Document;
use App\Model\Backlist;
use App\Model\User_role;
use App\Core\Security; 

class Page
{
    public function __construct()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }
    }

    public function show()
    {
        $view = new View("index" , 'index');
        $view->assign("title", "Hello World");
    }

    public function edit()
    {
        $view = new View("edit" , 'edit');
        $view->assign("title", "Hello World");
    }

    public function create()
    {

        $view = new View("create" , 'create');
        $view->assign("title", "Hello World");
    }

    public function delete()
    {
        $view = new View("delete" , 'delete');
        $view->assign("title", "Hello World");
    }

}