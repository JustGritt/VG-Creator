<?php

namespace App\Controller;

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
        $user = new UserModel();
        $id_site = $this->getSite($site_title);
        if($user->getUserByPseudo($author) && !is_null($id_site)){
          //GET THE SITE
            echo 'OK';
            $containt = $this->getSiteContaint($id_site, $site_title);
            echo $containt;
            if(is_null($containt)){
                return;
            }
        }
        // Check if the author exists in direcotry
        /*
        if (is_dir("./UserSites/".$author)){
            echo "Author exists";
            $path = "./UserSites/".$author."/".$site_title."/";
            if (is_dir($path) && $site_title != ""){
                echo " Site exists";
                $view = new View("index", 'client', $path);

            } else {
                echo " Site does not exist";
            }

        }*/
        return header('HTTP/1.1 404 Not Found');
    }

    public function getSite($slug){
        $sql = "SELECT id_site
        FROM esgi_site
        WHERE name = ?";
        $test = Sql::getInstance()->prepare($sql);
        $test->execute(array(addslashes($slug)));
        //$result = Sql::getInstance()->query($sql)->rowCount();
        $result = $test->fetch();
        return $result['id_site'];
    }

    public function getSiteContaint($id_site, $site_title){
        $uri = explode("{$site_title}", $_SERVER['REQUEST_URI']);
        $slug = $uri[1];
        if ($uri[1] == "" || $uri[1] == "/"){
            $slug  = "homepage";
        }
        $sql = "SELECT containts FROM `esgi_page` WHERE id_site = 2 and slug = ?";
        $test = Sql::getInstance()->prepare($sql);
        $test->execute(array(addslashes($slug)));
        return $test->fetch()['containts'] ?? null;
    }


}