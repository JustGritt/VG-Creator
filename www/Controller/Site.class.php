<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Exceptions\Routing\RouterNotFoundException;
use App\Core\MySqlBuilder;
use App\Core\PaginatedQuery;
use App\Core\Sql;
use App\Core\View;
use App\Helpers\Utils;
use App\Model\Page;

class Site extends Controller
{
    public function __construct()
    {
        $this->render("site", "back");
        if(isset($_GET['manage-pages']) && gettype($_GET['manage-pages']) == "string"){
            $pages = $this->getPagesBySite($_GET['manage-pages']);
            if($pages) {
                $this->view->assign('pages', $pages);
            }else{
                Utils::redirect('not-found');
            }
        }
        $this->getAllSiteByIdUser();
    }

    public function showAll()
    {
        parent::setDecription('Retrouvez ici, vos différents sites, choisissez et commencez à éditer.');
    }

    /**
     * @throws RouterNotFoundException
     */
    public function editClient($id_site, $slug ){
        $page = new Page();
        $page = $page->getPageBySiteAndSlug($id_site, $slug);
        try {
            if($page instanceof Page){
                $_SESSION['id_site'] = $id_site;
                $this->render("front_template", "empty");
                $this->view->assign('page', $page);
                $this->view->assign('id_site', $id_site);
            }else{
                throw new RouterNotFoundException("Merci de créer une page pour cet site.", 404, false);
            }
        }catch ( RouterNotFoundException $exception){
            $this->render("404", "error", [], "/Errors");
            $this->view->assign("error", $exception);
        }
    }

    /**
     *
     */
    public function handleUpdate ($id_site, $id_page=null):void
    {
        if (isset($_SERVER["HTTP_REFERER"])) {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        if(!$_POST['name']) return;
        $page = new Page();
        $page->setName($_POST['name'] );
        $page->setSlug($_POST['slug']);
        $page->setIdSite($id_site);
        $page->setId($id_page);
        $page->setIdSite($id_site);
        if(!$page->save()){
            http_send_status(401);
            return;
        };
    }

    /**
     *
     */
    public function handleSite ($id_site, $id_page=null):void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case ($method == "POST"):
                if (isset($_SERVER["HTTP_REFERER"])) {
                   header("Location: " . $_SERVER["HTTP_REFERER"]);
                }
                if(!$_POST['name']) return;
                $page = new Page();
                $page->setName($_POST['name'] );
                $page->setSlug($_POST['slug']);
                $page->setIdSite($id_site);
                $page->save();
                break;
            case ($method == "DELETE"):
                if(!$id_page || !$id_site) return;
                if(!$this->deletePageBySite($id_page , $id_site)){
                   http_send_status(401);
                   return;
                };
                break;
            default:
                break;
        }
    }

    /**
     *
     */
    public function getPagesBySite ($id_site )
    {
        $user_id = $_SESSION['id'];
        $request = "SELECT ep.name,ep.slug,ep.id FROM esgi_page ep 
        LEFT JOIN esgi_site es on ep.id_site = es.id 
        LEFT JOIN esgi_role_site rs on rs.id_site = es.id 
        LEFT JOIN esgi_user_role ur on rs.id = ur.id_role_site 
        WHERE ur.id_user = $user_id and es.id = $id_site";
        $query = $request;
        return Sql::getInstance()
            ->query($query)
            ->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }


    /**
     *
     */
    public function deletePageBySite ($page_id, $id_site ):bool
    {
        $user_id = $_SESSION['id'];
        $request = "DELETE ep FROM esgi_page ep 
        LEFT JOIN esgi_site es on ep.id_site = es.id 
        LEFT JOIN esgi_role_site rs on rs.id_site = es.id 
        LEFT JOIN esgi_user_role ur on rs.id = ur.id_role_site 
        WHERE ur.id_user = $user_id and es.id = $id_site and ep.id = $page_id;";

        return Sql::getInstance()->query($request)->execute();
    }

    public function updateDataContent($id_site, $id_page){
        $this->render("article", "empty");
        $page = new Page();
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents('php://input'), $_PUT);
            $page->setId(intval($id_page));
            $page->setHtml($_PUT['html']);
            $page->setCss($_PUT['css']);
            if(!$page->save()) \http_send_status(400);
        }
    }

    public function showClient(){
        $this->render("front_template", "empty");
    }

    public function getAllSiteByIdUser(){
        $site = new \App\Model\Site();
        $sites = $site->getAllSiteByIdUser($_SESSION['id']);
        $this->view->assign("all_sites_filtered", $sites);
    }

    public function setStatusSite($id_site){
        $site = new \App\Model\Site();
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
           parse_str(file_get_contents('php://input'), $_PUT);
           $status = intval($_PUT['status']); //$_PUT contains put fields
           if(!$site->updateStatus($id_site,$status )) http_send_status(400);
        }
    }

    public function chooseMySite(){
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
            header('Location: ' . DOMAIN . '/dashboard');
        }
    }
}