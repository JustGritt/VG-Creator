<?php

namespace App\Controller;

session_start();

use App\Core\CleanWords;
//use App\Core\Sql as sql;
use App\Core\SqlPDO as test;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;

class Admin
{
    protected $pdo = null;
    public function dashboard()
    {

        if (empty($_SESSION['id']) && empty($_SESSION['token'])) {
            header("Location: " . DOMAIN . "/login");
        }

        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);
        //var_dump($_SESSION);

        $explode_url = explode("/", $_SERVER["REQUEST_URI"]);

        if (count($explode_url) == 2) {
            $view = new View("back_home", "back");
            // $view->assign("user", $user);
        } else if (count($explode_url) > 2 && $explode_url[2] == "settings") {
            $view = new View("settings", "back");
            $view->assign("user", $user);
        }

        // $view = new View("back_home", "back");
        // $view->assign("user", $user);

        //var_dump($_POST);
        if (!empty($_POST)) {
            //var_dump($_POST);
            $user->logout();
        }

        // $view = new View("product", "back");
        // $this->pdo = test::connect();
        // var_dump($pdo);
        // $sql = 'SELECT * FROM  esgi_user';

        //var_dump(sql::getInstance()->execute($sql));
        //$view->assign("firstname", $user->getFirstname()); 
    }
}
