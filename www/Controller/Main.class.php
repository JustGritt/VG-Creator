<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Sql;
use App\Model\Page;
use App\Model\User as UserModel;
use App\Model\Site as SiteModel;
use App\Helpers\Utils;

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
        $user = $user->getUserByPseudo($author);
        $site_of_user = $user->getSitesOfUser($user->getId());

        foreach ($site_of_user as $site) {
            if(in_array($site_title, $site)) {
                $id_site = $site['id'];
            }
        }
        if (!isset($id_site)) {
            Utils::redirect('not-found');
        }

        $user_role = $user->getRoleOfUser($user->getId(), $id_site);
        $content =  $this->getSiteHtml($id_site, $site_title, $author);
        $site = new SiteModel();
        $site_info = $site->getSiteById($id_site);

        if(!$site_info || $site_info->getStatus() == 0){
            header("Location: " . DOMAIN . "/login");
        }

        if(($user_role['role'] == 'Admin')  && !is_null($id_site) && !is_null($content)){
            echo $content->getHtml();
            echo "<style>". $content->getCss()."</style>";
            if(isset($url_parse[2]) && $url_parse[2]  == "articles"){
                $articles = $this->getArticles($id_site);
                foreach($articles as $article){
                    echo '<article>' . $article->getBody() . '</article>';
                    echo "<a href='".DOMAIN."/{$author}/{$site_title}/articles/{$article->getArticletSlug()}'>{$article->getTitle()}</a>";
                }
                echo "<a href='".DOMAIN."/{$author}/{$site_title}/'>Retour</a>";
            }
        }
        die();

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

    public function getArticles($id_site){
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_post', ['*'])
            ->where('id_site', $id_site)
            ->getQuery();
        $result = Sql::getInstance()->query($query);
        $result->execute();
        $articles = $result->fetchAll(\PDO::FETCH_CLASS, 'App\Model\Post');
        return $articles;

    }

    public function getSiteHtml($id_site, $site_title, $author){
        $uri = explode("{$site_title}", $_SERVER['REQUEST_URI']);
        $slug = $uri[1];
        if ($uri[1] == "" || $uri[1] == "/"){
            $slug  = "homepage";
        }elseif($uri[1] == "/articles" || $uri[1] == "/articles/"){
            $slug = "articles";
        }elseif(in_array("articles", explode("/",$uri[1])) && count(explode("/",$slug)) >= 2){
            echo '<link rel="stylesheet" href="/dist/css/articles.css">';
            $articles = $this->getArticles($id_site);
            foreach($articles as $article){
                echo '<article class="card-article">' . $article->getBody() . '</article>';
                echo "<a href='".DOMAIN."/{$author}/{$site_title}/articles/'>Retour</a>";
            }
            return;
        }
        else {
            $slug = str_replace("/", "", $slug);
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