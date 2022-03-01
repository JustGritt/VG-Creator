<?php

namespace App\Controller;
session_start();

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;

class Admin
{

    public function dashboard()
    {       

        if(empty($_SESSION['id']) && empty($_SESSION['token'])){
            header("Location: ".DOMAIN."/login");   
        }
        
        
        echo "Ceci est un beau dashboard";
        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);
        //var_dump($_SESSION);
    

        $view = new View("dashboard", "back");
        $view->assign("user", $user);
        
        //var_dump($_POST);
        if(!empty($_POST))
        {
            //var_dump($_POST);
            $user->logout();
        }

        $view = new View("product", "back");
        
        
        
        //$view->assign("firstname", $user->getFirstname());
       
    }

}