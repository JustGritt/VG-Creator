<?php

namespace App\Controller;

use App\Core\Cache;
use App\Core\View;
use App\Core\Sql;
use App\Model\User as UserModel;
class Main {

    public function home()
    {
        $view = new View("front_website");
    }

    public function template()
    {
        $view = new View("front_website");
    }

    public function initContent(){
        //$view = new View("front_template");
        $url_parse = explode("/", $_GET['url']);
        $author = $url_parse[0];
        $site_title = $url_parse[1] ?? "";
        // Check if the author exists in direcotry
        if (is_dir("./UserSites/".$author)){
            echo "Author exists";
            $path = "./UserSites/".$author."/".$site_title."/";
            if (is_dir($path) && $site_title != ""){
                echo " Site exists";
                $user = new UserModel();
                $user = $user->getUserByPseudo($author);
                var_dump($author);
                $view = new View("index", 'client', $path);
                $view->assign("user", $user);
                $author = 'Auhtor' . $user['pseudo'];


                /*
                $view->assign("site_title", $site_title);
                $view->assign("author", $author);
                $view->assign("path", $path);
                */
            } else {
                echo " Site does not exist";
            }

        }
        //header('HTTP/1.1 404 Not Found');
        /*
        $find_author = $this->findAuthorByName($author);
        if (!isset($find_author)) {
            //$view->render("404");
            header("Location: /404");
        }

        var_dump($find_author);
        */
    }

    public function findAuthorByName($author){
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $request = $queryBuilder
            ->select('esgi_site', ['id_site'])
            ->where('name', $author)
            ->getQuery();
        $result = Sql::getInstance()->query($request)->fetchAll();
        return $result;
    }



}