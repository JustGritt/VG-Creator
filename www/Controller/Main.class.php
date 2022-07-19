<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Sql;
use App\Model\Page;
use App\Model\User as UserModel;
use App\Model\Site as SiteModel;

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

        $url_parse = explode("/", $_GET['url']);
        $author = $url_parse[0];
        $site_title = $url_parse[1] ?? "";
        $user = new UserModel();
        $id_site = $this->getSite($site_title);
        $content =  $this->getSiteHtml($id_site, $site_title);
        $user_info = $user->getUserByPseudo($author);
        $user_role = $user->getRoleOfUser($user_info->getId(), $id_site);
        $site = new SiteModel();
        $site_info = $site->getSiteById($id_site);
        if($site_info->getStatus() == 0){
            header("Location: " . DOMAIN . "/login");
        }

        if(($user_role['role'] == 'Admin')  && !is_null($id_site) && !is_null($content)){
           echo $content->getHtml();
           echo "<style>". $content->getCss()."</style>";
           die();
        }
        //return header('HTTP/1.1 404 Not Found');
    }

    public function getSite($slug){
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_site', ['id'])
            ->where("name",  ":slug")
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);
        $result->execute(["slug" => $slug]);
        return $result->fetch()['id'] ?? null;
    }

    public function getSiteHtml($id_site, $site_title){
        $uri = explode("{$site_title}", $_SERVER['REQUEST_URI']);
        $slug = $uri[1];
        if ($uri[1] == "" || $uri[1] == "/"){
            $slug  = "homepage";
        }
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_page', ['*'])
            ->where("id_site",  ":id_site")
            ->where('slug', ':slug')
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);
        $result->execute([
            "id_site" => $id_site,
            "slug" => $slug]);
        return $result->fetchObject(Page::class) ?? null;
    }
}