<?php

namespace App\Controller;
session_start();

use App\Core\CleanWords;
use App\Core\Sql; 
//use App\Core\SqlPDO;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;

class Admin 
{   
    protected $pdo;
    public function dashboard()
    {       
        
        if(empty($_SESSION['id']) && empty($_SESSION['token'])){
            header("Location: ".DOMAIN."/login");   
        }
        
        echo "Ceci est un beau dashboard";
        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);
        /*
        $user->setIdRole($_SESSION['id_role']);
        $obj = $user->getUserById($_SESSION['id']);
        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';
        */

        $view = new View("dashboard", "back");
        $view->assign("user", $user);
        
        
        //var_dump($_POST);
        if(!empty($_POST))
        {
            //var_dump($_POST);
            $user->logout();
        }
    
        $view = new View("product", "back");
        $sql = "SELECT * FROM esi_user";
        var_dump(Sql::getInstance());
        //$view->assign("firstname", $user->getFirstname());
       
    }


    public function client() {
        echo "Ceci est un beau client";
        $view = new View("front_website", "back");
        $view->assign("user", $user);
        

    }

}